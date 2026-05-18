<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: Login.php?redirect=Pedidos.php");
    exit;
}

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

$pedidos = [];
$stmt = $conn->prepare("SELECT id, total, fecha, envio_nombre, envio_direccion, envio_ciudad, envio_provincia, envio_codigo_postal, envio_pais, envio_telefono, envio_observaciones FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $row['items'] = [];
    $pedidos[] = $row;
}
$stmt->close();

if (!empty($pedidos)) {
    $itemStmt = $conn->prepare("SELECT nombre_producto, precio, cantidad, imagen FROM pedido_items WHERE id_pedido = ?");
    foreach ($pedidos as $idx => $pedido) {
        $pid = intval($pedido['id']);
        $itemStmt->bind_param("i", $pid);
        $itemStmt->execute();
        $itemsRes = $itemStmt->get_result();
        while ($it = $itemsRes->fetch_assoc()) {
            $pedidos[$idx]['items'][] = $it;
        }
    }
    $itemStmt->close();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pedidos | WildPet</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="DogProducts.css">
  <link rel="stylesheet" href="account-menu.css">
  <style>
    .orders-grid {
      display: grid;
      gap: 16px;
    }
    .order-card {
      margin-bottom: 0;
      padding: 0;
      overflow: hidden;
    }
    .order-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      padding: 16px 18px;
      border-bottom: 1px solid #ededed;
      background: #fff;
      flex-wrap: wrap;
    }
    .order-meta {
      color: #697386;
      font-size: 14px;
    }
    .order-total {
      color: #16181d;
      font-weight: 700;
      font-size: 18px;
    }
    .order-body {
      padding: 10px 18px 16px;
      background: #fff;
    }
    .order-item {
      display: grid;
      grid-template-columns: 52px 1fr auto;
      align-items: center;
      gap: 12px;
      padding: 8px 0;
      border-bottom: 1px solid #f3f3f3;
    }
    .order-item:last-child {
      border-bottom: 0;
    }
    .order-item img {
      width: 52px;
      height: 52px;
      object-fit: contain;
      border: 1px solid #eee;
      border-radius: 10px;
      background: #fff;
    }
    .order-item-name {
      font-weight: 600;
      color: #16181d;
    }
    .order-item-qty {
      color: #697386;
      font-size: 14px;
      margin-top: 2px;
    }
    .order-item-price {
      font-weight: 700;
      color: #16181d;
      white-space: nowrap;
    }
    .shipping-box {
      margin-top: 12px;
      border: 1px solid #ededed;
      border-radius: 12px;
      padding: 12px;
      background: #fff;
    }
    .shipping-title {
      margin: 0 0 6px;
      font-weight: 700;
      color: #16181d;
      font-size: 15px;
    }
    .shipping-line {
      margin: 0;
      color: #697386;
      font-size: 14px;
      line-height: 1.5;
    }
    .shipping-note {
      margin-top: 8px;
      padding: 8px 10px;
      border-radius: 10px;
      background: #f7fbf3;
      border: 1px solid #dff0d2;
      color: #3b6b22;
      font-size: 14px;
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
          <?php include('account-dropdown-links.php'); ?>
        </div>
      </div>
      <a href="ShoppingCart.php" title="Carrito"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>
  </div>
</header>

<main class="page">
  <div class="container">
    <div class="topbar"><h1>Pedidos</h1></div>
    <hr>
    <?php if (empty($pedidos)): ?>
      <p>Aún no tienes pedidos realizados.</p>
    <?php else: ?>
      <div class="orders-grid">
        <?php foreach ($pedidos as $pedido): ?>
          <section class="card order-card">
            <div class="order-head">
              <div>
                <div style="font-weight:800; font-size:24px; color:#16181d;">Pedido #<?php echo intval($pedido['id']); ?></div>
                <div class="order-meta"><?php echo htmlspecialchars($pedido['fecha']); ?></div>
              </div>
              <div class="order-total">Total: €<?php echo number_format($pedido['total'], 2); ?></div>
            </div>
            <div class="order-body">
              <?php foreach ($pedido['items'] as $item): ?>
                <div class="order-item">
                  <img src="<?php echo htmlspecialchars($item['imagen']); ?>" alt="">
                  <div>
                    <div class="order-item-name"><?php echo htmlspecialchars($item['nombre_producto']); ?></div>
                    <div class="order-item-qty">Cantidad: x<?php echo intval($item['cantidad']); ?></div>
                  </div>
                  <div class="order-item-price">€<?php echo number_format($item['precio'], 2); ?></div>
                </div>
              <?php endforeach; ?>

              <?php
              $hasShipping = trim((string)($pedido['envio_direccion'] ?? '')) !== '' ||
                             trim((string)($pedido['envio_ciudad'] ?? '')) !== '' ||
                             trim((string)($pedido['envio_provincia'] ?? '')) !== '' ||
                             trim((string)($pedido['envio_codigo_postal'] ?? '')) !== '' ||
                             trim((string)($pedido['envio_pais'] ?? '')) !== '' ||
                             trim((string)($pedido['envio_telefono'] ?? '')) !== '';
              ?>
              <?php if ($hasShipping): ?>
                <div class="shipping-box">
                  <p class="shipping-title">Dirección de envío</p>
                  <?php if (!empty($pedido['envio_nombre'])): ?>
                    <p class="shipping-line"><?php echo htmlspecialchars($pedido['envio_nombre']); ?></p>
                  <?php endif; ?>
                  <p class="shipping-line">
                    <?php echo htmlspecialchars(trim(($pedido['envio_direccion'] ?? '') . ' ' . ($pedido['envio_codigo_postal'] ?? ''))); ?>
                  </p>
                  <p class="shipping-line">
                    <?php echo htmlspecialchars(trim(($pedido['envio_ciudad'] ?? '') . ' ' . ($pedido['envio_provincia'] ?? '') . ' ' . ($pedido['envio_pais'] ?? ''))); ?>
                  </p>
                  <?php if (!empty($pedido['envio_telefono'])): ?>
                    <p class="shipping-line">Tel: <?php echo htmlspecialchars($pedido['envio_telefono']); ?></p>
                  <?php endif; ?>
                  <?php if (!empty($pedido['envio_observaciones'])): ?>
                    <div class="shipping-note"><strong>Observaciones:</strong> <?php echo htmlspecialchars($pedido['envio_observaciones']); ?></div>
                  <?php endif; ?>
                </div>
              <?php elseif (!empty($pedido['envio_observaciones'])): ?>
                <div class="shipping-box">
                  <p class="shipping-title">Observaciones de envío</p>
                  <div class="shipping-note"><?php echo htmlspecialchars($pedido['envio_observaciones']); ?></div>
                </div>
              <?php endif; ?>
            </div>
          </section>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include('footer.php'); ?>
<script src="account-menu.js"></script>
</body>
</html>
