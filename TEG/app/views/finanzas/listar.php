<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Historial de Periodos</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Historial de Periodos Generados</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/finanzas/generar">Generar nuevo periodo</a>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 15px;">
        <tr><th>Mes</th><th>Año</th><th>Generado por</th><th>Fecha</th><th>Estado</th></tr>
        <?php foreach ($periodos as $p): ?>
        <tr>
            <td><?php echo $p['mes']; ?></td>
            <td><?php echo $p['anio']; ?></td>
            <td><?php echo htmlspecialchars($p['generado_por_nombre']); ?></td>
            <td><?php echo $p['fecha_generacion']; ?></td>
            <td><?php echo $p['estado']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>