<?php
// Conectamos a la base de datos subiendo un nivel hacia la carpeta config
include '../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apaterno = $_POST['apaterno'];
    $amaterno = $_POST['amaterno'];
    $id_grupo = $_POST['id_grupo'];
    
    // Por defecto el alumno entra como Activo (1)
    $estatus = 1;

    $sql = "INSERT INTO alumnos (nombre, apaterno, amaterno, id_grupo, estatus) 
            VALUES ('$nombre', '$apaterno', '$amaterno', '$id_grupo', '$estatus')";

    if (mysqli_query($conexion, $sql)) {
        // Redireccionamos subiendo un nivel y entrando a la carpeta views
        echo "<script>alert('Alumno registrado correctamente'); window.location='../views/registro_alumno.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>