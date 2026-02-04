<div class="card">
<h3>Registrar Alumno</h3>
<form action="controllers/AlumnoController.php" method="POST">
<input name="nombre" placeholder="Nombre">
<input name="apellido_p" placeholder="Apellido P">
<input name="apellido_m" placeholder="Apellido M">
<select onchange="cargarGrupos(this.value)">
<option>Selecciona Carrera</option>
<?php
require "config/db.php";
$c=$pdo->query("SELECT * FROM carreras");
foreach($c as $row){
echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
}
?>
</select>
<select name="grupo_id" id="grupo_select"></select>
<button name="crear">Guardar Alumno</button>
</form>
</div>