<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Generar Finanzas del Mes</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Generar Finanzas del Mes</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/conceptos">Volver a Conceptos</a>
    <br><br>
    <form method="POST" action="<?php echo BASE_URL; ?>/finanzas/generar">
        <label>Mes (1-12): <input type="number" name="mes" min="1" max="12" required></label><br><br>
        <label>Año: <input type="number" name="anio" min="2024" max="2100" required></label><br><br>

        <h3>Montos de este mes por concepto</h3>
        <p style="font-size:13px; color:#555;">
            Los conceptos que se cobran por unidad generan un cargo por cada vehiculo activo del socio:
            un socio con 4 vehiculos recibe 4 cargos de ese concepto.
        </p>
        <table border="1" cellpadding="8" style="border-collapse: collapse;">
            <tr><th>Concepto</th><th>Aplica a</th><th>Se cobra por</th><th>Monto este mes</th></tr>
            <?php foreach ($conceptos as $c): ?>
            <tr>
                <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                <td><?php echo htmlspecialchars($c['aplica_a']); ?></td>
                <td><?php echo (($c['cobro_por'] ?? 'socio') === 'unidad') ? 'Unidad (cada vehiculo)' : 'Socio'; ?></td>
                <td>
                    <input type="number" step="0.01"
                           name="monto[<?php echo $c['id_concepto']; ?>]"
                           value="<?php echo $c['monto_default']; ?>" required>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <button type="submit" onclick="return confirm('Esto generara los cargos del mes. Continuar?')">Generar Finanzas</button>
    </form>
</body>
</html>