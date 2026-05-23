<?php
session_start();
include("conexion.php");
include("csrf.php");

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["ok" => false, "message" => "Método no permitido"]);
    exit;
}
if (!csrf_validate($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(["ok" => false, "message" => "Solicitud inválida"]);
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([
        "ok" => false,
        "message" => "Debes iniciar sesión",
        "login_url" => "Login.php?redirect=MisFavoritos.php"
    ]);
    exit;
}

$idsRaw = $_POST['ids'] ?? '';
$parts = array_filter(array_map('trim', explode(',', $idsRaw)));
$ids = [];
foreach ($parts as $part) {
    $id = intval($part);
    if ($id > 0) {
        $ids[$id] = $id;
    }
}
$ids = array_values($ids);

if (empty($ids)) {
    echo json_encode(["ok" => false, "message" => "No hay favoritos para pasar al carrito"]);
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']);
$added = 0;
$skipped = 0;

$stmt_check = $conn->prepare("SELECT id FROM productos WHERE id = ? AND stock > 0");
$stmt_exist = $conn->prepare("SELECT id, cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
$stmt_update = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ? AND id_usuario = ?");
$stmt_insert = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, 1)");

foreach ($ids as $producto_id) {
    $stmt_check->bind_param("i", $producto_id);
    $stmt_check->execute();
    $res = $stmt_check->get_result();
    if (!$res || $res->num_rows === 0) {
        $skipped++;
        continue;
    }

    $stmt_exist->bind_param("ii", $usuario_id, $producto_id);
    $stmt_exist->execute();
    $resExist = $stmt_exist->get_result();

    if ($resExist && $resExist->num_rows > 0) {
        $item = $resExist->fetch_assoc();
        $newQty = intval($item['cantidad']) + 1;
        $cartId = intval($item['id']);
        $stmt_update->bind_param("iii", $newQty, $cartId, $usuario_id);
        $stmt_update->execute();
    } else {
        $stmt_insert->bind_param("ii", $usuario_id, $producto_id);
        $stmt_insert->execute();
    }
    $added++;
}

$stmt_check->close();
$stmt_exist->close();
$stmt_update->close();
$stmt_insert->close();

echo json_encode([
    "ok" => true,
    "added" => $added,
    "skipped" => $skipped,
    "cart_url" => "ShoppingCart.php"
]);
exit;
