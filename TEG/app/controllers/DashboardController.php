<?php

class DashboardController
{
    public static function inicio(): void
    {
        Auth::requireLogin();
        $filas = Morosidad::reporteMatriz();

        $morosos = array_filter($filas, fn($f) => $f['total_deuda'] > 0);
        $totalMorosos = count($morosos);
        $totalSocios = count($filas);
        $morosidadGlobal = Morosidad::morosidadGlobal();

        // Ordenar los morosos de mayor a menor deuda, mostrar los 10 primeros
        usort($morosos, fn($a, $b) => $b['total_deuda'] <=> $a['total_deuda']);
        $topMorosos = array_slice($morosos, 0, 10);

        require __DIR__ . '/../views/dashboard/inicio.php';
    }
}