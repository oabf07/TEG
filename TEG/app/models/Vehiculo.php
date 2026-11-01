<?php

class Vehiculo
{
    public static function listarActivos(): array
    {
        return self::listarPorEstado(1);
    }

    public static function listarInactivos(): array
    {
        return self::listarPorEstado(0);
    }

    private static function listarPorEstado(int $activo): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "SELECT v.*, s.codigo AS codigo_socio, s.nombre_completo AS nombre_socio, s.tipo AS tipo_socio
             FROM vehiculos v
             JOIN socios s ON s.id_socio = v.id_socio
             WHERE v.activo = ?
             ORDER BY s.codigo, v.placa"
        );
        $stmt->execute([$activo]);
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $id): ?array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM vehiculos WHERE id_vehiculo = ?");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    /**
     * Vehiculos activos de un socio (para mostrarlos en su formulario de edicion)
     */
    public static function listarPorSocio(int $idSocio): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM vehiculos WHERE id_socio = ? AND activo = 1 ORDER BY placa");
        $stmt->execute([$idSocio]);
        return $stmt->fetchAll();
    }

    public static function contarActivosPorSocio(int $idSocio): int
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM vehiculos WHERE id_socio = ? AND activo = 1");
        $stmt->execute([$idSocio]);
        return (int) $stmt->fetchColumn();
    }

    public static function crear(array $datos): int
    {
        $datos = self::normalizar($datos);
        self::validar($datos, null);

        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "INSERT INTO vehiculos
                (id_socio, placa, numero_unidad, marca, modelo, anio, color,
                 serial_carroceria, serial_motor, capacidad_puestos)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $datos['id_socio'], $datos['placa'], $datos['numero_unidad'],
            $datos['marca'], $datos['modelo'], $datos['anio'], $datos['color'],
            $datos['serial_carroceria'], $datos['serial_motor'], $datos['capacidad_puestos'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $id, array $datos): bool
    {
        $datos = self::normalizar($datos);
        self::validar($datos, $id);

        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "UPDATE vehiculos
             SET id_socio = ?, placa = ?, numero_unidad = ?, marca = ?, modelo = ?, anio = ?,
                 color = ?, serial_carroceria = ?, serial_motor = ?, capacidad_puestos = ?
             WHERE id_vehiculo = ?"
        );
        return $stmt->execute([
            $datos['id_socio'], $datos['placa'], $datos['numero_unidad'],
            $datos['marca'], $datos['modelo'], $datos['anio'], $datos['color'],
            $datos['serial_carroceria'], $datos['serial_motor'], $datos['capacidad_puestos'],
            $id,
        ]);
    }

    public static function desactivar(int $id): bool
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("UPDATE vehiculos SET activo = 0 WHERE id_vehiculo = ?");
        return $stmt->execute([$id]);
    }

    public static function reactivar(int $id): bool
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("UPDATE vehiculos SET activo = 1 WHERE id_vehiculo = ?");
        return $stmt->execute([$id]);
    }

    /**
     * True si ningun campo de la fila trae contenido (fila en blanco del formulario)
     */
    public static function filaVacia(array $fila): bool
    {
        $campos = [
            'placa', 'numero_unidad', 'marca', 'modelo', 'anio',
            'color', 'serial_carroceria', 'serial_motor', 'capacidad_puestos',
        ];
        foreach ($campos as $campo) {
            if (trim((string) ($fila[$campo] ?? '')) !== '') {
                return false;
            }
        }
        return true;
    }

    /**
     * Guarda las filas de vehiculos que llegan desde el formulario de un socio.
     * - Fila con id_vehiculo: actualiza ese vehiculo (verificando que sea de este socio).
     * - Fila sin id_vehiculo: crea un vehiculo nuevo (si no esta completamente vacia).
     * Lanza una Exception con el numero de fila si alguna no es valida.
     * No abre transaccion: quien la llama decide (para poder deshacer tambien el socio).
     */
    public static function guardarVarios(int $idSocio, array $filas): void
    {
        foreach ($filas as $posicion => $fila) {
            if (!is_array($fila)) {
                continue;
            }

            $idVehiculo = (int) ($fila['id_vehiculo'] ?? 0);

            if ($idVehiculo === 0 && self::filaVacia($fila)) {
                continue;
            }

            $fila['id_socio'] = $idSocio;

            try {
                if ($idVehiculo > 0) {
                    $existente = self::buscarPorId($idVehiculo);
                    if (!$existente || (int) $existente['id_socio'] !== $idSocio) {
                        throw new Exception("el vehiculo no pertenece a este socio.");
                    }
                    self::actualizar($idVehiculo, $fila);
                } else {
                    self::crear($fila);
                }
            } catch (PDOException $e) {
                throw $e;
            } catch (Exception $e) {
                throw new Exception("Vehiculo #" . ((int) $posicion + 1) . ": " . $e->getMessage());
            }
        }
    }

    /**
     * Limpia espacios, pasa placa y seriales a mayusculas y convierte vacios en NULL.
     */
    private static function normalizar(array $d): array
    {
        $texto = fn($k) => trim((string) ($d[$k] ?? ''));
        $nulo  = fn($v) => $v === '' ? null : $v;

        return [
            'id_socio'          => (int) ($d['id_socio'] ?? 0),
            'placa'             => strtoupper($texto('placa')),
            'numero_unidad'     => $nulo($texto('numero_unidad')),
            'marca'             => $texto('marca'),
            'modelo'            => $texto('modelo'),
            'anio'              => (int) ($d['anio'] ?? 0),
            'color'             => $nulo($texto('color')),
            'serial_carroceria' => $nulo(strtoupper($texto('serial_carroceria'))),
            'serial_motor'      => $nulo(strtoupper($texto('serial_motor'))),
            'capacidad_puestos' => $nulo($texto('capacidad_puestos')) === null ? null : (int) $texto('capacidad_puestos'),
        ];
    }

    /**
     * Lanza una Exception con un mensaje claro si algun dato no es valido.
     */
    private static function validar(array $d, ?int $idActual): void
    {
        if ($d['placa'] === '' || $d['marca'] === '' || $d['modelo'] === '') {
            throw new Exception("La placa, la marca y el modelo son obligatorios.");
        }

        $anioMaximo = (int) date('Y') + 1;
        if ($d['anio'] < 1950 || $d['anio'] > $anioMaximo) {
            throw new Exception("El año del vehiculo debe estar entre 1950 y {$anioMaximo}.");
        }

        if ($d['capacidad_puestos'] !== null && $d['capacidad_puestos'] <= 0) {
            throw new Exception("La capacidad de puestos debe ser mayor a cero.");
        }

        // El vehiculo solo puede pertenecer a un propietario o afiliado activo
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT tipo FROM socios WHERE id_socio = ? AND activo = 1");
        $stmt->execute([$d['id_socio']]);
        $tipo = $stmt->fetchColumn();
        if ($tipo === false || !in_array($tipo, ['propietario', 'afiliado'], true)) {
            throw new Exception("El vehiculo debe estar asignado a un propietario o afiliado activo.");
        }

        // Placa y seriales no pueden repetirse en otro vehiculo
        self::verificarUnico('placa', $d['placa'], $idActual, 'La placa');
        self::verificarUnico('serial_carroceria', $d['serial_carroceria'], $idActual, 'El serial de carroceria');
        self::verificarUnico('serial_motor', $d['serial_motor'], $idActual, 'El serial de motor');
    }

    private static function verificarUnico(string $campo, ?string $valor, ?int $idActual, string $etiqueta): void
    {
        if ($valor === null) {
            return;
        }

        // $campo siempre viene de las llamadas de arriba, nunca del usuario
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT id_vehiculo FROM vehiculos WHERE {$campo} = ? AND id_vehiculo <> ?");
        $stmt->execute([$valor, $idActual ?? 0]);
        if ($stmt->fetch()) {
            throw new Exception("{$etiqueta} ({$valor}) ya esta registrado en otro vehiculo.");
        }
    }
}