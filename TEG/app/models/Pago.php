<?php

class Pago
{
    public static function cargosPendientesPorSocio(int $idSocio): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "SELECT c.id_cargo, c.monto, cp.nombre AS concepto, p.mes, p.anio,
                    v.placa, v.numero_unidad,
                    COALESCE((SELECT SUM(monto) FROM pagos WHERE id_cargo = c.id_cargo), 0) AS pagado,
                    c.monto - COALESCE((SELECT SUM(monto) FROM pagos WHERE id_cargo = c.id_cargo), 0) AS saldo
             FROM cargos c
             JOIN conceptos_pago cp ON cp.id_concepto = c.id_concepto
             JOIN periodos p ON p.id_periodo = c.id_periodo
             LEFT JOIN vehiculos v ON v.id_vehiculo = c.id_vehiculo
             WHERE c.id_socio = ?
             HAVING saldo > 0
             ORDER BY p.anio, p.mes, v.placa, cp.nombre"
        );
        $stmt->execute([$idSocio]);
        return $stmt->fetchAll();
    }

    public static function historialPorSocio(int $idSocio): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "SELECT pa.*, cp.nombre AS concepto, v.placa, v.numero_unidad
             FROM pagos pa
             JOIN cargos c ON c.id_cargo = pa.id_cargo
             JOIN conceptos_pago cp ON cp.id_concepto = c.id_concepto
             LEFT JOIN vehiculos v ON v.id_vehiculo = c.id_vehiculo
             WHERE pa.id_socio = ?
             ORDER BY pa.fecha_pago DESC"
        );
        $stmt->execute([$idSocio]);
        return $stmt->fetchAll();
    }

    /**
     * Calcula el saldo real y actual de un cargo especifico
     */
    public static function saldoDeCargo(int $idCargo): float
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "SELECT c.monto - COALESCE((SELECT SUM(monto) FROM pagos WHERE id_cargo = c.id_cargo), 0) AS saldo
             FROM cargos c WHERE c.id_cargo = ?"
        );
        $stmt->execute([$idCargo]);
        $resultado = $stmt->fetchColumn();

        if ($resultado === false) {
            throw new Exception("El cargo indicado no existe.");
        }

        return (float) $resultado;
    }

    /**
     * Registra un pago, validando antes que no exceda el saldo pendiente del cargo.
     * Lanza una Exception si la validacion falla.
     */
    public static function registrar(array $datos): int
    {
        $idCargo = (int) $datos['id_cargo'];
        $monto   = (float) $datos['monto'];

        if ($monto <= 0) {
            throw new Exception("El monto del pago debe ser mayor a cero.");
        }

        $saldo = self::saldoDeCargo($idCargo);

        if ($monto > $saldo) {
            throw new Exception(
                "El monto del pago ($" . number_format($monto, 2) . ") excede el saldo pendiente ($" . number_format($saldo, 2) . ")."
            );
        }

        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "INSERT INTO pagos (id_cargo, id_socio, monto, referencia, metodo_pago, registrado_por)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $idCargo,
            $datos['id_socio'],
            $monto,
            $datos['referencia'] ?: null,
            $datos['metodo_pago'],
            $datos['registrado_por'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    /**
     * Trae todos los datos necesarios para imprimir el comprobante de un pago especifico
     */
    public static function comprobantePorId(int $idPago): ?array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare(
            "SELECT pa.*, s.codigo, s.nombre_completo, s.cedula, s.tipo,
                    cp.nombre AS concepto, p.mes, p.anio, v.placa, v.numero_unidad,
                    u.nombre_completo AS registrado_por_nombre
             FROM pagos pa
             JOIN socios s ON s.id_socio = pa.id_socio
             JOIN cargos c ON c.id_cargo = pa.id_cargo
             JOIN conceptos_pago cp ON cp.id_concepto = c.id_concepto
             JOIN periodos p ON p.id_periodo = c.id_periodo
             LEFT JOIN vehiculos v ON v.id_vehiculo = c.id_vehiculo
             JOIN usuarios u ON u.id_usuario = pa.registrado_por
             WHERE pa.id_pago = ?"
        );
        $stmt->execute([$idPago]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }
}