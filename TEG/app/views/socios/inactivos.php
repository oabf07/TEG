<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Socios Desactivados - Rio Cristal</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Socios Desactivados</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/socios">Volver a Socios Activos</a>

    <?php if (empty($socios)): ?>
        <p>No hay socios desactivados.</p>
    <?php else: ?>
    <table border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 15px;">
        <tr>
            <th>Codigo</th><th>Nombre</th><th>Cedula</th><th>Tipo</th><th>Titular</th><th>Accion</th>
        </tr>
        <?php foreach ($socios as $s): ?>
        <tr>
            <td><?php echo htmlspecialchars($s['codigo']); ?></td>
            <td><?php echo htmlspecialchars($s['nombre_completo']); ?></td>
            <td><?php echo htmlspecialchars($s['cedula'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($s['tipo']); ?></td>
            <td><?php echo htmlspecialchars($s['nombre_titular'] ?? '-'); ?></td>
            <td>
                <a href="<?php echo BASE_URL; ?>/socios/reactivar/<?php echo $s['id_socio']; ?>" onclick="return confirm('Reactivar este socio?')">Reactivar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</body>
</html>