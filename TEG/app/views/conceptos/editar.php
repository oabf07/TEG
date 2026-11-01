<?php
$cobroPor = $concepto['cobro_por'] ?? 'socio';
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Editar Concepto</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Editar Concepto: <?php echo htmlspecialchars($concepto['nombre']); ?></h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>
    <form method="POST" action="<?php echo BASE_URL; ?>/conceptos/editar/<?php echo $concepto['id_concepto']; ?>">
        <label>Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($concepto['nombre']); ?>" required></label><br><br>
        <label>Tipo:
            <select name="tipo_concepto" required>
                <option value="finanza" <?php echo $concepto['tipo_concepto'] == 'finanza' ? 'selected' : ''; ?>>Finanza</option>
                <option value="prestamo" <?php echo $concepto['tipo_concepto'] == 'prestamo' ? 'selected' : ''; ?>>Prestamo</option>
                <option value="cuota_extraordinaria" <?php echo $concepto['tipo_concepto'] == 'cuota_extraordinaria' ? 'selected' : ''; ?>>Cuota Extraordinaria</option>
                <option value="otro" <?php echo $concepto['tipo_concepto'] == 'otro' ? 'selected' : ''; ?>>Otro</option>
            </select>
        </label><br><br>
        <label>Monto por defecto: <input type="number" step="0.01" name="monto_default" value="<?php echo $concepto['monto_default']; ?>" required></label><br><br>
        <label>Aplica a:
            <select name="aplica_a" required>
                <option value="todos" <?php echo $concepto['aplica_a'] == 'todos' ? 'selected' : ''; ?>>Todos</option>
                <option value="propietario" <?php echo $concepto['aplica_a'] == 'propietario' ? 'selected' : ''; ?>>Solo Propietarios</option>
                <option value="afiliado" <?php echo $concepto['aplica_a'] == 'afiliado' ? 'selected' : ''; ?>>Solo Afiliados</option>
                <option value="suplente" <?php echo $concepto['aplica_a'] == 'suplente' ? 'selected' : ''; ?>>Solo Suplentes</option>
            </select>
        </label><br><br>
        <label>Se cobra por:
            <select name="cobro_por" required>
                <option value="socio" <?php echo $cobroPor === 'socio' ? 'selected' : ''; ?>>Socio (una vez por persona)</option>
                <option value="unidad" <?php echo $cobroPor === 'unidad' ? 'selected' : ''; ?>>Unidad (una vez por cada vehiculo)</option>
            </select>
        </label><br><br>
        <button type="submit">Actualizar</button>
        <a href="<?php echo BASE_URL; ?>/conceptos">Cancelar</a>
    </form>
</body>
</html>