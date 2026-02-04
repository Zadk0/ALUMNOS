<?php include("conexion.php"); ?>

<form action="guardar_alumno.php" method="POST">
<h3>Registrar Alumno</h3>

<input name="nombre" placeholder="Nombre" required>
<input name="apellido_p" placeholder="Apellido P" required>
<input name="apellido_m" placeholder="Apellido M" required>

<select name="grupo_id">
<?php
$g = $conexion->query("SELECT * FROM grupos");
while($row=$g->fetch_assoc()){
 echo "<option value='{$row['id']}'>Grupo {$row['grupo']}</option>";
}
?>
</select>

<button>Registrar Alumno</button>
</form>
