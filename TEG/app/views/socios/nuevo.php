<?php
$socio = $socio ?? [];
$vehiculosForm = $vehiculosForm ?? [];
$error = $error ?? null;
$valor = fn($campo) => htmlspecialchars((string) ($socio[$campo] ?? ''));
$marcado = fn($campo, $opcion) => ((string) ($socio[$campo] ?? '') === (string) $opcion) ? 'selected' : '';
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Nuevo Socio</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Nuevo Socio</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="<?php echo BASE_URL; ?>/socios">
        <label>Codigo: <input type="text" name="codigo" value="<?php echo $valor('codigo'); ?>" required></label><br><br>
        <label>Nombre completo: <input type="text" name="nombre_completo" value="<?php echo $valor('nombre_completo'); ?>" required></label><br><br>
        <label>Cedula: <input type="text" name="cedula" value="<?php echo $valor('cedula'); ?>"></label><br><br>
        <label>Telefono: <input type="text" name="telefono" value="<?php echo $valor('telefono'); ?>"></label><br><br>
        <label>Tipo:
            <select name="tipo" required>
                <option value="propietario" <?php echo $marcado('tipo', 'propietario'); ?>>Propietario</option>
                <option value="afiliado" <?php echo $marcado('tipo', 'afiliado'); ?>>Afiliado</option>
                <option value="suplente" <?php echo $marcado('tipo', 'suplente'); ?>>Suplente</option>
            </select>
        </label><br><br>
        <label>Titular (solo si es suplente):
            <select name="id_socio_titular">
                <option value="">-- Ninguno --</option>
                <?php foreach ($titulares as $t): ?>
                <option value="<?php echo $t['id_socio']; ?>" <?php echo $marcado('id_socio_titular', $t['id_socio']); ?>>
                    <?php echo htmlspecialchars($t['codigo'] . ' - ' . $t['nombre_completo']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </label><br><br>

        <?php require __DIR__ . '/_vehiculos.php'; ?>

        <br>
        <button type="submit">Guardar</button>
        <a href="<?php echo BASE_URL; ?>/socios">Cancelar</a>
    </form>
</body>
</html>