<?php
// index.php
include 'config/conexion.php'; // Usa tu archivo de conexión unificado

// Obtener estadísticas reales de la base de datos
$stats = [];

// Alumnos registrados (usando tu columna 'estatus')
$result = $conexion->query("SELECT COUNT(*) as total FROM alumnos WHERE estatus = 1");
$stats['alumnos'] = $result->fetch_assoc()['total'];

// Grupos (revisando que existan siglas)
$result = $conexion->query("SELECT COUNT(*) as total FROM grupos");
$stats['grupos'] = $result->fetch_assoc()['total'];

// Carreras
$result = $conexion->query("SELECT COUNT(*) as total FROM carreras WHERE activo = 1");
$stats['carreras'] = $result->fetch_assoc()['total'];

// Turnos
$result = $conexion->query("SELECT COUNT(*) as total FROM turnos");
$stats['turnos'] = $result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Escolar - Panel de Control</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="views/registro_alumno.php">Registrar Alumno</a>
        <a href="views/registro_grupo.php">Registrar Grupo</a>
        <a href="views/tabla_alumnos.php">Ver Alumnos (Tabla)</a>
    </nav>

    <div class="container" style="display: block; text-align: center; margin-top: 30px;">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
            <p style="color: #94a3b8;">Bienvenido al panel integrado.</p>
        </header>

        <div class="container" style="grid-template-columns: repeat(4, 1fr); margin-top: 20px;">
            <div class="card" style="border-bottom: 4px solid #3498db;">
                <h2 style="margin:0;"><?php echo $stats['alumnos']; ?></h2>
                <p style="font-size: 0.9em; color: #94a3b8;">Alumnos</p>
            </div>
            <div class="card" style="border-bottom: 4px solid #22c55e;">
                <h2 style="margin:0;"><?php echo $stats['grupos']; ?></h2>
                <p style="font-size: 0.9em; color: #94a3b8;">Grupos</p>
            </div>
            <div class="card" style="border-bottom: 4px solid #f1c40f;">
                <h2 style="margin:0;"><?php echo $stats['carreras']; ?></h2>
                <p style="font-size: 0.9em; color: #94a3b8;">Carreras</p>
            </div>
            <div class="card" style="border-bottom: 4px solid #9b59b6;">
                <h2 style="margin:0;"><?php echo $stats['turnos']; ?></h2>
                <p style="font-size: 0.9em; color: #94a3b8;">Turnos</p>
            </div>
        </div>

        <div class="container" style="margin-top: 30px;">
            <div class="card" style="cursor: pointer;" onclick="location.href='views/registro_alumno.php'">
                <img src="https://cdn-icons-png.flaticon.com/512/3449/3449652.png" width="50" style="margin-bottom:10px;">
                <h3>Nuevo Alumno</h3>
                <p style="font-size: 0.8em; color: #94a3b8;">Captura de datos personales y asignación de grupo.</p>
                <button class="btn" style="margin-top:15px;">Ir a Registro</button>
            </div>

            <div class="card" style="cursor: pointer;" onclick="location.href='views/registro_grupo.php'">
                <img src="https://cdn-icons-png.flaticon.com/512/619/619032.png" width="50" style="margin-bottom:10px;">
                <h3>Configurar Grupos</h3>
                <p style="font-size: 0.8em; color: #94a3b8;">Generación automática de siglas profesionales.</p>
                <button class="btn" style="margin-top:15px; background:#3498db;">Ir a Grupos</button>
            </div>

            <div class="card" style="cursor: pointer;" onclick="location.href='views/tabla_alumnos.php'">
                <img src="https://cdn-icons-png.flaticon.com/512/2666/2666505.png" width="50" style="margin-bottom:10px;">
                <h3>Visualizar Datos</h3>
                <p style="font-size: 0.8em; color: #94a3b8;">Listado completo con búsqueda y filtros.</p>
                <button class="btn" style="margin-top:15px; background:#e67e22;">Ver Tabla</button>
            </div>
        </div>
    </div>
</body>
</html>