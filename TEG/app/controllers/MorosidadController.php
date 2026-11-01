<?php

class MorosidadController
{
    public static function reporte(): void
    {
        Auth::requireLogin();
        $filas = Morosidad::reporteMatriz();
        $global = Morosidad::morosidadGlobal();
        require __DIR__ . '/../views/morosidad/reporte.php';
    }
}