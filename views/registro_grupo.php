<?php include '../config/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Grupo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="card">
        <h3>Registrar Grupo</h3>
        <form action="../controllers/guardar_grupo.php" method="POST">
            <div class="campo">
                <label>Carrera</label>
                <select name="id_carrera" required>
                    <?php
                    $res = mysqli_query($conexion, "SELECT id, nombre FROM carrera");
                    while($row = mysqli_fetch_assoc($res)){
                        echo "<option value='".$row['id']."'>".$row['nombre']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="campo">
                <label>Turno</label>
                <select name="turno_letra">
                    <option value="M">Matutino</option>
                    <option value="V">Vespertino</option>
                </select>
            </div>
            <div class="campo">
                <label>Grado (Semestre)</label>
                <input type="number" name="grado_num" min="1" max="11" value="1" required>
            </div>
            <div class="campo">
                <label>Siglas del Grupo</label>
                <input type="text" class="disabled-input" placeholder="Generación Automática" disabled>
            </div>
            <button type="submit" class="btn">Crear Grupo</button>
        </form>
    </div>

</body>
</html>