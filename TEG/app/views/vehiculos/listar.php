<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Vehiculos - Rio Cristal</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1><?php echo $verInactivos ? 'Vehiculos desactivados' : 'Vehiculos registrados'; ?></h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>

    <?php if ($verInactivos): ?>
        <a href="<?php echo BASE_URL; ?>/vehiculos">Ver vehiculos activos</a>
    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/vehiculos/nuevo">+ Nuevo Vehiculo</a> |
        <a href="<?php echo BASE_URL; ?>/vehiculos?estado=inactivos">Ver vehiculos desactivados</a>
    <?php endif; ?>

    <?php if (empty($vehiculos)): ?>
        <p>No hay vehiculos para mostrar.</p>
    <?php else: ?>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 15px;">
        <tr>
            <th>Unidad</th><th>Placa</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Color</th>
            <th>Puestos</th><th>Propietario / Afiliado</th><th>Acciones</th>
        </tr>
        <?php foreach ($vehiculos as $v): ?>
        <tr>
            <td><?php echo htmlspecialchars($v['numero_unidad'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($v['placa']); ?></td>
            <td><?php echo htmlspecialchars($v['marca']); ?></td>
            <td><?php echo htmlspecialchars($v['modelo']); ?></td>
            <td><?php echo (int) $v['anio']; ?></td>
            <td><?php echo htmlspecialchars($v['color'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars((string) ($v['capacidad_puestos'] ?? '-')); ?></td>
            <td><?php echo htmlspecialchars($v['codigo_socio'] . ' - ' . $v['nombre_socio']); ?></td>
            <td>
                <?php if ($verInactivos): ?>
                    <a href="<?php echo BASE_URL; ?>/vehiculos/reactivar/<?php echo $v['id_vehiculo']; ?>" onclick="return confirm('Reactivar este vehiculo?')">Reactivar</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/vehiculos/editar/<?php echo $v['id_vehiculo']; ?>">Editar</a> |
                    <a href="<?php echo BASE_URL; ?>/vehiculos/desactivar/<?php echo $v['id_vehiculo']; ?>" onclick="return confirm('Desactivar este vehiculo?')">Desactivar</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</body>
</html>
