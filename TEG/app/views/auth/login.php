<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Iniciar Sesion - Rio Cristal</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Iniciar Sesion</h1>
    <form method="POST" action="<?php echo BASE_URL; ?>/login">
        <label>Usuario: <input type="text" name="usuario" required></label><br><br>
        <label>Contrasena: <input type="password" name="password" required></label><br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>