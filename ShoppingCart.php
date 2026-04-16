<?php 
session_start();
include("conexion.php");

// P6.2: Obtener productos del carrito para el usuario logueado
$usuario_id = $_SESSION["usuario_id"];
$query = "SELECT c.id as carrito_id, p.nombre, p.precio, c.cantidad 
          FROM carrito c 
          JOIN productos p ON c.producto_id = p.id 
          WHERE c.usuario_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<body>
<h1>Tu Carrito</h1>
<?php while($row = $result->fetch_assoc()): ?>
    <div><?= $row["nombre"] ?> - <?= $row["cantidad"] ?> uds - <?= $row["precio"] ?>€</div>
<?php endwhile; ?>
</body>
</html>
<?php 
// P6.3: Lógica para actualizar cantidad
if (isset($_POST["actualizar"])) {
    $carrito_id = $_POST["carrito_id"];
    $cantidad = $_POST["cantidad"];
    $stmt = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
    $stmt->bind_param("ii", $cantidad, $carrito_id);
    $stmt->execute();
    header("Location: ShoppingCart.php");
}

if (isset($_POST["eliminar"])) {
    $carrito_id = $_POST["carrito_id"];
    $conn->query("DELETE FROM carrito WHERE id = $carrito_id");
    header("Location: ShoppingCart.php");
}
