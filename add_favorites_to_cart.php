<?php include("conexion.php"); 
$id = $_POST["id"];
$conn->query("INSERT INTO carrito (usuario_id, producto_id) VALUES (1, $id)"); ?>
