<?php
session_start();

include("conexion.php");

$error = "";
$success = "";
$redirect = isset($_GET['redirect']) && !empty($_GET['redirect']) ? $_GET['redirect'] : 'Homepage.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $redirect = isset($_POST['redirect']) && !empty($_POST['redirect']) ? $_POST['redirect'] : $redirect;

    if (empty($email) || empty($password)) {
        $error = "Por favor completa todos los campos.";
    } else {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($password, $usuario['password'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];

                if (isset($_SESSION['pending_add']) && is_array($_SESSION['pending_add'])) {
                    $pending = $_SESSION['pending_add'];
                    unset($_SESSION['pending_add']);

                    $producto_id = intval($pending['producto_id']);
                    $cantidad = max(1, intval($pending['cantidad']));

                    $stmt_check = $conn->prepare("SELECT id FROM productos WHERE id = ? AND stock > 0");
                    $stmt_check->bind_param("i", $producto_id);
                    $stmt_check->execute();
                    $result_check = $stmt_check->get_result();

                    if ($result_check && $result_check->num_rows > 0) {
                        $stmt_exist = $conn->prepare("SELECT id, cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
                        $stmt_exist->bind_param("ii", $_SESSION['usuario_id'], $producto_id);
                        $stmt_exist->execute();
                        $result_exist = $stmt_exist->get_result();

                        if ($result_exist && $result_exist->num_rows > 0) {
                            $item = $result_exist->fetch_assoc();
                            $new_qty = $item['cantidad'] + $cantidad;
                            $stmt_update = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ? AND id_usuario = ?");
                            $stmt_update->bind_param("iii", $new_qty, $item['id'], $_SESSION['usuario_id']);
                            $stmt_update->execute();
                            $stmt_update->close();
                        } else {
                            $stmt_insert = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)");
                            $stmt_insert->bind_param("iii", $_SESSION['usuario_id'], $producto_id, $cantidad);
                            $stmt_insert->execute();
                            $stmt_insert->close();
                        }

                        $stmt_exist->close();
                    }

                    $stmt_check->close();
                }

                header("Location: " . $redirect);
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
        $stmt->close();
    }
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Login | WildPet</title>

  <!-- Fuente -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <link rel="stylesheet" href="Login.css">
</head>

<body>

  <!-- Logo -->
  <header class="top-logo">
    <a href="Homepage.php" style="text-decoration: none; color: inherit;">
      <span class="spark">✳</span>
      <span class="brand">WildPet</span>
    </a>
  </header>

  <!-- Login  -->
  <main class="login-wrapper">
    <div class="login-card">

      <h1>¡Bienvenido de<br>nuevo a WildPet!</h1>
      <p class="subtitle">Inicia sesión para continuar tu<br>aventura.</p>

      <?php if ($error): ?>
        <div style="color: red; margin-bottom: 10px; padding: 10px; background-color: #ffebee; border-radius: 5px;">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div style="color: green; margin-bottom: 10px; padding: 10px; background-color: #e8f5e9; border-radius: 5px;">
          <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
        <label>Correo electrónico</label>
        <div class="input">
          <i class="fa-regular fa-envelope"></i>
          <input type="email" name="email" placeholder="tu.email@ejemplo.com" required>
        </div>

        <label>Contraseña</label>
        <div class="input">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="password" placeholder="Tu contraseña segura" required>
        </div>

        <label class="check">
          <input type="checkbox" required>
          <span>Acepto los términos y condiciones de WildPet.</span>
        </label>

        <button class="btn primary" type="submit">Iniciar Sesión</button>
        <a href="Homepage.php" class="btn secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">Cancelar</a>

        <p class="register">
          ¿No tienes una cuenta? <a href="Register.php">Regístrate</a>
        </p>
      </form>

    </div>
  </main>

</body>
</html>

// Validación de campos obligatorios
if(empty($email) || empty($password)) { $error = "Campos obligatorios"; }

// Autenticación por correo electrónico
$sql = "SELECT * FROM usuarios WHERE email = ?";

// Inicialización de identificadores de sesión
session_start(); $_SESSION["usuario_id"] = $usuario["id"];
