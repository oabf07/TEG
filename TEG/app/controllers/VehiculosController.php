<?php

class VehiculosController
{
    public static function listar(): void
    {
        Auth::requireLogin();
        $verInactivos = Flight::request()->query->estado === 'inactivos';
        $vehiculos = $verInactivos ? Vehiculo::listarInactivos() : Vehiculo::listarActivos();
        require __DIR__ . '/../views/vehiculos/listar.php';
    }

    public static function formularioNuevo(): void
    {
        Auth::requireLogin();
        $titulares = Socio::listarPropietariosYAfiliados();
        $vehiculo = [];
        $error = null;
        require __DIR__ . '/../views/vehiculos/form.php';
    }

    public static function guardar(): void
    {
        Auth::requireLogin();
        $datos = Flight::request()->data->getData();

        try {
            Vehiculo::crear($datos);
        } catch (Exception $e) {
            $titulares = Socio::listarPropietariosYAfiliados();
            $vehiculo = $datos;
            $error = $e->getMessage();
            require __DIR__ . '/../views/vehiculos/form.php';
            return;
        }

        Flight::redirect('/vehiculos');
    }

    public static function formularioEditar($id): void
    {
        Auth::requireLogin();
        $vehiculo = Vehiculo::buscarPorId((int) $id);
        if (!$vehiculo) {
            Flight::redirect('/vehiculos');
            return;
        }
        $titulares = Socio::listarPropietariosYAfiliados();
        $error = null;
        require __DIR__ . '/../views/vehiculos/form.php';
    }

    public static function actualizar($id): void
    {
        Auth::requireLogin();
        $datos = Flight::request()->data->getData();

        try {
            Vehiculo::actualizar((int) $id, $datos);
        } catch (Exception $e) {
            $titulares = Socio::listarPropietariosYAfiliados();
            $vehiculo = $datos;
            $vehiculo['id_vehiculo'] = (int) $id;
            $error = $e->getMessage();
            require __DIR__ . '/../views/vehiculos/form.php';
            return;
        }

        Flight::redirect('/vehiculos');
    }

    public static function desactivar($id): void
    {
        Auth::requireLogin();
        Vehiculo::desactivar((int) $id);
        Flight::redirect('/vehiculos');
    }

    public static function reactivar($id): void
    {
        Auth::requireLogin();
        Vehiculo::reactivar((int) $id);
        Flight::redirect('/vehiculos?estado=inactivos');
    }
}