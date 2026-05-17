<?php
session_start();
include("conexion.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Por favor, completa todos los campos.';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $resultado_check = $stmt_check->get_result();

        if ($resultado_check->num_rows > 0) {
            $error = 'El email ya está registrado.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $email, $hashed_password);
            if ($stmt->execute()) {
                $usuario_id = $conn->insert_id;
                $_SESSION['usuario_id'] = $usuario_id;
                $_SESSION['usuario_nombre'] = $nombre;
                header("Location: Homepage.php");
                exit;
            } else {
                $error = 'Error al crear la cuenta.';
            }
            $stmt->close();
        }
        $stmt_check->close();
    }
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro | WildPet</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="Login.css">
</head>
<body>

  <header class="top-logo">
    <a href="Homepage.php" style="text-decoration: none; color: inherit;">
      <span class="spark">✳</span>
      <span class="brand">WildPet</span>
    </a>
  </header>

  <main class="login-wrapper">
    <div class="login-card">
      <h1>Crear cuenta en WildPet</h1>
      <p class="subtitle">Introduce tus datos para registrarte y empezar a comprar.</p>

      <?php if ($error): ?>
        <div style="color: red; margin-bottom: 10px; padding: 10px; background-color: #ffebee; border-radius: 5px;">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <label>Nombre</label>
        <div class="input">
          <i class="fa-regular fa-user"></i>
          <input type="text" name="nombre" placeholder="Tu nombre" required>
        </div>

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

        <label>Repetir contraseña</label>
        <div class="input">
          <i class="fa-solid fa-lock"></i>
          <input type="password" name="confirm_password" placeholder="Repite tu contraseña" required>
        </div>

        <button class="btn primary" type="submit" style="margin-top: 22px;">Registrarse</button>

        <p class="register">
          ¿Ya tienes una cuenta? <a href="Login.php">Inicia sesión</a>
        </p>
      </form>
    </div>
  </main>

</body>
</html>
