<?php
session_start();
include("conexion.php");

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$productos = [];

if ($query !== '') {
    $sql = "SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%{$query}%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }

    $stmt->close();
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buscar | WildPet</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="account-menu.css">
  <style>
    body { font-family: Inter, sans-serif; background: #f4f5fb; margin: 0; }
    .header { background: #fff; border-bottom: 1px solid #e6e9f4; }
    .header-inner { max-width: 1200px; margin: 0 auto; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
    .logo { width: 140px; display: block; }
    .nav { display: flex; gap: 18px; }
    .nav a { color: #4a4f68; text-decoration: none; font-weight: 600; }
    .icons { display: flex; gap: 18px; align-items: center; }
    .icons a { color: #4a4f68; text-decoration: none; font-size: 18px; }
    .page { max-width: 1200px; margin: 0 auto; padding: 30px 24px; }
    .search-card { background: #fff; border-radius: 18px; padding: 28px; box-shadow: 0 20px 50px rgba(43, 52, 83, 0.08); }
    .search-card h1 { margin: 0 0 18px; font-size: 32px; color: #14183e; }
    .search-form { display: flex; gap: 14px; flex-wrap: wrap; }
    .search-form input { flex: 1 1 220px; padding: 16px 18px; border: 1px solid #d9def3; border-radius: 14px; background: #fafbff; font-size: 15px; color: #262b42; }
    .search-form button { padding: 16px 24px; background: #7ed957; color: #fff; border: none; border-radius: 14px; font-weight: 700; cursor: pointer; }
    .results { margin-top: 28px; }
    .grid { display: grid; gap: 20px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
    .card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 18px 40px rgba(43, 52, 83, 0.08); }
    .card-img img { width: 100%; display: block; }
    .card-body { padding: 18px; }
    .card-body h3 { margin: 0 0 10px; font-size: 18px; color: #14183e; }
    .card-body p { margin: 0 0 12px; color: #6e728c; font-size: 14px; line-height: 1.6; }
    .card-body .price { margin: 0 0 16px; font-size: 18px; font-weight: 700; color: #14183e; }
    .card-body .actions { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .card-body .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; background: #7ed957; color: white; border-radius: 12px; text-decoration: none; font-weight: 700; }
    .fav-btn { width: 36px; height: 36px; border: 1px solid #d9def3; border-radius: 12px; background: #fff; color: #9ca3af; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
    .fav-btn.active { color: #e11d48; }
    .empty { padding: 32px; text-align: center; color: #6e728c; }
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
  <div class="search-card">
    <h1>Buscar productos</h1>
    <form class="search-form" method="GET" action="Search.php">
      <input type="search" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Buscar por nombre o descripción" autofocus>
      <button type="submit">Buscar</button>
    </form>

    <div class="results">
      <?php if ($query === ''): ?>
        <div class="empty">Introduce un término de búsqueda para encontrar productos.</div>
      <?php else: ?>
        <?php if (count($productos) > 0): ?>
          <div class="grid">
            <?php foreach ($productos as $producto): ?>
              <article class="card">
                <div class="card-img"><img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>"></div>
                <div class="card-body">
                  <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                  <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                  <p class="price">€<?php echo number_format($producto['precio'], 2); ?></p>
                  <div class="actions">
                    <a class="btn" href="DetailProduct.php?id=<?php echo $producto['id']; ?>">Ver producto</a>
                    <button class="fav-btn" aria-label="Favorito"
                      data-id="<?php echo intval($producto['id']); ?>"
                      data-nombre="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                      data-descripcion="<?php echo htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?>"
                      data-precio="<?php echo floatval($producto['precio']); ?>"
                      data-imagen="<?php echo htmlspecialchars($producto['imagen'], ENT_QUOTES, 'UTF-8'); ?>"
                      data-url="DetailProduct.php?id=<?php echo intval($producto['id']); ?>">
                      <i class="fa-regular fa-heart"></i>
                    </button>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="empty">No se han encontrado productos para "<?php echo htmlspecialchars($query); ?>".</div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</main>

<script src="favorites.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  bindFavoriteButtons();
});
</script>

<script src="account-menu.js"></script>
</body>
</html>
