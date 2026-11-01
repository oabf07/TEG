<?php

class Database
{
    private static ?PDO $conexion = null;

    public static function getConexion(): PDO
    {
        if (self::$conexion === null) {
            $host   = '127.0.0.1';
            $puerto = '3306';
            $bd     = 'teg_rio_cristal';
            $usuario = 'root';
            $clave   = 'mysql';

            $dsn = "mysql:host={$host};port={$puerto};dbname={$bd};charset=utf8mb4";

            try {
                self::$conexion = new PDO($dsn, $usuario, $clave, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die('Error de conexion a la base de datos: ' . $e->getMessage());
            }
        }

        return self::$conexion;
    }
}