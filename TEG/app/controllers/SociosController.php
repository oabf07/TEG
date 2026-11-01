<?php

class SociosController
{
    public static function listar(): void
    {
        Auth::requireLogin();
        $socios = Socio::listarConVehiculos();
        require __DIR__ . '/../views/socios/listar.php';
    }

    public static function formularioNuevo(): void
    {
        Auth::requireLogin();
        $titulares = Socio::listarPropietariosYAfiliados();
        $socio = [];
        $vehiculosForm = [];
        $error = null;
        require __DIR__ . '/../views/socios/nuevo.php';
    }

    public static function guardar(): void
    {
        Auth::requireLogin();
        $datos = Flight::request()->data->getData();
        $filas = self::filasDeVehiculos($datos);

        // Socio y vehiculos se guardan juntos: si algo falla, no queda nada a medias
        $pdo = Database::getConexion();
        $pdo->beginTransaction();

        try {
            $idSocio = Socio::crear($datos);

            if (self::admiteVehiculos($datos['tipo'] ?? '')) {
                Vehiculo::guardarVarios($idSocio, $filas);
            }

            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // Solo se muestran como mensaje los errores esperados (datos repetidos o invalidos)
            if ($e instanceof PDOException && (int) ($e->errorInfo[1] ?? 0) !== 1062) {
                throw $e;
            }

            $titulares = Socio::listarPropietariosYAfiliados();
            $socio = $datos;
            $vehiculosForm = $filas;
            $error = self::mensajeDeError($e);
            require __DIR__ . '/../views/socios/nuevo.php';
            return;
        }

        Flight::redirect('/socios');
    }

    public static function formularioEditar($id): void
    {
        Auth::requireLogin();
        $socio = Socio::buscarPorId((int) $id);
        if (!$socio) {
            Flight::redirect('/socios');
            return;
        }
        $titulares = Socio::listarPropietariosYAfiliados();
        $vehiculosForm = Vehiculo::listarPorSocio((int) $id);
        $error = null;
        require __DIR__ . '/../views/socios/editar.php';
    }

    public static function actualizar($id): void
    {
        Auth::requireLogin();
        $id = (int) $id;
        $datos = Flight::request()->data->getData();
        $filas = self::filasDeVehiculos($datos);
        $tipoNuevo = $datos['tipo'] ?? '';

        $pdo = Database::getConexion();
        $pdo->beginTransaction();

        try {
            // Un suplente no tiene vehiculos propios: no se permite el cambio si aun tiene alguno activo
            if (!self::admiteVehiculos($tipoNuevo) && Vehiculo::contarActivosPorSocio($id) > 0) {
                throw new Exception(
                    "Este socio tiene vehiculos activos. Desactivalos en la seccion Vehiculos antes de cambiarlo a suplente."
                );
            }

            Socio::actualizar($id, $datos);

            if (self::admiteVehiculos($tipoNuevo)) {
                Vehiculo::guardarVarios($id, $filas);
            }

            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($e instanceof PDOException && (int) ($e->errorInfo[1] ?? 0) !== 1062) {
                throw $e;
            }

            $titulares = Socio::listarPropietariosYAfiliados();
            $socio = array_merge(Socio::buscarPorId($id) ?? [], $datos);
            $vehiculosForm = $filas;
            $error = self::mensajeDeError($e);
            require __DIR__ . '/../views/socios/editar.php';
            return;
        }

        Flight::redirect('/socios');
    }

    public static function eliminar($id): void
    {
        Auth::requireLogin();
        $idSocio = (int) $id;

        // No permitir desactivar si el socio tiene saldo pendiente (moroso)
        $saldo = Solvencia::saldoActual($idSocio);
        if ($saldo > 0) {
            echo '<p style="color:red;"><strong>Error:</strong> No se puede desactivar a este socio porque tiene un saldo pendiente de $' . number_format($saldo, 2) . '. Debe estar solvente antes de desactivarlo.</p>';
            echo '<a href="' . BASE_URL . '/socios">Volver</a>';
            return;
        }

        Socio::desactivar($idSocio);
        Flight::redirect('/socios');
    }

    public static function reactivar($id): void
    {
        Auth::requireLogin();
        Socio::reactivar((int) $id);
        Flight::redirect('/socios');
    }

    public static function listarInactivos(): void
    {
        Auth::requireLogin();
        $socios = Socio::listarInactivos();
        require __DIR__ . '/../views/socios/inactivos.php';
    }

    /**
     * Solo propietarios y afiliados tienen vehiculos propios (los suplentes usan el de su titular).
     */
    private static function admiteVehiculos(string $tipo): bool
    {
        return in_array($tipo, ['propietario', 'afiliado'], true);
    }

    /**
     * Extrae del formulario las filas de vehiculos como lista de arrays.
     */
    private static function filasDeVehiculos(array $datos): array
    {
        $filas = $datos['vehiculos'] ?? [];
        if (!is_array($filas)) {
            return [];
        }
        return array_values(array_filter($filas, 'is_array'));
    }

    private static function mensajeDeError(Throwable $e): string
    {
        if ($e instanceof PDOException) {
            $texto = $e->getMessage();
            if (strpos($texto, 'cedula') !== false) {
                return 'Ya existe un socio con esa cedula (puede estar desactivado: revisa "Ver socios desactivados").';
            }
            if (strpos($texto, 'codigo') !== false) {
                return 'Ya existe un socio con ese codigo (puede estar desactivado: revisa "Ver socios desactivados").';
            }
            return 'Ya existe un registro con esos datos.';
        }
        return $e->getMessage();
    }
}