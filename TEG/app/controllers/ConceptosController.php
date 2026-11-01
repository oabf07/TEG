<?php

class ConceptosController
{
    public static function listar(): void
    {
        Auth::requireLogin();
        $conceptos = ConceptoPago::listarActivos();
        require __DIR__ . '/../views/conceptos/listar.php';
    }

    public static function formularioNuevo(): void
    {
        Auth::requireLogin();
        require __DIR__ . '/../views/conceptos/nuevo.php';
    }

    public static function guardar(): void
    {
        Auth::requireLogin();
        $datos = Flight::request()->data->getData();
        ConceptoPago::crear($datos);
        Flight::redirect('/conceptos');
    }

    public static function formularioEditar($id): void
    {
        Auth::requireLogin();
        $concepto = ConceptoPago::buscarPorId((int) $id);
        if (!$concepto) {
            Flight::redirect('/conceptos');
            return;
        }
        require __DIR__ . '/../views/conceptos/editar.php';
    }

    public static function actualizar($id): void
    {
        Auth::requireLogin();
        $datos = Flight::request()->data->getData();
        ConceptoPago::actualizar((int) $id, $datos);
        Flight::redirect('/conceptos');
    }

    public static function eliminar($id): void
    {
        Auth::requireLogin();
        ConceptoPago::desactivar((int) $id);
        Flight::redirect('/conceptos');
    }
}