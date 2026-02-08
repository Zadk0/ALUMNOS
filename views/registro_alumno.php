<?php 
// registro_alumno.php
require_once '../config/config.php'; // Conexión unificada
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alumno - Sistema Escolar</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
        </header>

        <?php include 'menu.php'; ?> <div class="content">
            <h2>Registrar Nuevo Alumno</h2>
            
            <form action="../controllers/guardar_alumno.php" method="POST">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre(s) *</label>
                        <input type="text" name="nombre" placeholder="Ej. Juan" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Apellido Paterno *</label>
                        <input type="text" name="apellido_paterno" placeholder="Ej. Pérez" required>
                    </div>

                    <div class="form-group">
                        <label>Apellido Materno</label>
                        <input type="text" name="apellido_materno" placeholder="Ej. García">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" placeholder="juan@ejemplo.com">
                    </div>

                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" name="telefono" placeholder="5512345678">
                    </div>

                    <div class="form-group">
                        <label>Género</label>
                        <select name="genero">
                            <option value="">Seleccionar...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Grupo (Asignación por Siglas)</label>
                    <select name="grupo_id" required>
                        <option value="">Selecciona un grupo activo</option>
                        <?php
                        // Usando la variable $conexion del archivo config unificado
                        $res = mysqli_query($conexion, "SELECT id, siglas FROM grupos WHERE activo = 1 ORDER BY siglas");
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<option value='".$row['id']."'>".$row['siglas']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn">Registrar Alumno</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>