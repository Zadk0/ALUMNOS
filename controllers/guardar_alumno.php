<?php
include '../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nombres actualizados para la nueva BD
    $nombre           = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo           = $_POST['correo'];
    $telefono         = $_POST['telefono'];
    $genero           = $_POST['genero'];
    $grupo_id         = $_POST['grupo_id'];
    
    $sql = "INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno, correo, telefono, genero, grupo_id, estatus) 
            VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$correo', '$telefono', '$genero', '$grupo_id', 1)";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Alumno registrado correctamente'); window.location='../views/registro_alumno.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>