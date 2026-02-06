<?php
require "../config/db.php";
$stmt=$pdo->prepare("SELECT * FROM grupos WHERE carrera_id=?");
$stmt->execute([$_GET['id']]);
while($g=$stmt->fetch()){
echo "<option value='{$g['id']}'>Grado {$g['grado']} Grupo {$g['grupo']}</option>";
}