<?php
session_start();
include("conexion.php");
include("csrf.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: Login.php?redirect=MiCuenta.php");
    exit;
}

$conn->query("CREATE TABLE IF NOT EXISTS usuario_facturacion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL UNIQUE,
  direccion VARCHAR(255) DEFAULT '',
  ciudad VARCHAR(100) DEFAULT '',
  provincia VARCHAR(100) DEFAULT '',
  codigo_postal VARCHAR(20) DEFAULT '',
  pais VARCHAR(100) DEFAULT '',
  telefono VARCHAR(30) DEFAULT '',
  nif VARCHAR(30) DEFAULT ''
)");
$conn->query("CREATE TABLE IF NOT EXISTS usuario_envio (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL UNIQUE,
  direccion VARCHAR(255) DEFAULT '',
  ciudad VARCHAR(100) DEFAULT '',
  provincia VARCHAR(100) DEFAULT '',
  codigo_postal VARCHAR(20) DEFAULT '',
  pais VARCHAR(100) DEFAULT '',
  telefono VARCHAR(30) DEFAULT '',
  telefono_secundario VARCHAR(30) DEFAULT ''
)");
$columnCheck3 = $conn->query("SHOW COLUMNS FROM usuario_envio LIKE 'telefono_secundario'");
if ($columnCheck3 && $columnCheck3->num_rows === 0) {
    $conn->query("ALTER TABLE usuario_envio ADD COLUMN telefono_secundario VARCHAR(30) DEFAULT ''");
}
$columnCheck = $conn->query("SHOW COLUMNS FROM usuario_facturacion LIKE 'nif'");
if ($columnCheck && $columnCheck->num_rows === 0) {
    $conn->query("ALTER TABLE usuario_facturacion ADD COLUMN nif VARCHAR(30) DEFAULT ''");
}
$columnCheck2 = $conn->query("SHOW COLUMNS FROM usuario_facturacion LIKE 'telefono_secundario'");
if ($columnCheck2 && $columnCheck2->num_rows === 0) {
    $conn->query("ALTER TABLE usuario_facturacion ADD COLUMN telefono_secundario VARCHAR(30) DEFAULT ''");
}

