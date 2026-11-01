<?php

class FinanzasController
{
    public static function formularioGenerar(): void
    {
        Auth::requireLogin();
        $conceptos = ConceptoPago::listarActivos();
        require __DIR__ . '/../views/finanzas/generar.php';
    }

    public static function generar(): void
    {
        Auth::requireLogin();
        $datos = Flight::request()->data->getData();
        $mes  = (int) $datos['mes'];
        $anio = (int) $datos['anio'];

        if (Periodo::existe($mes, $anio)) {
            echo "Ya existe un periodo generado para ese mes y año.
                  <a href='" . BASE_URL . "/finanzas/generar'>Volver</a>";
            return;
        }

        $montos = $datos['monto'];
        $generadoPor = Auth::idUsuarioActual();

        Periodo::generarFinanzasDelMes($mes, $anio, $montos, $generadoPor);
        Flight::redirect('/finanzas');
    }

    public static function listar(): void
    {
        Auth::requireLogin();
        $periodos = Periodo::listarTodos();
        require __DIR__ . '/../views/finanzas/listar.php';
    }
}