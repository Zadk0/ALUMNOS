<?php
// views/index.php - Corregido para diseño horizontal
require_once '../config/config.php'; 
$conn = getConnection(); 

$stats = [];
// Consultas alineadas a tu base de datos sistema_escolar_v2
$stats['alumnos'] = $conn->query("SELECT COUNT(*) as total FROM alumnos WHERE estatus = 1")->fetch_assoc()['total'];
$stats['grupos'] = $conn->query("SELECT COUNT(*) as total FROM grupos")->fetch_assoc()['total']; 
$stats['carreras'] = $conn->query("SELECT COUNT(*) as total FROM carreras WHERE activo = 1")->fetch_assoc()['total'];
$stats['turnos'] = $conn->query("SELECT COUNT(*) as total FROM turnos WHERE activo = 1")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Escolar - Inicio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
        </header>

        <?php include 'menu.php'; ?>

        <div class="content">
            <h2>Panel de Control</h2>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo $stats['alumnos']; ?></h3>
                    <p>Alumnos Registrados</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['grupos']; ?></h3>
                    <p>Grupos Activos</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['carreras']; ?></h3>
                    <p>Carreras Disponibles</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['turnos']; ?></h3>
                    <p>Turnos</p>
                </div>
            </div>

            <div class="alert alert-info">
                <strong>¡Bienvenido al Sistema de Gestión Escolar!</strong>
                <p>Utiliza el menú superior para gestionar alumnos, grupos, carreras y turnos.</p>
            </div>

            <h3 style="margin-top: 20px;">Funcionalidades del Sistema:</h3>
            <ul style="margin-top: 15px; line-height: 2; list-style-position: inside;">
                <li>✅ Registro y gestión de alumnos</li>
                <li>✅ Administración de grupos (Rama TELLEZ)</li>
                <li>✅ Catálogo de carreras</li>
                <li>✅ Catálogo de turnos</li>
                <li>✅ Operaciones CRUD completas</li>
            </ul>
        </div>
    </div>
</body>
</html>