<?php include("conexion.php"); ?>

<form action="guardar_grupo.php" method="POST">
<h3>Registrar Grupo</h3>

<select name="carrera_id">
<?php
$c = $conexion->query("SELECT * FROM carreras");
while($row=$c->fetch_assoc()){
 echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
}
?>
</select>

<input name="turno" placeholder="Turno">
<input name="grado" placeholder="Grado">
<input name="grupo" placeholder="Grupo">

<button>Registrar Grupo</button>
</form>
