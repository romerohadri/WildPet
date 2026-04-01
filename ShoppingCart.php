<?php
session_start();
include("conexion.php");

$checkout_error = '';
$card_name = '';
$card_number = '';
$expiry = '';
$ccv = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['carrito_id'], $_POST['cantidad']) && !isset($_POST['remove_item']) && !isset($_POST['checkout'])) {
        $carrito_id = intval($_POST['carrito_id']);
        $cantidad = max(1, intval($_POST['cantidad']));
        $stmt_update = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ? AND id_usuario = ?");
        $stmt_update->bind_param("iii", $cantidad, $carrito_id, $_SESSION['usuario_id']);
        $stmt_update->execute();
        $stmt_update->close();
        header("Location: ShoppingCart.php");
        exit;
    }

    if (isset($_POST['remove_item'], $_POST['carrito_id'])) {
        $carrito_id = intval($_POST['carrito_id']);
        $stmt_delete = $conn->prepare("DELETE FROM carrito WHERE id = ? AND id_usuario = ?");
        $stmt_delete->bind_param("ii", $carrito_id, $_SESSION['usuario_id']);
        $stmt_delete->execute();
        $stmt_delete->close();
        header("Location: ShoppingCart.php");
        exit;
    }

    if (isset($_POST['checkout'])) {
        $card_name = trim($_POST['card_name'] ?? '');
        $card_number = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
        $expiry = trim($_POST['expiry'] ?? '');
        $ccv = trim($_POST['ccv'] ?? '');

        if ($card_name === '' || $card_number === '' || $expiry === '' || $ccv === '') {
            $checkout_error = 'Por favor completa todos los datos de la tarjeta.';
        } elseif (strlen($card_number) < 13 || strlen($card_number) > 19) {
            $checkout_error = 'Número de tarjeta inválido.';
        } elseif (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $expiry)) {
            $checkout_error = 'Fecha de caducidad inválida. Usa MM/AA.';
        } elseif (!preg_match('/^\d{3,4}$/', $ccv)) {
            $checkout_error = 'CCV inválido.';
        }

        if ($checkout_error === '') {
            header("Location: DireccionEnvio.php");
            exit;
        }
    }
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, mostrar carrito vacío o redirigir a login
    $usuario_id = null;
} else {
    $usuario_id = $_SESSION['usuario_id'];
}

// Obtener productos del carrito
$carrito = [];
$subtotal = 0;

if ($usuario_id) {
    $sql = "SELECT c.id, c.cantidad, p.id as producto_id, p.nombre, p.precio, p.imagen 
            FROM carrito c
            JOIN productos p ON c.id_producto = p.id
            WHERE c.id_usuario = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    while ($row = $resultado->fetch_assoc()) {
        $carrito[] = $row;
        $subtotal += $row['precio'] * $row['cantidad'];
    }
    $stmt->close();
}

$envio = $subtotal > 49 ? 0 : 5.99;
$total = $subtotal + $envio;
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Carrito | WildPet</title>

  <!-- Fuente -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Iconos -->
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
      <a href="MisFavoritos.php" title="Mis Favoritos" aria-label="Mis Favoritos"><i class="fa-regular fa-heart"></i></a>
      <a href="Search.php" title="Buscar" aria-label="Buscar"><i class="fa-solid fa-magnifying-glass"></i></a>
      <div class="account-menu">
        <button class="account-toggle" aria-label="Cuenta"><i class="fa-regular fa-user"></i></button>
        <div class="account-dropdown">
          <?php include('account-dropdown-links.php'); ?>
        </div>
      </div>
      <a href="ShoppingCart.php" title="Carrito" aria-label="Carrito"><i class="fa-solid fa-cart-shopping active-icon"></i></a>
    </div>
  </div>
</header>

