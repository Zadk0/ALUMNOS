<?php include '../config/config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configurar Grupos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="card">
        <h3>Crear Nuevo Grupo</h3>
        <p style="font-size: 0.8em; color: #94a3b8; text-align: center; margin-bottom: 20px;">
            Las siglas se generarán automáticamente al guardar.
        </p>

        <form action="../controllers/guardar_group.php" method="POST">
            <div class="campo">
                <label>Carrera</label>
                <select name="carrera_id" required>
                    <option value="">Selecciona Carrera</option>
                    <?php
                    $res_c = mysqli_query($conexion, "SELECT id, nombre FROM carreras WHERE activo = 1");
                    while($c = mysqli_fetch_assoc($res_c)){
                        echo "<option value='".$c['id']."'>".$c['nombre']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="campo">
                <label>Turno (Horario)</label>
                <select name="turno_id" required>
                    <option value="">Selecciona Turno</option>
                    <?php
                    $res_t = mysqli_query($conexion, "SELECT id, nombre FROM turnos");
                    while($t = mysqli_fetch_assoc($res_t)){
                        echo "<option value='".$t['id']."'>".$t['nombre']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="campo">
                <label>Identificador de Turno (Para Siglas)</label>
                <select name="turno_letra" required>
                    <option value="M">M (Matutino)</option>
                    <option value="V">V (Vespertino)</option>
                </select>
            </div>

            <div class="campo">
                <label>Grado (Ejemplo: 1, 2, 3)</label>
                <input type="number" name="grado" min="1" max="12" required>
            </div>

            <div class="campo">
                <label>Cuatrimestre</label>
                <input type="number" name="cuatrimestre" min="1" max="12" required>
            </div>

            <button type="submit" class="btn">Crear Grupo</button>
        </form>
    </div>
</body>
</html>