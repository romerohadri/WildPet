<?php 
// P6.1: Lógica para añadir producto al carrito
session_start();
include("conexion.php");

if (isset($_POST["producto_id"])) {
    $producto_id = intval($_POST["producto_id"]);
    $usuario_id = $_SESSION["usuario_id"];
    
    $stmt = $conn->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, 1)");
    $stmt->bind_param("ii", $usuario_id, $producto_id);
    $stmt->execute();
    $stmt->close();
}
header("Location: ShoppingCart.php");
?>
