<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Nuevo Concepto</title><link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"></head>
<body>
    <h1>Nuevo Concepto de Pago</h1>
    <p><a href="<?php echo BASE_URL; ?>/inicio">← Volver a Inicio</a></p>
    <form method="POST" action="<?php echo BASE_URL; ?>/conceptos">
        <label>Nombre: <input type="text" name="nombre" required></label><br><br>
        <label>Tipo:
            <select name="tipo_concepto" required>
                <option value="finanza">Finanza</option>
                <option value="prestamo">Prestamo</option>
                <option value="cuota_extraordinaria">Cuota Extraordinaria</option>
                <option value="otro">Otro</option>
            </select>
        </label><br><br>
        <label>Monto por defecto: <input type="number" step="0.01" name="monto_default" required></label><br><br>
        <label>Aplica a:
            <select name="aplica_a" required>
                <option value="todos">Todos</option>
                <option value="propietario">Solo Propietarios</option>
                <option value="afiliado">Solo Afiliados</option>
                <option value="suplente">Solo Suplentes</option>
            </select>
        </label><br><br>
        <label>Se cobra por:
            <select name="cobro_por" required>
                <option value="socio">Socio (una vez por persona)</option>
                <option value="unidad">Unidad (una vez por cada vehiculo)</option>
            </select>
        </label><br><br>
        <button type="submit">Guardar</button>
        <a href="<?php echo BASE_URL; ?>/conceptos">Cancelar</a>
    </form>
</body>
</html>