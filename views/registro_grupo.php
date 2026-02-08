<?php 
// registro_grupo.php
require_once '../config/config.php'; // Conexión unificada
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Grupos - Sistema Escolar</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
        </header>

        <?php include 'menu.php'; ?> <div class="content">
            <h2>Crear Nuevo Grupo</h2>
            <p style="color: #667eea; text-align: center; margin-bottom: 20px; font-size: 0.9em;">
                Las siglas se generarán automáticamente al guardar.
            </p>

            <form action="../controllers/guardar_grupo.php" method="POST">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Carrera *</label>
                        <select name="carrera_id" required>
                            <option value="">Selecciona Carrera</option>
                            <?php
                            // Usamos la variable $conexion del config unificado
                            $res_c = mysqli_query($conexion, "SELECT id, nombre FROM carreras WHERE activo = 1 ORDER BY nombre");
                            while($c = mysqli_fetch_assoc($res_c)){
                                echo "<option value='".$c['id']."'>".$c['nombre']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Turno (Horario) *</label>
                        <select name="turno_id" required>
                            <option value="">Selecciona Turno</option>
                            <?php
                            $res_t = mysqli_query($conexion, "SELECT id, nombre FROM turnos WHERE activo = 1");
                            while($t = mysqli_fetch_assoc($res_t)){
                                echo "<option value='".$t['id']."'>".$t['nombre']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Identificador de Turno (Para Siglas) *</label>
                        <select name="turno_letra" required>
                            <option value="M">M (Matutino)</option>
                            <option value="V">V (Vespertino)</option>
                            <option value="X">X (Mixto)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Grado (Ejemplo: 1, 2, 3) *</label>
                        <input type="number" name="grado" min="1" max="12" placeholder="Ej. 1" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Cuatrimestre *</label>
                        <input type="number" name="cuatrimestre" min="1" max="12" placeholder="Ej. 1" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Cupo Máximo</label>
                        <input type="number" name="cupo_maximo" value="30" min="1" max="100">
                    </div>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn">Crear Grupo</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>