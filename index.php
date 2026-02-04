<?php include("conexion.php"); ?>
<link rel="stylesheet" href="estilos.css">

<div class="contenedor">

<?php include("alumnos.php"); ?>
<?php include("grupos.php"); ?>

<table>
<h3>Alumnos Registrados</h3>
<tr>
<th>ID</th>
<th>Alumno</th>
<th>Grupo</th>
<th>Acciones</th>
</tr>

<?php
$sql="SELECT alumnos.id,
CONCAT(nombre,' ',apellido_p,' ',apellido_m) alumno,
grupo
FROM alumnos
JOIN grupos ON alumnos.grupo_id=grupos.id";

$res=$conexion->query($sql);

while($row=$res->fetch_assoc()){
echo "<tr>
<td>{$row['id']}</td>
<td>{$row['alumno']}</td>
<td>{$row['grupo']}</td>
<td>
<a href='eliminar.php?id={$row['id']}'>❌</a>
</td>
</tr>";
}
?>

</table>

</div>
