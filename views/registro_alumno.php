<?php include '../config/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Alumno</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="card">
        <h3>Registrar Alumno</h3>
        <form action="../controllers/guardar_alumno.php" method="POST">
            <div class="campo">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="campo">
                <label>Apellido P.</label>
                <input type="text" name="apaterno" required>
            </div>
            <div class="campo">
                <label>Apellido M.</label>
                <input type="text" name="amaterno">
            </div>
            <div class="campo">
                <label>Grupo</label>
                <select name="id_grupo">
                    <?php
                    $res = mysqli_query($conexion, "SELECT id, siglas FROM grupos");
                    while($row = mysqli_fetch_assoc($res)){
                        echo "<option value='".$row['id']."'>".$row['siglas']."</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">- Registrar Alumno -</button>
        </form>
    </div>

</body>
</html>