<?php
include("conexion.php");

$stmt=$conexion->prepare("INSERT INTO grupos(carrera_id,turno,grado,grupo) VALUES(?,?,?,?)");
$stmt->bind_param("isss",
$_POST['carrera_id'],
$_POST['turno'],
$_POST['grado'],
$_POST['grupo']
);

$stmt->execute();

header("Location:index.php");