$ok = "";
$error = "";
$usuario_id = intval($_SESSION['usuario_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validate($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        $error = "Solicitud inválida. Recarga la página e inténtalo de nuevo.";
    } else {
    if (isset($_POST['save_profile'])) {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $nif_personal = trim($_POST['nif_personal'] ?? '');
        if ($nombre === '' || $apellidos === '' || $email === '') {
            $error = "Nombre, apellidos y correo son obligatorios.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Correo electrónico inválido.";
        } else {
            $nombreCompleto = trim($nombre . " " . $apellidos);

            $stmtCheck = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id <> ?");
            $stmtCheck->bind_param("si", $email, $usuario_id);
            $stmtCheck->execute();
            $resCheck = $stmtCheck->get_result();
            if ($resCheck && $resCheck->num_rows > 0) {
                $error = "Ese correo ya está en uso por otra cuenta.";
            } else {
                $stmtUp = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
                $stmtUp->bind_param("ssi", $nombreCompleto, $email, $usuario_id);
                $stmtUp->execute();
                $stmtUp->close();

                $_SESSION['usuario_nombre'] = $nombreCompleto;
                $_SESSION['usuario_email'] = $email;

                $stmtNifExists = $conn->prepare("SELECT id FROM usuario_facturacion WHERE id_usuario = ?");
                $stmtNifExists->bind_param("i", $usuario_id);
                $stmtNifExists->execute();
                $resNifExists = $stmtNifExists->get_result();
                if ($resNifExists && $resNifExists->num_rows > 0) {
                    $stmtNifUp = $conn->prepare("UPDATE usuario_facturacion SET nif = ? WHERE id_usuario = ?");
                    $stmtNifUp->bind_param("si", $nif_personal, $usuario_id);
                    $stmtNifUp->execute();
                    $stmtNifUp->close();
                } else {
                    $stmtNifIn = $conn->prepare("INSERT INTO usuario_facturacion (id_usuario, nif) VALUES (?, ?)");
                    $stmtNifIn->bind_param("is", $usuario_id, $nif_personal);
                    $stmtNifIn->execute();
                    $stmtNifIn->close();
                }
                $stmtNifExists->close();

                $ok = "Datos personales actualizados.";
            }
            $stmtCheck->close();
        }
    }

    if (isset($_POST['save_billing'])) {
        $direccion = trim($_POST['direccion'] ?? '');
        $ciudad = trim($_POST['ciudad'] ?? '');
        $provincia = trim($_POST['provincia'] ?? '');
        $codigo_postal = trim($_POST['codigo_postal'] ?? '');
        $pais = trim($_POST['pais'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $telefono_secundario = trim($_POST['telefono_secundario'] ?? '');
        $nif_actual = trim($_POST['nif_actual'] ?? '');

        $stmtExists = $conn->prepare("SELECT id FROM usuario_facturacion WHERE id_usuario = ?");
        $stmtExists->bind_param("i", $usuario_id);
        $stmtExists->execute();
        $resExists = $stmtExists->get_result();

        if ($resExists && $resExists->num_rows > 0) {
            $stmtBillUp = $conn->prepare("UPDATE usuario_facturacion SET direccion = ?, ciudad = ?, provincia = ?, codigo_postal = ?, pais = ?, telefono = ?, telefono_secundario = ?, nif = ? WHERE id_usuario = ?");
            $stmtBillUp->bind_param("ssssssssi", $direccion, $ciudad, $provincia, $codigo_postal, $pais, $telefono, $telefono_secundario, $nif_actual, $usuario_id);
            $stmtBillUp->execute();
            $stmtBillUp->close();
        } else {
            $stmtBillIn = $conn->prepare("INSERT INTO usuario_facturacion (id_usuario, direccion, ciudad, provincia, codigo_postal, pais, telefono, telefono_secundario, nif) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtBillIn->bind_param("issssssss", $usuario_id, $direccion, $ciudad, $provincia, $codigo_postal, $pais, $telefono, $telefono_secundario, $nif_actual);
            $stmtBillIn->execute();
            $stmtBillIn->close();
        }
        $stmtExists->close();

        if ($error === "") {
            $ok = "Dirección de facturación actualizada.";
        }
    }

    if (isset($_POST['save_shipping'])) {
        $direccion_envio = trim($_POST['direccion_envio'] ?? '');
        $ciudad_envio = trim($_POST['ciudad_envio'] ?? '');
        $provincia_envio = trim($_POST['provincia_envio'] ?? '');
        $codigo_postal_envio = trim($_POST['codigo_postal_envio'] ?? '');
        $pais_envio = trim($_POST['pais_envio'] ?? '');
        $telefono_envio = trim($_POST['telefono_envio'] ?? '');
        $telefono_secundario_envio = trim($_POST['telefono_secundario_envio'] ?? '');

        $stmtExistsShip = $conn->prepare("SELECT id FROM usuario_envio WHERE id_usuario = ?");
        $stmtExistsShip->bind_param("i", $usuario_id);
        $stmtExistsShip->execute();
        $resExistsShip = $stmtExistsShip->get_result();

        if ($resExistsShip && $resExistsShip->num_rows > 0) {
            $stmtShipUp = $conn->prepare("UPDATE usuario_envio SET direccion = ?, ciudad = ?, provincia = ?, codigo_postal = ?, pais = ?, telefono = ?, telefono_secundario = ? WHERE id_usuario = ?");
            $stmtShipUp->bind_param("sssssssi", $direccion_envio, $ciudad_envio, $provincia_envio, $codigo_postal_envio, $pais_envio, $telefono_envio, $telefono_secundario_envio, $usuario_id);
            $stmtShipUp->execute();
            $stmtShipUp->close();
        } else {
            $stmtShipIn = $conn->prepare("INSERT INTO usuario_envio (id_usuario, direccion, ciudad, provincia, codigo_postal, pais, telefono, telefono_secundario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtShipIn->bind_param("isssssss", $usuario_id, $direccion_envio, $ciudad_envio, $provincia_envio, $codigo_postal_envio, $pais_envio, $telefono_envio, $telefono_secundario_envio);
            $stmtShipIn->execute();
            $stmtShipIn->close();
        }
        $stmtExistsShip->close();

        if ($error === "") {
            $ok = "Dirección de envío actualizada.";
        }
    }
    }
}

$usuario = null;
$stmt = $conn->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows > 0) {
    $usuario = $res->fetch_assoc();
}
$stmt->close();

$facturacion = [
    "direccion" => "",
    "ciudad" => "",
    "provincia" => "",
    "codigo_postal" => "",
    "pais" => "",
    "telefono" => "",
    "telefono_secundario" => "",
    "nif" => ""
];
$stmtBill = $conn->prepare("SELECT direccion, ciudad, provincia, codigo_postal, pais, telefono, telefono_secundario, nif FROM usuario_facturacion WHERE id_usuario = ?");
$stmtBill->bind_param("i", $usuario_id);
$stmtBill->execute();
$resBill = $stmtBill->get_result();
if ($resBill && $resBill->num_rows > 0) {
    $facturacion = $resBill->fetch_assoc();
}
$stmtBill->close();

$envio = [
    "direccion" => "",
    "ciudad" => "",
    "provincia" => "",
    "codigo_postal" => "",
    "pais" => "",
    "telefono" => "",
    "telefono_secundario" => ""
];
$stmtShip = $conn->prepare("SELECT direccion, ciudad, provincia, codigo_postal, pais, telefono, telefono_secundario FROM usuario_envio WHERE id_usuario = ?");
$stmtShip->bind_param("i", $usuario_id);
$stmtShip->execute();
$resShip = $stmtShip->get_result();
if ($resShip && $resShip->num_rows > 0) {
    $envio = $resShip->fetch_assoc();
}
$stmtShip->close();

$nombrePartes = ["", ""];
if ($usuario && !empty($usuario["nombre"])) {
    $tmp = preg_split('/\s+/', trim($usuario["nombre"]), 2);
    $nombrePartes[0] = $tmp[0] ?? "";
    $nombrePartes[1] = $tmp[1] ?? "";
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mi Cuenta | WildPet</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="DogProducts.css">
  <link rel="stylesheet" href="account-menu.css">
  <style>
    .no-outline-btn:focus,
    .no-outline-btn:focus-visible,
    .no-outline-btn:active {
      outline: none !important;
      box-shadow: none !important;
      border: 0 !important;
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
    <div class="topbar"><h1>Mi Cuenta</h1></div>
    <hr>
    <?php if ($ok): ?>
      <p style="color:#2f7d32; margin-bottom:12px;"><?php echo htmlspecialchars($ok); ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
      <p style="color:#b42318; margin-bottom:12px;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <section class="card" style="max-width:980px; padding:24px; margin-bottom:16px;">
      <h3 style="margin:0 0 12px; font-size:22px;">Datos personales</h3>
      <?php if ($usuario): ?>
        <form method="POST" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
          <div>
            <label style="display:block; margin-bottom:6px; font-weight:600;">Nombre</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombrePartes[0]); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;" required>
          </div>
          <div>
            <label style="display:block; margin-bottom:6px; font-weight:600;">Apellidos</label>
            <input type="text" name="apellidos" value="<?php echo htmlspecialchars($nombrePartes[1]); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;" required>
          </div>
          <div>
            <label style="display:block; margin-bottom:6px; font-weight:600;">Correo electrónico</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;" required>
          </div>
          <div>
            <label style="display:block; margin-bottom:6px; font-weight:600;">DNI / NIF</label>
            <input type="text" name="nif_personal" value="<?php echo htmlspecialchars($facturacion['nif']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
          </div>
          <div style="grid-column:1 / -1;">
            <button class="btn no-outline-btn" type="submit" name="save_profile" style="width:auto; padding:10px 18px; border:0; outline:none; box-shadow:none;">Guardar datos personales</button>
          </div>
        </form>
      <?php else: ?>
        <p>No se pudieron cargar los datos de la cuenta.</p>
      <?php endif; ?>
    </section>

    <section class="card" style="max-width:980px; padding:24px; margin-bottom:16px;">
      <h3 style="margin:0 0 12px; font-size:22px;">Dirección de facturación</h3>
      <form method="POST" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
        <div style="grid-column:1 / -1;">
          <label style="display:block; margin-bottom:6px; font-weight:600;">Dirección</label>
          <input type="text" name="direccion" value="<?php echo htmlspecialchars($facturacion['direccion']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Ciudad</label>
          <input type="text" name="ciudad" value="<?php echo htmlspecialchars($facturacion['ciudad']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Provincia</label>
          <input type="text" name="provincia" value="<?php echo htmlspecialchars($facturacion['provincia']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Código postal</label>
          <input type="text" name="codigo_postal" value="<?php echo htmlspecialchars($facturacion['codigo_postal']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">País</label>
          <input type="text" name="pais" value="<?php echo htmlspecialchars($facturacion['pais']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Teléfono</label>
          <input type="text" name="telefono" value="<?php echo htmlspecialchars($facturacion['telefono']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Segundo número de contacto</label>
          <input type="text" name="telefono_secundario" value="<?php echo htmlspecialchars($facturacion['telefono_secundario']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
          <input type="hidden" name="nif_actual" value="<?php echo htmlspecialchars($facturacion['nif']); ?>">
        </div>
        <div style="grid-column:1 / -1;">
          <button class="btn no-outline-btn" type="submit" name="save_billing" style="width:auto; padding:10px 18px; border:0; outline:none; box-shadow:none;">Guardar facturación</button>
        </div>
      </form>
    </section>

    <section class="card" style="max-width:980px; padding:24px;">
      <h3 style="margin:0 0 12px; font-size:22px;">Dirección de envío</h3>
      <form method="POST" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
        <div style="grid-column:1 / -1;">
          <label style="display:block; margin-bottom:6px; font-weight:600;">Dirección</label>
          <input type="text" name="direccion_envio" value="<?php echo htmlspecialchars($envio['direccion']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Ciudad</label>
          <input type="text" name="ciudad_envio" value="<?php echo htmlspecialchars($envio['ciudad']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Provincia</label>
          <input type="text" name="provincia_envio" value="<?php echo htmlspecialchars($envio['provincia']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Código postal</label>
          <input type="text" name="codigo_postal_envio" value="<?php echo htmlspecialchars($envio['codigo_postal']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">País</label>
          <input type="text" name="pais_envio" value="<?php echo htmlspecialchars($envio['pais']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Teléfono</label>
          <input type="text" name="telefono_envio" value="<?php echo htmlspecialchars($envio['telefono']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div>
          <label style="display:block; margin-bottom:6px; font-weight:600;">Segundo número de contacto</label>
          <input type="text" name="telefono_secundario_envio" value="<?php echo htmlspecialchars($envio['telefono_secundario']); ?>" style="width:100%; height:42px; border:1px solid #ededed; border-radius:8px; padding:0 12px;">
        </div>
        <div style="grid-column:1 / -1;">
          <button class="btn no-outline-btn" type="submit" name="save_shipping" style="width:auto; padding:10px 18px; border:0; outline:none; box-shadow:none;">Guardar dirección de envío</button>
        </div>
      </form>
    </section>
  </div>
</main>

<?php include('footer.php'); ?>
<script src="account-menu.js"></script>
</body>
</html>
