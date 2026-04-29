<?php 
include("conexion.php"); 
session_start();
echo "<h1>Mis Pedidos</h1>";
$usuario_id = $_SESSION["usuario_id"];
$res = $conn->query("SELECT * FROM pedidos WHERE usuario_id = $usuario_id"); 
?>
<?php while($row = $res->fetch_assoc()) { 
    echo "<div>Pedido #".$row["id"]." - Total: ".$row["total"]."€</div>"; 
} ?>
<?php 
// P7.8: Mostrar observaciones
echo "<p>Observaciones: ".$row["observaciones"]."</p>"; 
?>
