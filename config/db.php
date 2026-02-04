<?php
try{
$pdo = new PDO("mysql:host=localhost;dbname=control_escolar","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
 die("Error BD");
}
