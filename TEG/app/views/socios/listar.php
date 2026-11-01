<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Socios - Rio Cristal</title>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Socios registrados</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/socios/nuevo">+ Nuevo Socio</a>
    <a href="<?php echo BASE_URL; ?>/socios/inactivos">Ver socios desactivados</a>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 15px;">
        <tr>
            <th>Codigo</th><th>Nombre</th><th>Cedula</th><th>Tipo</th><th>Titular</th><th>Vehiculos</th><th>Acciones</th>
        </tr>
        <?php foreach ($socios as $s): ?>
        <tr>
            <td><?php echo htmlspecialchars($s['codigo']); ?></td>
            <td><?php echo htmlspecialchars($s['nombre_completo']); ?></td>
            <td><?php echo htmlspecialchars($s['cedula'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($s['tipo']); ?></td>
            <td><?php echo htmlspecialchars($s['nombre_titular'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($s['vehiculos'] ?? '-'); ?></td>
            <td>
                <a href="<?php echo BASE_URL; ?>/socios/editar/<?php echo $s['id_socio']; ?>">Editar</a> |
                <a href="<?php echo BASE_URL; ?>/socios/eliminar/<?php echo $s['id_socio']; ?>" onclick="return confirm('Desactivar este socio?')">Desactivar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>