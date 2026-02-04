<?php
include("conexion.php");

$stmt=$conexion->prepare("INSERT INTO alumnos(nombre,apellido_p,apellido_m,grupo_id) VALUES(?,?,?,?)");
$stmt->bind_param("sssi",
$_POST['nombre'],
$_POST['apellido_p'],
$_POST['apellido_m'],
$_POST['grupo_id']
);

$stmt->execute();

header("Location:index.php");
