<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Morosidad - Rio Cristal</title>
    <style>
        table { border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: right; }
        th, td:nth-child(1), td:nth-child(2), td:nth-child(3) { text-align: left; }
        .moroso { color: #b30000; font-weight: bold; }
        .solvente { color: #0a7d0a; }
        tfoot td { font-weight: bold; background: #f0f0f0; }
    </style>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Reporte de Morosidad</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/socios">Volver a Socios</a>

    <table>
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Finanza</th>
                <th>Prestamos</th>
                <th>Cuota Extraordinaria</th>
                <th>TOTAL DEUDA</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filas as $f): ?>
            <tr>
                <td><?php echo htmlspecialchars($f['codigo']); ?></td>
                <td><?php echo htmlspecialchars($f['nombre_completo']); ?></td>
                <td><?php echo htmlspecialchars($f['tipo']); ?></td>
                <td>$<?php echo number_format($f['saldo_finanza'], 2); ?></td>
                <td>$<?php echo number_format($f['saldo_prestamos'], 2); ?></td>
                <td>$<?php echo number_format($f['saldo_cuota_extra'], 2); ?></td>
                <td class="<?php echo $f['total_deuda'] > 0 ? 'moroso' : 'solvente'; ?>">
                    $<?php echo number_format($f['total_deuda'], 2); ?>
                    <?php echo $f['total_deuda'] > 0 ? '(Moroso)' : '(Solvente)'; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">MOROSIDAD GLOBAL DE LA ASOCIACION</td>
                <td>$<?php echo number_format($global, 2); ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>