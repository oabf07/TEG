<?php

class Periodo
{
    public static function existe(int $mes, int $anio): bool
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT id_periodo FROM periodos WHERE mes = ? AND anio = ?");
        $stmt->execute([$mes, $anio]);
        return (bool) $stmt->fetch();
    }

    public static function listarTodos(): array
    {
        $pdo = Database::getConexion();
        $stmt = $pdo->query(
            "SELECT p.*, u.nombre_completo AS generado_por_nombre
             FROM periodos p
             JOIN usuarios u ON u.id_usuario = p.generado_por
             ORDER BY p.anio DESC, p.mes DESC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Crea el periodo, sus periodo_conceptos y genera los cargos.
     * - Concepto "por unidad": un cargo por cada vehiculo activo de propietarios/afiliados activos.
     * - Concepto "por socio": un cargo por cada socio activo (sin vehiculo).
     * El campo "aplica_a" de cada concepto filtra el tipo de socio en ambos casos.
     * $montosPorConcepto = [id_concepto => monto, ...]
     */
    public static function generarFinanzasDelMes(int $mes, int $anio, array $montosPorConcepto, int $generadoPor): int
    {
        $pdo = Database::getConexion();
        $pdo->beginTransaction();

        try {
            // 1. Crear el periodo
            $stmt = $pdo->prepare("INSERT INTO periodos (mes, anio, generado_por) VALUES (?, ?, ?)");
            $stmt->execute([$mes, $anio, $generadoPor]);
            $idPeriodo = (int) $pdo->lastInsertId();

            $stmtMonto = $pdo->prepare(
                "INSERT INTO periodo_conceptos (id_periodo, id_concepto, monto) VALUES (?, ?, ?)"
            );
            $stmtConcepto = $pdo->prepare("SELECT aplica_a, cobro_por FROM conceptos_pago WHERE id_concepto = ?");
            $stmtCargo = $pdo->prepare(
                "INSERT INTO cargos (id_socio, id_periodo, id_concepto, id_vehiculo, monto) VALUES (?, ?, ?, ?, ?)"
            );

            foreach ($montosPorConcepto as $idConcepto => $monto) {
                $idConcepto = (int) $idConcepto;
                $monto = (float) $monto;

                // 2. Guardar el monto real de ese concepto para este periodo
                $stmtMonto->execute([$idPeriodo, $idConcepto, $monto]);

                // 3. Ver a quien aplica y si se cobra por socio o por unidad
                $stmtConcepto->execute([$idConcepto]);
                $concepto = $stmtConcepto->fetch();
                if (!$concepto) {
                    continue;
                }

                // 4. Generar los cargos
                $destinatarios = self::destinatarios($pdo, $concepto['aplica_a'], $concepto['cobro_por']);
                foreach ($destinatarios as $destino) {
                    $stmtCargo->execute([
                        $destino['id_socio'], $idPeriodo, $idConcepto, $destino['id_vehiculo'], $monto,
                    ]);
                }
            }

            $pdo->commit();
            return $idPeriodo;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Lista de (id_socio, id_vehiculo) a los que se les genera cargo para un concepto.
     */
    private static function destinatarios(PDO $pdo, string $aplicaA, string $cobroPor): array
    {
        $params = [];

        if ($cobroPor === 'unidad') {
            // Los vehiculos solo pertenecen a propietarios y afiliados
            $sql = "SELECT s.id_socio, v.id_vehiculo
                    FROM vehiculos v
                    JOIN socios s ON s.id_socio = v.id_socio
                    WHERE v.activo = 1 AND s.activo = 1
                      AND s.tipo IN ('propietario', 'afiliado')";
            if ($aplicaA !== 'todos') {
                $sql .= " AND s.tipo = ?";
                $params[] = $aplicaA;
            }
            $sql .= " ORDER BY s.codigo, v.placa";
        } else {
            $sql = "SELECT s.id_socio, NULL AS id_vehiculo
                    FROM socios s
                    WHERE s.activo = 1";
            if ($aplicaA !== 'todos') {
                $sql .= " AND s.tipo = ?";
                $params[] = $aplicaA;
            }
            $sql .= " ORDER BY s.codigo";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}