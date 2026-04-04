<?php
include("conexion.php");
// Controlador de altas

// Comprobación de duplicados
$check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
