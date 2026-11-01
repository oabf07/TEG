<?php

class ConceptoPago
{
    public static function listarActivos(): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->query("SELECT * FROM conceptos_pago WHERE activo = 1 ORDER BY tipo_concepto, nombre");
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $id): ?array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM conceptos_pago WHERE id_concepto = ?");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    public static function crear(array $datos): int
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "INSERT INTO conceptos_pago (nombre, tipo_concepto, monto_default, aplica_a, cobro_por)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $datos['nombre'],
            $datos['tipo_concepto'],
            $datos['monto_default'],
            $datos['aplica_a'],
            self::cobroPorValido($datos['cobro_por'] ?? 'socio'),
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function actualizar(int $id, array $datos): bool
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "UPDATE conceptos_pago
             SET nombre = ?, tipo_concepto = ?, monto_default = ?, aplica_a = ?, cobro_por = ?
             WHERE id_concepto = ?"
        );
        return $stmt->execute([
            $datos['nombre'],
            $datos['tipo_concepto'],
            $datos['monto_default'],
            $datos['aplica_a'],
            self::cobroPorValido($datos['cobro_por'] ?? 'socio'),
            $id,
        ]);
    }

    public static function desactivar(int $id): bool
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("UPDATE conceptos_pago SET activo = 0 WHERE id_concepto = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Solo se aceptan los dos valores validos; cualquier otro se trata como "socio".
     */
    private static function cobroPorValido($valor): string
    {
        return $valor === 'unidad' ? 'unidad' : 'socio';
    }
}