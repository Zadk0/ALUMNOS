<?php
$conexion = new mysqli("localhost","root","","control_escolar");
if($conexion->connect_error){
 die("Error de conexión");
}
?>
