<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar - Panel de Control</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php 
    include '../config/conexion.php'; 
    include 'menu.php'; 
    ?>

    <div class="container" style="display: block; text-align: center; margin-top: 50px;">
        <h1>Panel de Gestión Escolar</h1>
        <p style="color: #94a3b8;">Bienvenido. Selecciona una acción para gestionar los registros.</p>
        
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-top: 30px;">
            <div class="card" style="width: 250px; cursor: pointer;" onclick="location.href='registro_alumno.php'">
                <h3>Registrar Alumnos</h3>
                <p>Captura de nuevos ingresos.</p>
            </div>

            <div class="card" style="width: 250px; cursor: pointer;" onclick="location.href='registro_grupo.php'">
                <h3>Configurar Grupos</h3>
                <p>Creación automática de siglas.</p>
            </div>

            <div class="card" style="width: 250px; cursor: pointer; border: 1px solid #22c55e;" onclick="location.href='tabla_alumnos.php'">
                <h3 style="color: #22c55e;">Lista de Alumnos</h3>
                <p>Consulta y edición de registros.</p>
            </div>
        </div>
    </div>
</body>
</html>