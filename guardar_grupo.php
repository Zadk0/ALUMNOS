<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_carrera = $_POST['id_carrera'];
    $turno_letra = $_POST['turno_letra'];
    $grado_num = $_POST['grado_num']; // Ejemplo: 5

    // 1. Obtener siglas de la carrera
    $query_c = mysqli_query($conexion, "SELECT siglas FROM carrera WHERE id = '$id_carrera'");
    $f_carrera = mysqli_fetch_assoc($query_c);
    $siglas_c = $f_carrera['siglas'];

    // 2. Contar cuántos grupos existen ya para esa carrera, grado y turno
    // para generar el consecutivo (01, 02, 03...)
    $sql_conteo = "SELECT COUNT(*) as total FROM grupos 
                   WHERE id_carrera = '$id_carrera' 
                   AND grado LIKE '$grado_num%' 
                   AND turno_letra = '$turno_letra'";
    
    $res_conteo = mysqli_query($conexion, $sql_conteo);
    $fila_conteo = mysqli_fetch_assoc($res_conteo);
    
    // El nuevo número será el total actual + 1, formateado a dos dígitos (01, 02...)
    $consecutivo = str_pad($fila_conteo['total'] + 1, 2, "0", STR_PAD_LEFT);
    
    // 3. Crear el grado final (Ejemplo: 5 + 01 = 501)
    $grado_final = $grado_num . $consecutivo;

    // 4. Crear la sigla final (Ejemplo: ISC501-V)
    $siglas_finales = $siglas_c . $grado_final . "-" . $turno_letra;

    // 5. Verificación final de seguridad: que no exista exactamente esa sigla
    $check_duplicado = mysqli_query($conexion, "SELECT id FROM grupos WHERE siglas = '$siglas_finales'");
    
    if (mysqli_num_rows($check_duplicado) > 0) {
        // Si por alguna razón el conteo falló o hubo un salto, buscamos el siguiente disponible
        echo "<script>alert('Error: El grupo $siglas_finales ya existe. Intenta de nuevo.'); window.history.back();</script>";
    } else {
        // 6. Insertar
        $sql = "INSERT INTO grupos (id_carrera, grado, turno_letra, siglas) 
                VALUES ('$id_carrera', '$grado_final', '$turno_letra', '$siglas_finales')";

        if (mysqli_query($conexion, $sql)) {
            echo "<script>alert('Grupo creado exitosamente: $siglas_finales'); window.location='registro_grupo.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }
}
?>