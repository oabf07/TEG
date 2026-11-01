<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Elite Road</title>
    <style>
        body { background-color: #0c0c0c; color: #d4af37; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #1a1a1a; padding: 40px; border: 1px solid #d4af37; width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #000; border: 1px solid #333; color: #fff; box-sizing: border-box; }
        .btn { background: #d4af37; color: #000; border: none; padding: 12px; width: 100%; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
   <div class="login-box">
    <h2 style="color:#d4af37;">ACCESO ELITE</h2>
    <form action="/goncalves/procesar-login" method="POST">
        <input type="text" name="adminis" placeholder="Usuario Administrador" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        
        <button type="submit" class="btn">ENTRAR</button>
    </form>
    <a href="/goncalves/">Volver al inicio</a>
</div>
</body>
</html>