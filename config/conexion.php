<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistema_escolar_v2"; // Nombre unificado

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>