<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Usuarios del Sistema</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Usuarios del Sistema</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/socios">Volver a Socios</a> |
    <a href="<?php echo BASE_URL; ?>/usuarios/nueva-secretaria">+ Nueva Secretaria</a>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 15px;">
        <tr><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Fecha creacion</th></tr>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?php echo htmlspecialchars($u['nombre_completo']); ?></td>
            <td><?php echo htmlspecialchars($u['usuario']); ?></td>
            <td><?php echo htmlspecialchars($u['rol']); ?></td>
            <td><?php echo $u['fecha_creacion']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>