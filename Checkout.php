<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: Login.php?redirect=ShoppingCart.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$conn->query("CREATE TABLE IF NOT EXISTS pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  total DECIMAL(10,2) NOT NULL DEFAULT 0,
  fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  envio_nombre VARCHAR(150) DEFAULT '',
  envio_direccion VARCHAR(255) DEFAULT '',
  envio_ciudad VARCHAR(100) DEFAULT '',
  envio_provincia VARCHAR(100) DEFAULT '',
  envio_codigo_postal VARCHAR(20) DEFAULT '',
  envio_pais VARCHAR(100) DEFAULT '',
  envio_telefono VARCHAR(30) DEFAULT '',
  envio_observaciones TEXT
)");
$extraPedidoCols = [
    "envio_nombre" => "VARCHAR(150) DEFAULT ''",
    "envio_direccion" => "VARCHAR(255) DEFAULT ''",
    "envio_ciudad" => "VARCHAR(100) DEFAULT ''",
    "envio_provincia" => "VARCHAR(100) DEFAULT ''",
    "envio_codigo_postal" => "VARCHAR(20) DEFAULT ''",
    "envio_pais" => "VARCHAR(100) DEFAULT ''",
    "envio_telefono" => "VARCHAR(30) DEFAULT ''",
    "envio_observaciones" => "TEXT"
];
foreach ($extraPedidoCols as $col => $definition) {
    $check = $conn->query("SHOW COLUMNS FROM pedidos LIKE '{$col}'");
    if ($check && $check->num_rows === 0) {
        $conn->query("ALTER TABLE pedidos ADD COLUMN {$col} {$definition}");
    }
}

$conn->query("CREATE TABLE IF NOT EXISTS pedido_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_pedido INT NOT NULL,
  id_producto INT NOT NULL,
  nombre_producto VARCHAR(255) NOT NULL,
  precio DECIMAL(10,2) NOT NULL DEFAULT 0,
  cantidad INT NOT NULL DEFAULT 1,
  imagen VARCHAR(255) DEFAULT NULL
)");

$stmt = $conn->prepare("SELECT c.id, c.id_producto, c.cantidad, p.nombre, p.precio, p.imagen
                        FROM carrito c
                        JOIN productos p ON p.id = c.id_producto
                        WHERE c.id_usuario = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    $message = 'Tu carrito está vacío. Añade productos antes de tramitar el pedido.';
} else {
    $items = [];
    $total = 0;
    $shipping = $_SESSION['shipping_data'] ?? [];
    $envio_nombre = trim($shipping['nombre'] ?? '');
    $envio_direccion = trim($shipping['direccion'] ?? '');
    $envio_ciudad = trim($shipping['ciudad'] ?? '');
    $envio_provincia = trim($shipping['provincia'] ?? '');
    $envio_codigo_postal = trim($shipping['codigo_postal'] ?? '');
    $envio_pais = trim($shipping['pais'] ?? '');
    $envio_telefono = trim($shipping['telefono'] ?? '');
    $envio_observaciones = trim($shipping['observaciones'] ?? '');
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
        $total += floatval($row['precio']) * intval($row['cantidad']);
    }

    $stmt_order = $conn->prepare("INSERT INTO pedidos (id_usuario, total, envio_nombre, envio_direccion, envio_ciudad, envio_provincia, envio_codigo_postal, envio_pais, envio_telefono, envio_observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_order->bind_param("idssssssss", $usuario_id, $total, $envio_nombre, $envio_direccion, $envio_ciudad, $envio_provincia, $envio_codigo_postal, $envio_pais, $envio_telefono, $envio_observaciones);
    $stmt_order->execute();
    $pedido_id = $conn->insert_id;
    $stmt_order->close();

    $stmt_item = $conn->prepare("INSERT INTO pedido_items (id_pedido, id_producto, nombre_producto, precio, cantidad, imagen) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($items as $it) {
        $id_producto = intval($it['id_producto']);
        $nombre = $it['nombre'];
        $precio = floatval($it['precio']);
        $cantidad = intval($it['cantidad']);
        $imagen = $it['imagen'];
        $stmt_item->bind_param("iisdis", $pedido_id, $id_producto, $nombre, $precio, $cantidad, $imagen);
        $stmt_item->execute();
    }
    $stmt_item->close();

    $stmt_delete = $conn->prepare("DELETE FROM carrito WHERE id_usuario = ?");
    $stmt_delete->bind_param("i", $usuario_id);
    $stmt_delete->execute();
    $stmt_delete->close();
    $message = 'Gracias por tu pedido. Tu carrito ha sido procesado con éxito.';
}
$stmt->close();
unset($_SESSION['shipping_data']);
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pedido completado | WildPet</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="DogProducts.css">
  <link rel="stylesheet" href="account-menu.css">
  <style>
    .checkout-page-wrap {
      max-width: 900px;
      margin: 40px auto 56px;
      padding: 0 24px;
    }
    .checkout-result-card {
      background: #fff;
      border: 1px solid #ededed;
      border-radius: 20px;
      padding: 40px;
      text-align: center;
    }
    .checkout-result-card h1 {
      margin: 0 0 14px;
      font-size: 44px;
      font-weight: 800;
      color: #16181d;
    }
    .checkout-result-card p {
      margin: 0 auto 22px;
      max-width: 620px;
      font-size: 20px;
      line-height: 1.6;
      color: #697386;
    }
    .checkout-result-card .btn {
      width: auto;
      display: inline-block;
      padding: 12px 20px;
      text-decoration: none;
      border: 0;
    }
  </style>
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
      <a href="ShoppingCart.php" title="Carrito"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>
  </div>
</header>

<main class="page">
  <div class="checkout-page-wrap">
    <section class="checkout-result-card">
      <h1>Pedido realizado</h1>
      <p><?php echo htmlspecialchars($message); ?></p>
      <a href="Homepage.php" class="btn">Seguir comprando</a>
    </section>
  </div>
</main>

<?php include('footer.php'); ?>
<script src="account-menu.js"></script>
</body>
</html>
<?php 
if(isset($_POST['observaciones'])) {
    $obs = $_POST['observaciones'];
    $conn->query("UPDATE pedidos SET observaciones = '$obs' WHERE id = 1");
}
?>
