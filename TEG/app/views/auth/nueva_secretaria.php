<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Nueva Secretaria</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Crear Nueva Secretaria</h1>
    <form method="POST" action="<?php echo BASE_URL; ?>/usuarios/nueva-secretaria">
        <label>Nombre completo: <input type="text" name="nombre_completo" required></label><br><br>
        <label>Usuario: <input type="text" name="usuario" required></label><br><br>
        <label>Contrasena: <input type="password" name="password" required></label><br><br>
        <button type="submit">Crear</button>
        <a href="<?php echo BASE_URL; ?>/usuarios">Cancelar</a>
    </form>
</body>
</html>