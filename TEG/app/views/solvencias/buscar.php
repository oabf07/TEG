<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Solvencias - Rio Cristal</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Emitir Solvencia</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/socios">Volver a Socios</a>
    <p>Selecciona un socio para ver su estatus:</p>
    <table border="1" cellpadding="8" style="border-collapse: collapse;">
        <tr><th>Codigo</th><th>Nombre</th><th>Tipo</th><th>Accion</th></tr>
        <?php foreach ($socios as $s): ?>
        <tr>
            <td><?php echo htmlspecialchars($s['codigo']); ?></td>
            <td><?php echo htmlspecialchars($s['nombre_completo']); ?></td>
            <td><?php echo htmlspecialchars($s['tipo']); ?></td>
            <td><a href="<?php echo BASE_URL; ?>/solvencias/socio/<?php echo $s['id_socio']; ?>">Ver estatus</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>