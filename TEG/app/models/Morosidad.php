<?php

class Morosidad
{
    /**
     * Matriz de morosidad: un socio por fila, saldo pendiente por categoria de concepto.
     */
    public static function reporteMatriz(): array
    {
        $pdo = Database::getConexion();
        $sql = "
            SELECT
                s.id_socio,
                s.codigo,
                s.nombre_completo,
                s.tipo,

                COALESCE(SUM(CASE WHEN cp.tipo_concepto = 'finanza' THEN c.monto ELSE 0 END), 0)
                    - COALESCE((SELECT SUM(p.monto) FROM pagos p
                                 JOIN cargos c2 ON c2.id_cargo = p.id_cargo
                                 JOIN conceptos_pago cp2 ON cp2.id_concepto = c2.id_concepto
                                 WHERE c2.id_socio = s.id_socio AND cp2.tipo_concepto = 'finanza'), 0)
                    AS saldo_finanza,

                COALESCE(SUM(CASE WHEN cp.tipo_concepto = 'prestamo' THEN c.monto ELSE 0 END), 0)
                    - COALESCE((SELECT SUM(p.monto) FROM pagos p
                                 JOIN cargos c2 ON c2.id_cargo = p.id_cargo
                                 JOIN conceptos_pago cp2 ON cp2.id_concepto = c2.id_concepto
                                 WHERE c2.id_socio = s.id_socio AND cp2.tipo_concepto = 'prestamo'), 0)
                    AS saldo_prestamos,

                COALESCE(SUM(CASE WHEN cp.tipo_concepto = 'cuota_extraordinaria' THEN c.monto ELSE 0 END), 0)
                    - COALESCE((SELECT SUM(p.monto) FROM pagos p
                                 JOIN cargos c2 ON c2.id_cargo = p.id_cargo
                                 JOIN conceptos_pago cp2 ON cp2.id_concepto = c2.id_concepto
                                 WHERE c2.id_socio = s.id_socio AND cp2.tipo_concepto = 'cuota_extraordinaria'), 0)
                    AS saldo_cuota_extra,

                COALESCE(SUM(c.monto), 0)
                    - COALESCE((SELECT SUM(p.monto) FROM pagos p WHERE p.id_socio = s.id_socio), 0)
                    AS total_deuda

            FROM socios s
            LEFT JOIN cargos c        ON c.id_socio = s.id_socio
            LEFT JOIN conceptos_pago cp ON cp.id_concepto = c.id_concepto
            WHERE s.activo = 1
            GROUP BY s.id_socio, s.codigo, s.nombre_completo, s.tipo
            ORDER BY s.codigo
        ";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function morosidadGlobal(): float
    {
        $pdo = Database::getConexion();
        $sql = "
            SELECT COALESCE(SUM(c.monto), 0) - COALESCE((SELECT SUM(monto) FROM pagos), 0) AS total
            FROM cargos c
        ";
        $stmt = $pdo->query($sql);
        return (float) $stmt->fetchColumn();
    }
}