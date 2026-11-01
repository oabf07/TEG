<?php

class Auth
{
    public static function iniciarSesion(array $usuario): void
    {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
        $_SESSION['rol'] = $usuario['rol'];
    }

    public static function estaLogueado(): bool
    {
        return isset($_SESSION['id_usuario']);
    }

    public static function idUsuarioActual(): ?int
    {
        return $_SESSION['id_usuario'] ?? null;
    }

    public static function rolActual(): ?string
    {
        return $_SESSION['rol'] ?? null;
    }

    /**
     * Corta la ejecucion y redirige a /login si no hay sesion activa.
     * Llamar como PRIMERA linea de cualquier metodo de controlador que deba protegerse.
     */
    public static function requireLogin(): void
    {
        if (!self::estaLogueado()) {
            Flight::redirect('/login');
            exit;
        }
    }

    /**
     * Ademas de exigir sesion, exige que el rol este en la lista permitida.
     * Ejemplo: Auth::requireRol(['administrador']);
     */
    public static function requireRol(array $rolesPermitidos): void
    {
        self::requireLogin();
        if (!in_array(self::rolActual(), $rolesPermitidos, true)) {
            echo 'No tienes permiso para acceder a esta seccion.';
            exit;
        }
    }

    public static function cerrarSesion(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}