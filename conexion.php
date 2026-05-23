<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$host = "localhost";
$usuario = "root";
$contrasena = "";
$bd = "wildpet";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $usuario, $contrasena, $bd);
    $conn->set_charset("utf8");
} catch (Exception $e) {
    error_log("WildPet DB connection error: " . $e->getMessage());
    http_response_code(500);
    die("Error interno del servidor.");
}
?>
