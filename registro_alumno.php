<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registrar Alumno</title>
    <style>
        .card { border: 1px solid #000; padding: 20px; width: 300px; border-radius: 10px; font-family: sans-serif; }
        .campo { margin-bottom: 10px; display: flex; justify-content: space-between; }
        input, select { width: 150px; }
        .btn { width: 100%; margin-top: 10px; padding: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="card">

<div class="card">
    <h3>Registrar Alumno</h3>
    <form action="guardar_alumno.php" method="POST">
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