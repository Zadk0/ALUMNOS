<?php
// views/index.php - Corregido para estar dentro de la carpeta views
include '../config/config.php'; // Salimos a buscar la configuración

$stats = [];
$conn = getConnection(); // Usamos la función unificada

// Estadísticas reales
$stats['alumnos'] = $conn->query("SELECT COUNT(*) as total FROM alumnos WHERE estatus = 1")->fetch_assoc()['total'];
$stats['grupos'] = $conn->query("SELECT COUNT(*) as total FROM grupos")->fetch_assoc()['total'];
$stats['carreras'] = $conn->query("SELECT COUNT(*) as total FROM carreras WHERE activo = 1")->fetch_assoc()['total'];
$stats['turnos'] = $conn->query("SELECT COUNT(*) as total FROM turnos")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar - Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container" style="display: block; text-align: center; margin-top: 20px;">
        <header class="card">
            <h1>🎓 Sistema de Gestión Escolar</h1>
            <p style="color: #667eea;">Panel unificado - Rama TELLEZ</p>
        </header>

        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $stats['alumnos']; ?></h3>
                <p>Alumnos</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['grupos']; ?></h3>
                <p>Grupos</p>
            </div>
            <div class="stat-card" style="background: #22c55e;">
                <h3><?php echo $stats['carreras']; ?></h3>
                <p>Carreras</p>
            </div>
            <div class="stat-card" style="background: #f1c40f;">
                <h3><?php echo $stats['turnos']; ?></h3>
                <p>Turnos</p>
            </div>
        </div>

        <div class="form-row">
            <div class="card" onclick="location.href='registro_alumno.php'" style="cursor:pointer;">
                <h3>Nuevo Alumno</h3>
                <button class="btn">Ir a Registro</button>
            </div>
            <div class="card" onclick="location.href='registro_grupo.php'" style="cursor:pointer;">
                <h3>Nuevo Grupo</h3>
                <button class="btn" style="background:#764ba2;">Ir a Grupos</button>
            </div>
            <div class="card" onclick="location.href='alumnos.php'" style="cursor:pointer;">
                <h3>Lista General</h3>
                <button class="btn-edit">Ver Tabla</button>
            </div>
        </div>
    </div>
</body>
</html>