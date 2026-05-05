<?php session_start(); include("conexion.php"); ?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="DogProducts.css"></head>
<body>
<h1>Mi Cuenta</h1>
<div class="account-container"></div>
</body>
</html>
<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre">
    <button type="submit" name="guardar">Guardar</button>
</form>
<input type="text" name="nif" placeholder="DNI/NIF">
<div class="billing">
    <h3>Facturación</h3>
    <input type="text" name="direccion_fac" placeholder="Dirección">
</div>
<input type="text" name="tel2_fac" placeholder="Segundo teléfono">
