<?php
session_start();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mis Favoritos | WildPet</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="DogProducts.css">
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
      <a href="MisFavoritos.php" title="Mis Favoritos" aria-label="Mis Favoritos"><i class="fa-solid fa-heart active-icon"></i></a>
      <a href="Search.php" title="Buscar" aria-label="Buscar"><i class="fa-solid fa-magnifying-glass"></i></a>
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

<main class="page">
  <div class="container">
    <div class="topbar">
      <h1>Mis Favoritos</h1>
      <button class="btn" id="moveToCartBtn" type="button" style="width:auto; flex:none; padding:10px 18px; border:0; outline:none; box-shadow:none;">Pasar al carrito</button>
    </div>
    <hr>
    <p id="favoritesMsg" style="display:none; margin:0 0 12px; color:#6b7280;"></p>
    <p id="emptyFavorites" style="display:none;">No tienes productos en favoritos todavía.</p>
    <section class="grid" id="favoritesGrid" aria-label="Favoritos"></section>
  </div>
</main>

<?php include('footer.php'); ?>

<script src="favorites.js"></script>
<style>
#moveToCartBtn:focus,
#moveToCartBtn:focus-visible{
  outline: none;
  box-shadow: none;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
  renderFavoritesGrid("#favoritesGrid", "#emptyFavorites");

  const moveBtn = document.getElementById("moveToCartBtn");
  const msg = document.getElementById("favoritesMsg");

  function updateButtonState() {
    const hasFavorites = Object.keys(loadFavorites()).length > 0;
    moveBtn.disabled = !hasFavorites;
    moveBtn.style.opacity = hasFavorites ? "1" : ".5";
    moveBtn.style.cursor = hasFavorites ? "pointer" : "not-allowed";
  }

  updateButtonState();

  moveBtn.addEventListener("click", async function() {
    const favorites = loadFavorites();
    const ids = Object.keys(favorites);
    if (ids.length === 0) return;

    moveBtn.disabled = true;
    msg.style.display = "none";

    try {
      const body = new URLSearchParams();
      body.set("ids", ids.join(","));

      const res = await fetch("add_favorites_to_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body.toString()
      });

      const data = await res.json();
      if (res.status === 401 && data.login_url) {
        window.location.href = data.login_url;
        return;
      }
      if (!data.ok) {
        msg.textContent = data.message || "No se pudo pasar al carrito.";
        msg.style.display = "block";
        updateButtonState();
        return;
      }

      saveFavorites({});
      renderFavoritesGrid("#favoritesGrid", "#emptyFavorites");
      updateButtonState();
      window.location.href = data.cart_url || "ShoppingCart.php";
    } catch (e) {
      msg.textContent = "Error de conexión al pasar favoritos al carrito.";
      msg.style.display = "block";
      updateButtonState();
    }
  });
});
</script>
<script src="account-menu.js"></script>
</body>
</html>
