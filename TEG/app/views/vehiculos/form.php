<?php
$esEdicion = isset($vehiculo['id_vehiculo']);
$valor = fn($campo) => htmlspecialchars((string) ($vehiculo[$campo] ?? ''));
$accion = $esEdicion
    ? BASE_URL . '/vehiculos/editar/' . (int) $vehiculo['id_vehiculo']
    : BASE_URL . '/vehiculos';
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title><?php echo $esEdicion ? 'Editar' : 'Nuevo'; ?> Vehiculo</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1><?php echo $esEdicion ? 'Editar Vehiculo' : 'Nuevo Vehiculo'; ?></h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="<?php echo $accion; ?>">
        <label>Propietario / Afiliado:
            <select name="id_socio" required>
                <option value="">-- Seleccione --</option>
                <?php foreach ($titulares as $t): ?>
                <option value="<?php echo $t['id_socio']; ?>" <?php echo ((int) ($vehiculo['id_socio'] ?? 0) === (int) $t['id_socio']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($t['codigo'] . ' - ' . $t['nombre_completo']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </label><br><br>

        <label>Placa: <input type="text" name="placa" maxlength="10" value="<?php echo $valor('placa'); ?>" required></label><br><br>
        <label>Numero de unidad: <input type="text" name="numero_unidad" maxlength="10" value="<?php echo $valor('numero_unidad'); ?>"></label><br><br>
        <label>Marca: <input type="text" name="marca" maxlength="50" value="<?php echo $valor('marca'); ?>" required></label><br><br>
        <label>Modelo: <input type="text" name="modelo" maxlength="50" value="<?php echo $valor('modelo'); ?>" required></label><br><br>
        <label>Año: <input type="number" name="anio" min="1950" max="<?php echo (int) date('Y') + 1; ?>" value="<?php echo $valor('anio'); ?>" required></label><br><br>
        <label>Color: <input type="text" name="color" maxlength="30" value="<?php echo $valor('color'); ?>"></label><br><br>
        <label>Serial de carroceria: <input type="text" name="serial_carroceria" maxlength="30" value="<?php echo $valor('serial_carroceria'); ?>"></label><br><br>
        <label>Serial de motor: <input type="text" name="serial_motor" maxlength="30" value="<?php echo $valor('serial_motor'); ?>"></label><br><br>
        <label>Capacidad de puestos: <input type="number" name="capacidad_puestos" min="1" max="100" value="<?php echo $valor('capacidad_puestos'); ?>"></label><br><br>

        <button type="submit"><?php echo $esEdicion ? 'Actualizar' : 'Guardar'; ?></button>
        <a href="<?php echo BASE_URL; ?>/vehiculos">Cancelar</a>
    </form>
</body>
</html>
