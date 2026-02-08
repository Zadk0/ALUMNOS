<?php
require_once '../config/config.php'; 
$conn = getConnection(); 

$stats = [];
// Consultas ajustadas a sistema_escolar_v2.sql
$stats['alumnos'] = $conn->query("SELECT COUNT(*) as total FROM alumnos WHERE estatus = 1")->fetch_assoc()['total'];
$stats['grupos'] = $conn->query("SELECT COUNT(*) as total FROM grupos")->fetch_assoc()['total']; 
$stats['carreras'] = $conn->query("SELECT COUNT(*) as total FROM carreras WHERE activo = 1")->fetch_assoc()['total'];
$stats['turnos'] = $conn->query("SELECT COUNT(*) as total FROM turnos WHERE activo = 1")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar - Inicio</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=1">
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
                <strong>¡Bienvenido al Sistema!</strong>
                <p>Usa el menú superior para navegar por las secciones.</p>
            </div>
        </div>
    </div>
</body>
</html>