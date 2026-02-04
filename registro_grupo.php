<?php include 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registrar Grupo</title>
    <style>
        .card { border: 1px solid #000; padding: 20px; width: 320px; border-radius: 10px; font-family: sans-serif; }
        .campo { margin-bottom: 10px; display: flex; justify-content: space-between; }
        select, input { width: 160px; }
        .btn { width: 100%; margin-top: 10px; padding: 5px; cursor: pointer; }
        .disabled-input { background-color: #eee; border: 1px solid #999; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="card">

<div class="card">
    <h3>Registrar Grupo</h3>
    <form action="guardar_grupo.php" method="POST">
        <div class="campo">
            <label>Carrera</label>
            <select name="id_carrera">
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
            <label>Grupo</label>
            <input type="text" class="disabled-input" placeholder="Automático" disabled>
        </div>
        <button type="submit" class="btn">- Registrar Grupo -</button>
    </form>
</div>

</body>
</html>