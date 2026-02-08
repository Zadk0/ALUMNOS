<?php
// views/index.php - Corregido para estar dentro de la carpeta views
include '../config/config.php'; // Salimos a buscar la configuración unificada

$stats = [];
$conn = getConnection(); // Usamos la función unificada de conexión

// Estadísticas reales consultadas desde la base de datos unificada
$stats['alumnos'] = $conn->query("SELECT COUNT(*) as total FROM alumnos WHERE estatus = 1")->fetch_assoc()['total'];
$stats['grupos'] = $conn->query("SELECT COUNT(*) as total FROM grupos WHERE estatus = 1")->fetch_assoc()['total'];
$stats['carreras'] = $conn->query("SELECT COUNT(*) as total FROM carreras WHERE estatus = 1")->fetch_assoc()['total'];
$stats['turnos'] = $conn->query("SELECT COUNT(*) as total FROM turnos WHERE estatus = 1")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Escolar - Panel de Control</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
            <p style="color: #667eea; text-align: center; margin-top: 5px;">Rama de Trabajo: <strong>TELLEZ</strong></p>
        </header>

        <?php include 'menu.php'; ?> <div class="content">
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
                    <p>Turnos Configurados</p>
                </div>
            </div>

            <div class="alert alert-info">
                <strong>¡Bienvenido!</strong> Utiliza las opciones inferiores o el menú superior para gestionar el sistema.
            </div>

            <div class="form-row">
                <div class="card" onclick="location.href='registro_alumno.php'" style="cursor:pointer; text-align: center; padding: 20px;">
                    <h3 style="font-size: 1.2em;">Nuevo Alumno</h3>
                    <p style="font-size: 0.8em; color: #666; margin-bottom: 15px;">Captura de datos e ingreso</p>
                    <button class="btn">Ir a Registro</button>
                </div>
                
                <div class="card" onclick="location.href='registro_grupo.php'" style="cursor:pointer; text-align: center; padding: 20px;">
                    <h3 style="font-size: 1.2em;">Nuevo Grupo</h3>
                    <p style="font-size: 0.8em; color: #666; margin-bottom: 15px;">Generación de siglas automáticas</p>
                    <button class="btn" style="background:#764ba2;">Ir a Grupos</button>
                </div>
                
                <div class="card" onclick="location.href='alumnos.php'" style="cursor:pointer; text-align: center; padding: 20px;">
                    <h3 style="font-size: 1.2em;">Lista General</h3>
                    <p style="font-size: 0.8em; color: #666; margin-bottom: 15px;">Consulta y edición de alumnos</p>
                    <button class="btn-edit">Ver Tabla</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>