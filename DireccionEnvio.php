<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: Login.php?redirect=DireccionEnvio.php");
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']);
$error = "";

$stmtCart = $conn->prepare("SELECT COUNT(*) AS total FROM carrito WHERE id_usuario = ?");
$stmtCart->bind_param("i", $usuario_id);
$stmtCart->execute();
$resCart = $stmtCart->get_result();
$rowCart = $resCart ? $resCart->fetch_assoc() : ["total" => 0];
$stmtCart->close();

if (intval($rowCart["total"]) <= 0) {
    header("Location: ShoppingCart.php");
    exit;
}

$shipping = $_SESSION["shipping_data"] ?? [
    "nombre" => "",
    "direccion" => "",
    "ciudad" => "",
    "provincia" => "",
    "codigo_postal" => "",
    "pais" => "",
    "telefono" => "",
    "observaciones" => ""
];

if (!isset($_SESSION["shipping_data"])) {
    $stmtUser = $conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
    $stmtUser->bind_param("i", $usuario_id);
    $stmtUser->execute();
    $resUser = $stmtUser->get_result();
    if ($resUser && $resUser->num_rows > 0) {
        $rowUser = $resUser->fetch_assoc();
        $shipping["nombre"] = trim($rowUser["nombre"] ?? "");
    }
    $stmtUser->close();

    $stmtShip = $conn->prepare("SELECT direccion, ciudad, provincia, codigo_postal, pais, telefono FROM usuario_envio WHERE id_usuario = ?");
    $stmtShip->bind_param("i", $usuario_id);
    $stmtShip->execute();
    $resShip = $stmtShip->get_result();
    if ($resShip && $resShip->num_rows > 0) {
        $savedShip = $resShip->fetch_assoc();
        $shipping["direccion"] = trim($savedShip["direccion"] ?? "");
        $shipping["ciudad"] = trim($savedShip["ciudad"] ?? "");
        $shipping["provincia"] = trim($savedShip["provincia"] ?? "");
        $shipping["codigo_postal"] = trim($savedShip["codigo_postal"] ?? "");
        $shipping["pais"] = trim($savedShip["pais"] ?? "");
        $shipping["telefono"] = trim($savedShip["telefono"] ?? "");
    } else {
        $stmtBill = $conn->prepare("SELECT direccion, ciudad, provincia, codigo_postal, pais, telefono FROM usuario_facturacion WHERE id_usuario = ?");
        $stmtBill->bind_param("i", $usuario_id);
        $stmtBill->execute();
        $resBill = $stmtBill->get_result();
        if ($resBill && $resBill->num_rows > 0) {
            $savedBill = $resBill->fetch_assoc();
            $shipping["direccion"] = trim($savedBill["direccion"] ?? "");
            $shipping["ciudad"] = trim($savedBill["ciudad"] ?? "");
            $shipping["provincia"] = trim($savedBill["provincia"] ?? "");
            $shipping["codigo_postal"] = trim($savedBill["codigo_postal"] ?? "");
            $shipping["pais"] = trim($savedBill["pais"] ?? "");
            $shipping["telefono"] = trim($savedBill["telefono"] ?? "");
        }
        $stmtBill->close();
    }
    $stmtShip->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $shipping["nombre"] = trim($_POST["nombre"] ?? "");
    $shipping["direccion"] = trim($_POST["direccion"] ?? "");
    $shipping["ciudad"] = trim($_POST["ciudad"] ?? "");
    $shipping["provincia"] = trim($_POST["provincia"] ?? "");
    $shipping["codigo_postal"] = trim($_POST["codigo_postal"] ?? "");
    $shipping["pais"] = trim($_POST["pais"] ?? "");
    $shipping["telefono"] = trim($_POST["telefono"] ?? "");
    $shipping["observaciones"] = trim($_POST["observaciones"] ?? "");

    if (
        $shipping["nombre"] === "" ||
        $shipping["direccion"] === "" ||
        $shipping["ciudad"] === "" ||
        $shipping["provincia"] === "" ||
        $shipping["codigo_postal"] === "" ||
        $shipping["pais"] === "" ||
        $shipping["telefono"] === ""
    ) {
        $error = "Completa todos los campos obligatorios de envío.";
    } else {
        $_SESSION["shipping_data"] = $shipping;
        header("Location: Checkout.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dirección de Envío | WildPet</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="ShoppingCart.css">
  <link rel="stylesheet" href="account-menu.css">
</head>
<body>
<header class="header">
  <div class="header-inner">
    <a href="Homepage.php"><img class="logo" src="img/logo.png" alt="WildPet"></a>
    <nav class="nav">
      <a href="Homepage.php">Inicio</a>
      <a href="Perros.php">Perros</a>
      <a href="Gatos.php">Gatos</a>
      <a href="Pajaros.php">Pájaros</a>
      <a href="Peces.php">Peces</a>
    </nav>
    <div class="icons">
      <a href="MisFavoritos.php" title="Mis Favoritos"><i class="fa-regular fa-heart"></i></a>
      <a href="Search.php" title="Buscar"><i class="fa-solid fa-magnifying-glass"></i></a>
      <div class="account-menu">
        <button class="account-toggle" aria-label="Cuenta"><i class="fa-regular fa-user"></i></button>
        <div class="account-dropdown">
          <?php include("account-dropdown-links.php"); ?>
        </div>
      </div>
      <a href="ShoppingCart.php" title="Carrito"><i class="fa-solid fa-cart-shopping active-icon"></i></a>
    </div>
  </div>
</header>

<main class="page">
  <div class="container">
    <h1 class="title" style="font-size:36px; margin-bottom:20px;">Dirección de envío</h1>
    <section class="panel" style="max-width:900px; margin:0 auto;">
      <?php if ($error): ?>
        <p style="color:#b42318; margin-bottom:12px;"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form method="POST" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div style="grid-column:1 / -1;">
          <label>Nombre y apellidos</label>
          <input type="text" name="nombre" value="<?php echo htmlspecialchars($shipping['nombre']); ?>" class="qty-input" style="width:100%; height:44px;" required>
        </div>
        <div style="grid-column:1 / -1;">
          <label>Dirección</label>
          <input type="text" name="direccion" value="<?php echo htmlspecialchars($shipping['direccion']); ?>" class="qty-input" style="width:100%; height:44px;" required>
        </div>
        <div>
          <label>Ciudad</label>
          <input type="text" name="ciudad" value="<?php echo htmlspecialchars($shipping['ciudad']); ?>" class="qty-input" style="width:100%; height:44px;" required>
        </div>
        <div>
          <label>Provincia</label>
          <input type="text" name="provincia" value="<?php echo htmlspecialchars($shipping['provincia']); ?>" class="qty-input" style="width:100%; height:44px;" required>
        </div>
        <div>
          <label>Código postal</label>
          <input type="text" name="codigo_postal" value="<?php echo htmlspecialchars($shipping['codigo_postal']); ?>" class="qty-input" style="width:100%; height:44px;" required>
        </div>
        <div>
          <label>País</label>
          <input type="text" name="pais" value="<?php echo htmlspecialchars($shipping['pais']); ?>" class="qty-input" style="width:100%; height:44px;" required>
        </div>
        <div style="grid-column:1 / -1;">
          <label>Teléfono</label>
          <input type="text" name="telefono" value="<?php echo htmlspecialchars($shipping['telefono']); ?>" class="qty-input" style="width:100%; height:44px;" required>
        </div>
        <div style="grid-column:1 / -1;">
          <label>Observaciones (opcional)</label>
          <textarea name="observaciones" style="width:100%; min-height:90px; border:1px solid #ededed; border-radius:10px; padding:10px 12px; font-family:Inter,sans-serif;"><?php echo htmlspecialchars($shipping['observaciones']); ?></textarea>
        </div>
        <div style="grid-column:1 / -1; display:flex; gap:10px;">
          <a href="ShoppingCart.php" class="checkout secondary" style="max-width:220px;">Volver al carrito</a>
          <button type="submit" class="checkout" style="max-width:260px;">Confirmar dirección y pagar</button>
        </div>
      </form>
    </section>
  </div>
</main>

<?php include('footer.php'); ?>
<script src="account-menu.js"></script>
</body>
</html>
