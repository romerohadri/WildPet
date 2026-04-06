<?php include('conexion.php'); ?>

if(!isset($_SESSION["usuario_id"])) { header("Location: Login.php"); exit; }
