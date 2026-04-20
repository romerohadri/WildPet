<?php include("conexion.php");
// P6.12: Guardar pedido y vaciar carrito
$conn->query("INSERT INTO pedidos (usuario_id, total) VALUES (1, 126.47)");
$conn->query("DELETE FROM carrito WHERE usuario_id = 1");
echo "Pedido confirmado"; ?>
