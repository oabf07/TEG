<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inicio - Rio Cristal</title>
    <style>
        .alerta {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }
        .alerta-roja { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alerta-verde { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        table { border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 8px; }
        nav a { margin-right: 15px; }
    </style>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Bienvenido(a), <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></h1>

    <nav>
        <a href="<?php echo BASE_URL; ?>/socios">Socios</a>
        
        <a href="<?php echo BASE_URL; ?>/pagos">Pagos</a>
        <a href="<?php echo BASE_URL; ?>/conceptos">Conceptos</a>
        <a href="<?php echo BASE_URL; ?>/finanzas">Finanzas</a>
        <a href="<?php echo BASE_URL; ?>/morosidad">Morosidad</a>
        <a href="<?php echo BASE_URL; ?>/solvencias">Solvencias</a>
        <?php if (Auth::rolActual() === 'administrador'): ?>
        <a href="<?php echo BASE_URL; ?>/usuarios">Usuarios</a>
        <?php endif; ?>
        <a href="<?php echo BASE_URL; ?>/logout">Cerrar sesion</a>
    </nav>

    <?php if ($totalMorosos > 0): ?>
        <div class="alerta alerta-roja">
            ALERTA: hay <?php echo $totalMorosos; ?> de <?php echo $totalSocios; ?> socios en estado MOROSO.
            Deuda total de la asociacion: $<?php echo number_format($morosidadGlobal, 2); ?>.
            <a href="<?php echo BASE_URL; ?>/morosidad">Ver reporte completo</a>
        </div>

        <h2>Socios con mayor deuda</h2>
        <table>
            <tr><th>Codigo</th><th>Nombre</th><th>Tipo</th><th>Deuda total</th></tr>
            <?php foreach ($topMorosos as $m): ?>
            <tr>
                <td><?php echo htmlspecialchars($m['codigo']); ?></td>
                <td><?php echo htmlspecialchars($m['nombre_completo']); ?></td>
                <td><?php echo htmlspecialchars($m['tipo']); ?></td>
                <td>$<?php echo number_format($m['total_deuda'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <div class="alerta alerta-verde">
            Todos los socios estan solventes. No hay morosidad pendiente.
        </div>
    <?php endif; ?>
</body>
</html>