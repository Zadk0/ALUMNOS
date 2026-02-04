<?php require "config/db.php"; ?>
<div class="card">
<h3>Alumnos Registrados</h3>
<table>
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
$r=$pdo->query($sql);
foreach($r as $row){
echo "<tr>
<td>{$row['id']}</td>
<td>{$row['alumno']}</td>
<td>{$row['grupo']}</td>
<td>✏️ ❌ 👁️</td>
</tr>";
}
?>
</table>
</div>