<?php
// Texto de la unidad: placa y, si existe, el numero de unidad. "-" cuando el cargo es por socio.
$unidad = function (array $fila): string {
    if (empty($fila['placa'])) {
        return '-';
    }
    return $fila['placa'] . (!empty($fila['numero_unidad']) ? ' (Unidad ' . $fila['numero_unidad'] . ')' : '');
};
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Cargos de <?php echo htmlspecialchars($socio['nombre_completo']); ?></title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1><?php echo htmlspecialchars($socio['codigo'] . ' - ' . $socio['nombre_completo']); ?></h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/pagos">Volver a buscar socio</a>

    <h2>Cargos pendientes</h2>
    <?php if (empty($cargos)): ?>
        <p><strong>Este socio no tiene deudas pendientes. Esta solvente.</strong></p>
    <?php else: ?>
    <table border="1" cellpadding="8" style="border-collapse: collapse;">
        <tr><th>Periodo</th><th>Concepto</th><th>Unidad</th><th>Debe</th><th>Pagado</th><th>Saldo</th><th>Abonar</th></tr>
        <?php foreach ($cargos as $c): ?>
        <tr>
            <td><?php echo $c['mes'] . '/' . $c['anio']; ?></td>
            <td><?php echo htmlspecialchars($c['concepto']); ?></td>
            <td><?php echo htmlspecialchars($unidad($c)); ?></td>
            <td>$<?php echo number_format($c['monto'], 2); ?></td>
            <td>$<?php echo number_format($c['pagado'], 2); ?></td>
            <td><strong>$<?php echo number_format($c['saldo'], 2); ?></strong></td>
            <td>
                <form method="POST" action="<?php echo BASE_URL; ?>/pagos" style="display:flex; gap:5px;">
                    <input type="hidden" name="id_cargo" value="<?php echo $c['id_cargo']; ?>">
                    <input type="hidden" name="id_socio" value="<?php echo $socio['id_socio']; ?>">
                    <input type="number" step="0.01" name="monto" max="<?php echo $c['saldo']; ?>" placeholder="Monto" required style="width:80px;">
                    <select name="metodo_pago" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="pago_movil">Pago Movil</option>
                        <option value="divisas">Divisas</option>
                    </select>
                    <input type="text" name="referencia" placeholder="Referencia">
                    <button type="submit">Pagar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <h2>Historial de pagos</h2>
    <table border="1" cellpadding="8" style="border-collapse: collapse;">
        <tr><th>Fecha</th><th>Concepto</th><th>Unidad</th><th>Monto</th><th>Metodo</th><th>Referencia</th><th>Comprobante</th></tr>
        <?php foreach ($historial as $h): ?>
        <tr>
            <td><?php echo $h['fecha_pago']; ?></td>
            <td><?php echo htmlspecialchars($h['concepto']); ?></td>
            <td><?php echo htmlspecialchars($unidad($h)); ?></td>
            <td>$<?php echo number_format($h['monto'], 2); ?></td>
            <td><?php echo htmlspecialchars($h['metodo_pago']); ?></td>
            <td><?php echo htmlspecialchars($h['referencia'] ?? '-'); ?></td>
            <td><a href="<?php echo BASE_URL; ?>/pagos/comprobante/<?php echo $h['id_pago']; ?>" target="_blank">Ver / Imprimir</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>