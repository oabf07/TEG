<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Conceptos de Pago - Rio Cristal</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Conceptos de Pago</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/finanzas/generar">Generar Finanzas del Mes</a>
    <br><br>
    <a href="<?php echo BASE_URL; ?>/conceptos/nuevo">+ Nuevo Concepto</a>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 15px;">
        <tr>
            <th>Nombre</th><th>Tipo</th><th>Monto por defecto</th><th>Aplica a</th><th>Se cobra por</th><th>Acciones</th>
        </tr>
        <?php foreach ($conceptos as $c): ?>
        <tr>
            <td><?php echo htmlspecialchars($c['nombre']); ?></td>
            <td><?php echo htmlspecialchars($c['tipo_concepto']); ?></td>
            <td>$<?php echo number_format($c['monto_default'], 2); ?></td>
            <td><?php echo htmlspecialchars($c['aplica_a']); ?></td>
            <td><?php echo (($c['cobro_por'] ?? 'socio') === 'unidad') ? 'Unidad (cada vehiculo)' : 'Socio'; ?></td>
            <td>
                <a href="<?php echo BASE_URL; ?>/conceptos/editar/<?php echo $c['id_concepto']; ?>">Editar</a> |
                <a href="<?php echo BASE_URL; ?>/conceptos/eliminar/<?php echo $c['id_concepto']; ?>" onclick="return confirm('Desactivar este concepto?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>