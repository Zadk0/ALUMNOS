<?php
require "../config/db.php";
if(isset($_POST['crear'])){
$sql="INSERT INTO alumnos(nombre,apellido_p,apellido_m,grupo_id)
VALUES(?,?,?,?)";
$stmt=$pdo->prepare($sql);
$stmt->execute([
$_POST['nombre'],
$_POST['apellido_p'],
$_POST['apellido_m'],
$_POST['grupo_id']
]);
header("Location:../index.php");
}