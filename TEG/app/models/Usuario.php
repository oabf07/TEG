<?php

class Usuario
{
    public static function buscarPorUsuario(string $usuario): ?array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? AND activo = 1");
        $stmt->execute([$usuario]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    public static function verificarPassword(string $passwordPlano, string $hash): bool
    {
        return password_verify($passwordPlano, $hash);
    }

    public static function listarActivos(): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->query("SELECT id_usuario, nombre_completo, usuario, rol, fecha_creacion FROM usuarios WHERE activo = 1 ORDER BY rol, nombre_completo");
        return $stmt->fetchAll();
    }

    public static function crearSecretaria(array $datos, int $creadoPor): int
    {
        $pdo = Database::getConexion();
        $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (nombre_completo, usuario, password_hash, rol, creado_por)
             VALUES (?, ?, ?, 'secretaria', ?)"
        );
        $stmt->execute([$datos['nombre_completo'], $datos['usuario'], $hash, $creadoPor]);
        return (int) $pdo->lastInsertId();
    }
}