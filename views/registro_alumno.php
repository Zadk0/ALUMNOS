<?php include '../config/config.php'; ?>
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
                <label>Apellido Paterno</label>
                <input type="text" name="apellido_paterno" required>
            </div>
            <div class="campo">
                <label>Apellido Materno</label>
                <input type="text" name="apellido_materno">
            </div>
            <div class="campo">
                <label>Correo</label>
                <input type="email" name="correo">
            </div>
            <div class="campo">
                <label>Teléfono</label>
                <input type="text" name="telefono">
            </div>
            <div class="campo">
                <label>Género</label>
                <select name="genero">
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="campo">
                <label>Grupo</label>
                <select name="grupo_id" required>
                    <?php
                    $res = mysqli_query($conexion, "SELECT id, siglas FROM grupos");
                    while($row = mysqli_fetch_assoc($res)){
                        echo "<option value='".$row['id']."'>".$row['siglas']."</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">Registrar Alumno</button>
        </form>
    </div>
</body>
</html>