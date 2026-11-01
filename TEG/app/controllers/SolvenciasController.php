<?php

class SolvenciasController
{
    public static function buscarSocio(): void
    {
        Auth::requireLogin();
        $socios = Socio::listarTodos();
        require __DIR__ . '/../views/solvencias/buscar.php';
    }

    public static function verSocio($idSocio): void
    {
        Auth::requireLogin();
        $idSocio = (int) $idSocio;
        $socio = Socio::buscarPorId($idSocio);
        if (!$socio) {
            Flight::redirect('/solvencias');
            return;
        }
        $saldo = Solvencia::saldoActual($idSocio);
        $historial = Solvencia::historialPorSocio($idSocio);
        require __DIR__ . '/../views/solvencias/socio.php';
    }

    public static function emitir($idSocio): void
    {
        Auth::requireLogin();
        $idSocio = (int) $idSocio;
        $emitidoPor = Auth::idUsuarioActual();

        try {
            Solvencia::emitir($idSocio, $emitidoPor);
        } catch (Exception $e) {
            echo '<p style="color:red;"><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<a href="' . BASE_URL . '/solvencias/socio/' . $idSocio . '">Volver</a>';
            return;
        }

        Flight::redirect('/solvencias/socio/' . $idSocio);
    }

    public static function verConstancia($idSolvencia): void
    {
        Auth::requireLogin();
        $solvencia = Solvencia::buscarPorId((int) $idSolvencia);
        if (!$solvencia) {
            Flight::redirect('/solvencias');
            return;
        }
        require __DIR__ . '/../views/solvencias/constancia.php';
    }
}