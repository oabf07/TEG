<?php

class Solvencia
{
    /**
     * Saldo actual y total del socio (para decidir si puede recibir solvencia)
     */
    public static function saldoActual(int $idSocio): float
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "SELECT COALESCE(SUM(c.monto), 0)
                    - COALESCE((SELECT SUM(monto) FROM pagos WHERE id_socio = ?), 0) AS saldo
             FROM cargos c WHERE c.id_socio = ?"
        );
        $stmt->execute([$idSocio, $idSocio]);
        return (float) $stmt->fetchColumn();
    }

    public static function emitir(int $idSocio, int $emitidoPor): int
    {
        $saldo = self::saldoActual($idSocio);

        if ($saldo > 0) {
            throw new Exception(
                "No se puede emitir solvencia: el socio tiene un saldo pendiente de $" . number_format($saldo, 2) . "."
            );
        }

        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "INSERT INTO solvencias (id_socio, emitido_por, saldo_al_emitir) VALUES (?, ?, ?)"
        );
        $stmt->execute([$idSocio, $emitidoPor, $saldo]);
        return (int) $pdo->lastInsertId();
    }

    public static function historialPorSocio(int $idSocio): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "SELECT sv.*, u.nombre_completo AS emitido_por_nombre
             FROM solvencias sv
             JOIN usuarios u ON u.id_usuario = sv.emitido_por
             WHERE sv.id_socio = ?
             ORDER BY sv.fecha_emision DESC"
        );
        $stmt->execute([$idSocio]);
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $idSolvencia): ?array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "SELECT sv.*, s.codigo, s.nombre_completo, s.cedula, s.tipo,
                    u.nombre_completo AS emitido_por_nombre
             FROM solvencias sv
             JOIN socios s ON s.id_socio = sv.id_socio
             JOIN usuarios u ON u.id_usuario = sv.emitido_por
             WHERE sv.id_solvencia = ?"
        );
        $stmt->execute([$idSolvencia]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }
}