<?php
include("conexion.php");
// Controlador de altas

// Comprobación de duplicados
$check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");

// Persistencia con encriptación segura
$pass_hash = password_hash($password, PASSWORD_BCRYPT);
