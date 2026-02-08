<?php
// config/config.php
// Configuración global de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_escolar_v2'); // Asegúrate de que este sea el nombre final en tu phpMyAdmin

// 1. MÉTODO COMPAÑERO: MySQLi Orientado a Objetos (Usado por sus vistas)
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    return $conn;
}

// 2. TU MÉTODO: MySQLi Procedimental (Para tus controladores anteriores)
$conexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conexion) {
    die("Error de conexión procedimental: " . mysqli_connect_error());
}

// 3. MÉTODO PDO: (Para la tabla de alumnos y procesos AJAX)
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error en conexión PDO: " . $e->getMessage());
}

// Funciones de utilidad de tu compañero
function limpiarDatos($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>