<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Solvencia de <?php echo htmlspecialchars($socio['nombre_completo']); ?></title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1><?php echo htmlspecialchars($socio['codigo'] . ' - ' . $socio['nombre_completo']); ?></h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">Volver a Inicio</a></p>
    <a href="<?php echo BASE_URL; ?>/solvencias">Volver a buscar socio</a>

    <h2>Estatus actual</h2>
    <?php if ($saldo <= 0): ?>
        <p style="color:green;"><strong>SOLVENTE</strong> — Saldo: $<?php echo number_format($saldo, 2); ?></p>
        <form method="POST" action="<?php echo BASE_URL; ?>/solvencias/emitir/<?php echo $socio['id_socio']; ?>">
            <button type="submit">Emitir Solvencia</button>
        </form>
    <?php else: ?>
        <p style="color:red;"><strong>MOROSO</strong> — Saldo pendiente: $<?php echo number_format($saldo, 2); ?></p>
        <p>No se puede emitir solvencia mientras exista un saldo pendiente.</p>
    <?php endif; ?>

    <h2>Historial de solvencias emitidas</h2>
    <?php if (empty($historial)): ?>
        <p>Este socio no tiene solvencias emitidas todavia.</p>
    <?php else: ?>
    <table border="1" cellpadding="8" style="border-collapse: collapse;">
        <tr><th>Fecha</th><th>Emitido por</th><th>Saldo al emitir</th><th>Ver</th></tr>
        <?php foreach ($historial as $h): ?>
        <tr>
            <td><?php echo $h['fecha_emision']; ?></td>
            <td><?php echo htmlspecialchars($h['emitido_por_nombre']); ?></td>
            <td>$<?php echo number_format($h['saldo_al_emitir'], 2); ?></td>
            <td><a href="<?php echo BASE_URL; ?>/solvencias/constancia/<?php echo $h['id_solvencia']; ?>" target="_blank">Ver constancia</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</body>
</html>