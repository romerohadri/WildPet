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
  <link rel="stylesheet" href="DogProducts.css">
  <link rel="stylesheet" href="account-menu.css">
  <style>
    :root { --green:#86c440; --border:#ededed; }
    * { box-sizing: border-box; }
    body {
      font-family: Inter, sans-serif;
      background: #f4f5fb;
      margin: 0;
      overflow-x: hidden;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .header { background: #fff; border-bottom: 1px solid var(--border); }
    .header-inner { width: 100%; height: 60px; padding: 0 32px; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; }
    .logo { width: 26px; justify-self: start; display: block; }
    .nav { display: flex; gap: 22px; justify-self: center; }
    .nav a { text-decoration: none; color: #222; font-size: 13px; font-weight: 500; }
    .icons { display: flex; gap: 14px; align-items: center; justify-self: end; }
    .icons a { color: #222; text-decoration: none; font-size: 18px; }
    .icons i { transition: color .2s ease, transform .2s ease; }
    .icons i:hover { color: var(--green); transform: scale(1.08); }
    .page { width: min(1200px, 92%); margin: 0 auto; padding: 30px 0; flex: 1; }
    .search-card { background: #fff; border-radius: 18px; padding: 28px; box-shadow: 0 20px 50px rgba(43, 52, 83, 0.08); }
    .search-card h1 { margin: 0 0 18px; font-size: 32px; color: #14183e; }
    .search-form { display: flex; gap: 14px; flex-wrap: wrap; }
    .search-form input { flex: 1 1 220px; padding: 16px 18px; border: 1px solid #d9def3; border-radius: 14px; background: #fafbff; font-size: 15px; color: #262b42; }
    .search-form button { padding: 16px 24px; background: #86c440; color: #fff; border: none; border-radius: 14px; font-weight: 700; cursor: pointer; }
    .results { margin-top: 28px; }
    .grid { display: grid; gap: 22px; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); justify-items: center; }
    .card { width: 100%; max-width: 360px; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 18px 40px rgba(43, 52, 83, 0.08); display: flex; flex-direction: column; }
    .card-img { height: 230px; background: #fff; display: flex; align-items: center; justify-content: center; padding: 14px; }
    .card-img img { width: 100%; height: 100%; object-fit: contain; display: block; }
    .card-body { padding: 18px; display: flex; flex-direction: column; flex: 1; }
    .card-body h3 { margin: 0 0 10px; font-size: 18px; color: #14183e; }
    .card-body p { margin: 0 0 12px; color: #6e728c; font-size: 14px; line-height: 1.6; }
    .card-body .price { margin: 0 0 16px; font-size: 18px; font-weight: 700; color: #14183e; }
    .card-body .actions { margin-top: auto; display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .card-body .btn { display: inline-flex; align-items: center; justify-content: center; flex: 1; padding: 10px 16px; background: #86c440; color: white; border-radius: 12px; text-decoration: none; font-weight: 700; }
    .fav-btn { width: 36px; height: 36px; border: 1px solid #d9def3; border-radius: 12px; background: #fff; color: #9ca3af; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
    .fav-btn.active { color: #e11d48; }
    .empty { padding: 32px; text-align: center; color: #6e728c; }

    @media (max-width: 900px) {
      .header-inner {
        padding: 10px 14px;
        display: grid;
        grid-template-columns: 1fr auto;
        grid-template-areas:
          "logo icons"
          "nav nav";
        row-gap: 10px;
        align-items: center;
      }
      .logo { grid-area: logo; }
      .icons { grid-area: icons; gap: 12px; }
      .nav {
        grid-area: nav;
        justify-self: start;
        gap: 18px;
        flex-wrap: wrap;
      }
    }

    @media (max-width: 600px) {
      .header-inner { padding: 10px 10px; }
      .nav { gap: 14px; }
      .nav a { font-size: 12px; }
      .icons { gap: 10px; }
      .icons a { font-size: 16px; }
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

<?php include('footer.php'); ?>

<script src="favorites.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  bindFavoriteButtons();
});
</script>

<script src="account-menu.js"></script>
</body>
</html>
