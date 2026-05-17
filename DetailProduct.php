<?php
session_start();
include("conexion.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Producto no encontrado.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado || $resultado->num_rows === 0) {
    die("Producto no encontrado.");
}

$producto = $resultado->fetch_assoc();
$stmt->close();
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $producto['nombre']; ?> | WildPet</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <link rel="stylesheet" href="DetailProduct.css">
  <link rel="stylesheet" href="account-menu.css">
</head>

<body>

<header class="header">
  <div class="header-inner">
    <a href="Homepage.php"><img class="logo" src="img/logo.png" alt="WildPet"></a>

    <nav class="nav">
      <a href="Homepage.php">Inicio</a>
      <a class="active" href="Perros.php">Perros</a>
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

    <div class="product-layout">
      <section class="gallery">
        <div class="gallery-main">
          <button class="navbtn left" aria-label="Anterior">
            <i class="fa-solid fa-chevron-left"></i>
          </button>

          <img class="main-img" src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">

          <button class="navbtn right" aria-label="Siguiente">
            <i class="fa-solid fa-chevron-right"></i>
          </button>

          <button class="zoom" aria-label="Zoom">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>

        <div class="thumbs" aria-label="Miniaturas">
          <button class="thumb is-active">
            <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
          </button>
        </div>
      </section>

      <section class="info">
        <div class="brandline">WildPet</div>
        <h1 class="prod-title"><?php echo $producto['nombre']; ?></h1>

        <div class="rating">
          <span class="stars">★★★★★</span>
          <span class="score">(4.8)</span>
          <a class="link" href="#">40 opiniones</a>
          <span class="dot">|</span>
          <a class="link" href="#desc">Ver descripción</a>
        </div>

        <div class="size-top">
          <div class="label">Producto disponible</div>
          <a class="size-guide" href="#"><i class="fa-solid fa-box"></i> Stock: <?php echo $producto['stock']; ?></a>
        </div>

        <div class="price">€<?php echo number_format($producto['precio'], 2); ?></div>

        <form method="POST" action="add_to_cart.php" class="buyrow">
          <input type="hidden" name="producto_id" value="<?php echo intval($producto['id']); ?>">
          <select name="cantidad" class="qty" aria-label="Cantidad">
            <?php for ($i = 1; $i <= 10; $i++): ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>

          <button type="submit" class="add">Añadir al carrito</button>
        </form>

        <div class="promo">
          <div class="promo-top">
            <i class="fa-solid fa-tag"></i>
            <strong>¡No te pierdas esta oferta!</strong>
          </div>
          <p>-25% dto. 2ª ud. con cupón - en casi toda la web (alimentación, accesorios y productos de higiene)</p>

          <div class="coupon">
            <span>Cupón:</span>
            <span class="code">HADRI25</span>
            <button class="copy"><i class="fa-regular fa-copy"></i> Copiar</button>
          </div>

          <a class="link small" href="#">Ver condiciones</a>
        </div>

        <div class="ship">
          <h3>Opciones de envío</h3>

          <div class="ship-item">
            <i class="fa-solid fa-store"></i>
            <div>
              <strong>Recogida en tienda con Click &amp; Collect</strong>
              <div class="ok">
                <i class="fa-solid fa-circle-check"></i>
                Disponible
                <span>Podrás recoger tu pedido en 24-48 horas GRATIS</span>
              </div>
            </div>
          </div>

          <div class="ship-item">
            <i class="fa-solid fa-truck"></i>
            <div>
              <strong>Entrega a domicilio</strong>
              <div class="ok">
                <i class="fa-solid fa-circle-check"></i>
                Disponible
                <span>Te llegará en 1-2 días hábiles GRATIS a partir de 49€</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <section id="desc" class="desc">
      <button class="desc-head" type="button">
        <span>Descripción del producto</span>
        <i class="fa-solid fa-chevron-down"></i>
      </button>

      <div class="desc-body">
        <p><?php echo $producto['descripcion']; ?></p>

        <p><strong>Características principales:</strong></p>
        <ul>
          <li>Producto de alta calidad para mascotas.</li>
          <li>Diseñado para comodidad y durabilidad.</li>
          <li>Ideal para el uso diario.</li>
          <li>Disponible en WildPet.</li>
          <li>Stock actual: <?php echo $producto['stock']; ?> unidades.</li>
        </ul>
      </div>
    </section>

  </div>
</main>

<?php include('footer.php'); ?>

<script src="account-menu.js"></script>
</body>
</html>