<main class="page">
  <div class="container">

    <h1 class="title">Tu Cesta de la Compra</h1>

    <div class="layout">

      <!-- CESTA -->
      <section class="panel">
        <h2 class="panel-title">Tu Cesta</h2>

        <?php if (!$usuario_id): ?>
          <p style="text-align: center; padding: 20px;">
            Debes <a href="Login.php">iniciar sesión</a> para ver tu carrito.
          </p>
        <?php elseif (empty($carrito)): ?>
          <p style="text-align: center; padding: 20px;">
            Tu carrito está vacío. <a class="continue-link" href="Perros.php">Continúa comprando</a>
          </p>
        <?php else: ?>
          <?php foreach ($carrito as $item): ?>
            <article class="item">
              <img class="item__img" src="<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
              <div class="item__info">
                <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                <p class="unit">€<?php echo number_format($item['precio'], 2); ?></p>
                <p class="sub">Subtotal: €<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></p>
              </div>
              <div class="item__qty">
                <form method="POST" class="qty-form">
                  <input type="hidden" name="carrito_id" value="<?php echo intval($item['id']); ?>">
                  <input type="number" name="cantidad" value="<?php echo intval($item['cantidad']); ?>" min="1" class="qty-input" onchange="this.form.submit()">
                </form>
                <form method="POST" class="remove-form">
                  <input type="hidden" name="carrito_id" value="<?php echo intval($item['id']); ?>">
                  <button type="submit" name="remove_item" class="trash" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
                </form>
              </div>
            </article>
            <hr class="sep">
          <?php endforeach; ?>
        <?php endif; ?>

      </section>

      <!-- RESUMEN -->
      <aside class="panel summary">
        <h2 class="panel-title">Resumen del<br>Pedido</h2>

        <div class="rows">
          <div class="row"><span>Subtotal</span><strong>€<?php echo number_format($subtotal, 2); ?></strong></div>
          <div class="row"><span>Envío Estimado</span><strong><?php echo $envio == 0 ? 'GRATIS' : '€' . number_format($envio, 2); ?></strong></div>
        </div>

        <hr class="sep">

        <div class="row total"><span>Total</span><strong>€<?php echo number_format($total, 2); ?></strong></div>

        <h3 class="pay-title">Métodos de Pago</h3>

        <div class="pay-methods">
          <label class="pay-method">
            <input type="radio" name="payment_method" value="visa" required>
            <span class="paybox"><img src="img/visa.png" alt="Visa"></span>
            <span class="pay-label">Visa</span>
          </label>

          <label class="pay-method">
            <input type="radio" name="payment_method" value="mastercard" required>
            <span class="paybox"><img src="img/MasterCard.png" alt="MasterCard"></span>
            <span class="pay-label">MasterCard</span>
          </label>

          <a href="https://www.paypal.com" target="_blank" rel="noopener" class="pay-method paybox-paypal">
            <span class="paybox"><img src="img/PayPal.png" alt="PayPal"></span>
            <span class="pay-label">PayPal</span>
          </a>
        </div>

        <?php if ($usuario_id && !empty($carrito)): ?>
          <?php if ($checkout_error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($checkout_error); ?></div>
          <?php endif; ?>

          <form method="POST" class="checkout-form">
            <input type="hidden" name="checkout" value="1">

            <div class="payment-hint">Introduce tus datos de tarjeta para completar el pago.</div>

            <div class="card-form">
              <label>Nombre en la tarjeta</label>
              <input type="text" name="card_name" value="<?php echo htmlspecialchars($card_name); ?>" placeholder="Nombre completo" autocomplete="cc-name" required>

              <label>Número de tarjeta</label>
              <input type="text" name="card_number" value="<?php echo htmlspecialchars($card_number); ?>" placeholder="1234 5678 9012 3456" inputmode="numeric" pattern="\d{13,19}" autocomplete="cc-number" required>

              <div class="card-row">
                <div>
                  <label>Caducidad (MM/AA)</label>
                  <input type="text" name="expiry" value="<?php echo htmlspecialchars($expiry); ?>" placeholder="MM/AA" maxlength="5" pattern="(0[1-9]|1[0-2])\/\d{2}" autocomplete="cc-exp" required>
                </div>
                <div>
                  <label>CCV</label>
                  <input type="text" name="ccv" value="<?php echo htmlspecialchars($ccv); ?>" placeholder="123" maxlength="4" pattern="\d{3,4}" autocomplete="cc-csc" required>
                </div>
              </div>
            </div>

            <button type="submit" class="checkout" id="checkoutButton" disabled>Tramitar Pedido</button>
          </form>
        <?php elseif (!$usuario_id): ?>
          <div class="checkout-note">Inicia sesión para tramitar tu pedido.</div>
          <a href="Login.php?redirect=ShoppingCart.php" class="checkout secondary">Iniciar sesión</a>
        <?php else: ?>
          <div class="checkout-note">Añade productos a tu carrito para continuar.</div>
          <a href="Perros.php" class="checkout secondary">Seguir comprando</a>
        <?php endif; ?>
      </aside>

    </div>
  </div>
</main>

<?php include('footer.php'); ?>

  <script>
    const paymentRadios = Array.from(document.querySelectorAll('input[name="payment_method"]'));
    const cardForm = document.querySelector('.card-form');
    const checkoutButton = document.getElementById('checkoutButton');
    const paymentHint = document.querySelector('.payment-hint');
    let lastChecked = null;

    function updatePaymentState() {
      const selected = paymentRadios.find(r => r.checked);
      if (selected) {
        cardForm.classList.add('visible');
        paymentHint.classList.add('visible');
        checkoutButton.disabled = false;
      } else {
        cardForm.classList.remove('visible');
        paymentHint.classList.remove('visible');
        checkoutButton.disabled = true;
      }
    }

    paymentRadios.forEach(radio => {
      radio.addEventListener('click', (event) => {
        if (radio === lastChecked) {
          radio.checked = false;
          lastChecked = null;
          updatePaymentState();
          event.preventDefault();
        } else {
          lastChecked = radio;
        }
      });
      radio.addEventListener('change', updatePaymentState);
    });

    updatePaymentState();
  </script>
<script src="account-menu.js"></script>
</body>
</html>
