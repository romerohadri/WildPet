<?php
session_start();
include("conexion.php");
include("csrf.php");

$productos_destacados = [];
$sql = "SELECT * FROM productos WHERE destacado = 1 ORDER BY id LIMIT 2";
$resultado = $conn->query($sql);
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $productos_destacados[] = $row;
    }
}

$nombre_fijo = 'Set de Juguetes de Cuerda';
$stmt = $conn->prepare("SELECT * FROM productos WHERE nombre = ? LIMIT 1");
$stmt->bind_param("s", $nombre_fijo);
$stmt->execute();
$resultado_fijo = $stmt->get_result();
if ($resultado_fijo && $resultado_fijo->num_rows > 0) {
    $producto_fijo = $resultado_fijo->fetch_assoc();
    $productos_destacados[] = $producto_fijo;
}
$stmt->close();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Homepage | Tienda Mascotas</title>

  <!-- Fuente -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <link rel="stylesheet" href="Homepage.css">
  <link rel="stylesheet" href="account-menu.css">
</head>

<body>

<!-- HEADER -->
<header class="header">
  <div class="header-inner">
    <a href="Homepage.php"><img class="logo" src="img/logo.png" alt="Logo Tienda Mascotas"></a>

    <nav class="nav">
      <a class="active" href="Homepage.php">Inicio</a>
      <a href="Perros.php">Perros</a>
      <a href="Gatos.php">Gatos</a>
      <a href="Pajaros.php">Pájaros</a>
      <a href="Peces.php">Peces</a>
    </nav>

    <div class="icons">
      <a href="MisFavoritos.php" title="Mis Favoritos" aria-label="Mis Favoritos"><i class="fa-regular fa-heart"></i></a>
      <a href="Perros.php" title="Buscar" aria-label="Buscar"><i class="fa-solid fa-magnifying-glass"></i></a>
      <div class="account-menu">
        <button class="account-toggle" aria-label="Cuenta"><i class="fa-regular fa-user"></i></button>
        <div class="account-dropdown">
          <?php include('account-dropdown-links.php'); ?>
        </div>
      </div>
      <a href="ShoppingCart.php" title="Carrito" aria-label="Carrito"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>
  </div>
</header>

<main>

<!-- HERO -->
<section class="hero">
  <div class="container hero-grid">

    <div class="hero-text">
      <h1>
        El hogar de los<br>
        mejores<br>
        productos<br>
        para tus<br>
        mascotas
      </h1>

      <p>
        Descubre nuestra amplia selección de alimentos,
        juguetes y accesorios para cuidar a tus compañeros animales.
      </p>

      <a class="btn" href="Perros.php">Explorar Productos</a>
    </div>

    <div class="hero-img">
      <img src="img2/HomePageKira.jpg" alt="Familia con su mascota">
    </div>

  </div>
</section>

<!-- PRODUCTOS DESTACADOS -->
<section class="featured">
  <div class="container">

    <h2>Productos Destacados</h2>

    <div class="products">
      <?php if (!empty($productos_destacados)): ?>
        <?php foreach ($productos_destacados as $producto): ?>
          <article class="product">
            <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
            <p class="price">€<?php echo number_format($producto['precio'], 2); ?></p>
            <div class="actions">
              <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <input type="hidden" name="producto_id" value="<?php echo intval($producto['id']); ?>">
                <input type="hidden" name="cantidad" value="1">
                <button type="submit" class="btn small">Comprar</button>
              </form>
              <button class="fav-btn" aria-label="Favorito"
                data-id="<?php echo intval($producto['id']); ?>"
                data-nombre="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                data-descripcion=""
                data-precio="<?php echo floatval($producto['precio']); ?>"
                data-imagen="<?php echo htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>"
                data-url="DetailProduct.php?id=<?php echo intval($producto['id']); ?>">
                <i class="fa-regular fa-heart"></i>
              </button>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay productos destacados disponibles.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="home-contact">
  <div class="container">
    <div class="home-contact-card">
      <h3>Contacto</h3>
      <p><strong>Teléfono:</strong> +34 651 512 316</p>
      <p><strong>Correo:</strong> hadri@wildpet.com</p>
    </div>
  </div>
</section>

</main>

<!-- FOOTER  -->
<?php include('footer.php'); ?>

<script src="favorites.js"></script>
<script src="account-menu.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  bindFavoriteButtons();
});
</script>

</body>
</html>
