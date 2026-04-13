<?php
session_start();
include("conexion.php");

$sql = "SELECT p.* 
        FROM productos p
        INNER JOIN (
          SELECT MIN(id) AS id
          FROM productos
          WHERE id_categoria = 1
            AND id NOT IN (5, 6)
          GROUP BY nombre
        ) unicos ON p.id = unicos.id";
$resultado = $conn->query($sql);
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos para Perros | WildPet</title>

  <!-- Fuente -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <link rel="stylesheet" href="DogProducts.css">
  <link rel="stylesheet" href="account-menu.css">
</head>

<body>

<!-- HEADER -->
<header class="header">
  <div class="header-inner">
    <a href="Homepage.php"><img class="logo" src="img/logo.png" alt="Logo Tienda Mascotas"></a>

    <nav class="nav">
      <a href="Homepage.php">Inicio</a>
      <a class="active" href="Perros.php">Perros</a>
      <a href="Gatos.php">Gatos</a>
      <a href="Pajaros.php">Pájaros</a>
      <a href="Peces.php">Peces</a>
    </nav>

    <div class="icons">
      <a href="MisFavoritos.php" title="Mis Favoritos" aria-label="Mis Favoritos"><i class="fa-regular fa-heart"></i></a>
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

<!-- MAIN -->
<main class="page">
  <div class="container">

    <div class="topbar">
      <h1>Productos para Perros</h1>

      <div class="filters">
        <select id="tipoFilter" aria-label="Filtrar">
          <option>Todos</option>
          <option>Comida</option>
          <option>Accesorios</option>
          <option>Higiene</option>
          <option>Juguetes</option>
        </select>

        <select id="ordenFilter" aria-label="Ordenar por">
          <option>Popularidad</option>
          <option>Precio: menor a mayor</option>
          <option>Precio: mayor a menor</option>
        </select>
      </div>
    </div>

    <hr>

    <section class="grid" aria-label="Productos">

      <?php if ($resultado && $resultado->num_rows > 0) { ?>
        <?php while($producto = $resultado->fetch_assoc()) { ?>

          <article class="card"
            data-nombre="<?php echo htmlspecialchars(strtolower($producto['nombre']), ENT_QUOTES, 'UTF-8'); ?>"
            data-descripcion="<?php echo htmlspecialchars(strtolower($producto['descripcion']), ENT_QUOTES, 'UTF-8'); ?>"
            data-precio="<?php echo floatval($producto['precio']); ?>">
            <div class="card-img">
              <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
            </div>
            <div class="card-body">
              <h3><?php echo $producto['nombre']; ?></h3>
              <p class="desc"><?php echo $producto['descripcion']; ?></p>
              <p class="price">€<?php echo number_format($producto['precio'], 2); ?></p>
              <div class="actions">
                <a class="btn" href="DetailProduct.php?id=<?php echo $producto['id']; ?>">Comprar</a>
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

        <?php } ?>
      <?php } else { ?>
        <p>No hay productos disponibles en esta categoría.</p>
      <?php } ?>

    </section>

  </div>
</main>

<!-- FOOTER -->
<?php include('footer.php'); ?>

<script src="favorites.js"></script>
<script src="account-menu.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const tipoSelect = document.getElementById("tipoFilter");
  const ordenSelect = document.getElementById("ordenFilter");
  const grid = document.querySelector(".grid");
  if (!tipoSelect || !ordenSelect || !grid) return;

  const keywordMap = {
    "Todos": [],
    "Comida": ["comida", "alimento", "pienso", "snack", "hueso"],
    "Accesorios": ["collar", "correa", "arnes", "arnés", "accesorio", "placa", "cama"],
    "Higiene": ["higiene", "cepillo", "champu", "champú", "limpieza", "toallita"],
    "Juguetes": ["juguete", "pelota", "kong", "mordedor", "cascabel"]
  };

  function normalize(value) {
    return (value || "").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  }

  function applyFiltersAndSort() {
    const selectedTipo = tipoSelect.value;
    const selectedOrden = ordenSelect.value;
    const cards = Array.from(grid.querySelectorAll(".card"));

    cards.forEach((card) => {
      const text = normalize((card.dataset.nombre || "") + " " + (card.dataset.descripcion || ""));
      const keywords = (keywordMap[selectedTipo] || []).map(normalize);
      const visible = selectedTipo === "Todos" || keywords.some((k) => text.includes(k));
      card.style.display = visible ? "" : "none";
    });

    const visibleCards = cards.filter((card) => card.style.display !== "none");
    if (selectedOrden === "Precio: menor a mayor" || selectedOrden === "Precio: mayor a menor") {
      visibleCards.sort((a, b) => {
        const pa = parseFloat(a.dataset.precio) || 0;
        const pb = parseFloat(b.dataset.precio) || 0;
        return selectedOrden === "Precio: menor a mayor" ? pa - pb : pb - pa;
      });
      visibleCards.forEach((card) => grid.appendChild(card));
    }
  }

  tipoSelect.addEventListener("change", applyFiltersAndSort);
  ordenSelect.addEventListener("change", applyFiltersAndSort);
  applyFiltersAndSort();
  bindFavoriteButtons();
});
</script>

</body>
</html>

// Lógica de marcado de pestaña de navegación activa

// Bloque de ordenación por precio
$order = "ORDER BY precio ASC";
