<?php

class Socio
{
    public static function listarTodos(): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->query(
            "SELECT s.*, t.nombre_completo AS nombre_titular
             FROM socios s
             LEFT JOIN socios t ON t.id_socio = s.id_socio_titular
             WHERE s.activo = 1
             ORDER BY s.tipo, s.codigo"
        );
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $id): ?array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM socios WHERE id_socio = ?");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    public static function crear(array $datos): int
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "INSERT INTO socios (codigo, nombre_completo, cedula, telefono, tipo, id_socio_titular)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $datos['codigo'],
            $datos['nombre_completo'],
            $datos['cedula'] ?: null,
            $datos['telefono'] ?: null,
            $datos['tipo'],
            $datos['id_socio_titular'] ?: null,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $id, array $datos): bool
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "UPDATE socios
             SET nombre_completo = ?, cedula = ?, telefono = ?, tipo = ?, id_socio_titular = ?
             WHERE id_socio = ?"
        );
        return $stmt->execute([
            $datos['nombre_completo'],
            $datos['cedula'] ?: null,
            $datos['telefono'] ?: null,
            $datos['tipo'],
            $datos['id_socio_titular'] ?: null,
            $id,
        ]);
    }

    public static function desactivar(int $id): bool
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("UPDATE socios SET activo = 0 WHERE id_socio = ?");
        return $stmt->execute([$id]);
    }

    public static function reactivar(int $id): bool
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("UPDATE socios SET activo = 1 WHERE id_socio = ?");
        return $stmt->execute([$id]);
    }

public static function listarInactivos(): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->query(
            "SELECT s.*, t.nombre_completo AS nombre_titular
             FROM socios s
             LEFT JOIN socios t ON t.id_socio = s.id_socio_titular
             WHERE s.activo = 0
             ORDER BY s.codigo"
        );
        return $stmt->fetchAll();
    }

    public static function listarPropietariosYAfiliados(): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->query(
            "SELECT id_socio, codigo, nombre_completo FROM socios
             WHERE tipo IN ('propietario','afiliado') AND activo = 1
             ORDER BY codigo"
        );
        return $stmt->fetchAll();
    }
    
    public static function listarConVehiculos(): array
{
    $pdo = Database::getConexion();
    $stmt = $pdo->query(
        "SELECT s.*, t.nombre_completo AS nombre_titular,
                (SELECT GROUP_CONCAT(
                            CONCAT(v.placa, IF(v.numero_unidad IS NULL, '', CONCAT(' (Unidad ', v.numero_unidad, ')')))
                            ORDER BY v.placa SEPARATOR ', ')
                 FROM vehiculos v
                 WHERE v.activo = 1
                   AND v.id_socio = COALESCE(s.id_socio_titular, s.id_socio)) AS vehiculos
         FROM socios s
         LEFT JOIN socios t ON t.id_socio = s.id_socio_titular
         WHERE s.activo = 1
         ORDER BY s.tipo, s.codigo"
    );
    return $stmt->fetchAll();
}
}