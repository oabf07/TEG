<?php

class PagosController
{
    public static function buscarSocio(): void
    {
        Auth::requireLogin();
        $socios = Socio::listarTodos();
        require __DIR__ . '/../views/pagos/buscar.php';
    }

    public static function verCargos($idSocio): void
    {
        Auth::requireLogin();
        $idSocio = (int) $idSocio;
        $socio = Socio::buscarPorId($idSocio);
        if (!$socio) {
            Flight::redirect('/pagos');
            return;
        }
        $cargos = Pago::cargosPendientesPorSocio($idSocio);
        $historial = Pago::historialPorSocio($idSocio);
        require __DIR__ . '/../views/pagos/cargos.php';
    }

    public static function registrar(): void
    {
        Auth::requireLogin();
        $datos = Flight::request()->data->getData();
        $datos['registrado_por'] = Auth::idUsuarioActual();

        try {
            Pago::registrar($datos);
        } catch (Exception $e) {
            echo '<p style="color:red;"><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<a href="' . BASE_URL . '/pagos/socio/' . (int) $datos['id_socio'] . '">Volver</a>';
            return;
        }

        Flight::redirect('/pagos/socio/' . (int) $datos['id_socio']);
    }

    public static function verComprobante($idPago): void
    {
        Auth::requireLogin();
        $comprobante = Pago::comprobantePorId((int) $idPago);
        if (!$comprobante) {
            Flight::redirect('/pagos');
            return;
        }
        require __DIR__ . '/../views/pagos/comprobante.php';
    }
}