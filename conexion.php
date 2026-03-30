<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$bd = "wildpet";

$conn = new mysqli($host, $usuario, $contrasena, $bd);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>