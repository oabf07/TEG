<?php

class AuthController
{
    public static function formularioLogin(): void
    {
        if (Auth::estaLogueado()) {
            Flight::redirect('/inicio');
            return;
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public static function login(): void
    {
        $datos = Flight::request()->data->getData();
        $usuario = Usuario::buscarPorUsuario($datos['usuario']);

        if (!$usuario || !Usuario::verificarPassword($datos['password'], $usuario['password_hash'])) {
            echo '<p style="color:red;">Usuario o contrasena incorrectos.</p>';
            echo '<a href="' . BASE_URL . '/login">Volver a intentar</a>';
            return;
        }

        Auth::iniciarSesion($usuario);
        Flight::redirect('/inicio');
    }

    public static function logout(): void
    {
        Auth::cerrarSesion();
        Flight::redirect('/login');
    }

    public static function listarUsuarios(): void
    {
        Auth::requireRol(['administrador']);
        $usuarios = Usuario::listarActivos();
        require __DIR__ . '/../views/auth/usuarios.php';
    }

    public static function formularioNuevaSecretaria(): void
    {
        Auth::requireRol(['administrador']);
        require __DIR__ . '/../views/auth/nueva_secretaria.php';
    }

    public static function guardarSecretaria(): void
    {
        Auth::requireRol(['administrador']);
        $datos = Flight::request()->data->getData();
        Usuario::crearSecretaria($datos, Auth::idUsuarioActual());
        Flight::redirect('/usuarios');
    }
}