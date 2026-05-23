<?php
session_start();
include("conexion.php");
include("csrf.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Homepage.php");
    exit;
}
if (!csrf_validate($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    die("Solicitud inválida.");
}

$producto_id = isset($_POST['producto_id']) ? intval($_POST['producto_id']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
if ($cantidad <= 0) {
    $cantidad = 1;
}

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['pending_add'] = [
        'producto_id' => $producto_id,
        'cantidad' => $cantidad,
    ];
    header("Location: Login.php?redirect=ShoppingCart.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$stmt_check = $conn->prepare("SELECT id FROM productos WHERE id = ? AND stock > 0");
$stmt_check->bind_param("i", $producto_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if (!$result_check || $result_check->num_rows === 0) {
    header("Location: Homepage.php?error=Producto no disponible");
    exit;
}
$stmt_check->close();

$stmt_exist = $conn->prepare("SELECT id, cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
$stmt_exist->bind_param("ii", $usuario_id, $producto_id);
$stmt_exist->execute();
$result_exist = $stmt_exist->get_result();

if ($result_exist && $result_exist->num_rows > 0) {
    $item = $result_exist->fetch_assoc();
    $new_qty = $item['cantidad'] + $cantidad;
    $stmt_update = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ? AND id_usuario = ?");
    $stmt_update->bind_param("iii", $new_qty, $item['id'], $usuario_id);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    $stmt_insert = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("iii", $usuario_id, $producto_id, $cantidad);
    $stmt_insert->execute();
    $stmt_insert->close();
}

$stmt_exist->close();

header("Location: ShoppingCart.php?success=Producto agregado al carrito");
exit;
