<?php
include '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carrera_id   = $_POST['carrera_id'];
    $turno_id     = $_POST['turno_id'];
    $grado        = $_POST['grado'];
    $turno_letra  = $_POST['turno_letra']; // M o V para tus siglas
    $cuatrimestre = $_POST['cuatrimestre'];

    $sql = "INSERT INTO grupos (carrera_id, turno_id, grado, turno_letra, cuatrimestre) 
            VALUES ('$carrera_id', '$turno_id', '$grado', '$turno_letra', '$cuatrimestre')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Grupo creado exitosamente'); window.location='../views/registro_grupo.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>