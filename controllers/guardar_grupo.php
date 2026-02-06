<?php
// Conexión subiendo un nivel a la carpeta config
include '../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_carrera = $_POST['id_carrera'];
    $turno_letra = $_POST['turno_letra'];
    $grado_num = $_POST['grado_num']; 

    // 1. Obtener siglas de la carrera (ej: 'ISC')
    $query_c = mysqli_query($conexion, "SELECT siglas FROM carrera WHERE id = '$id_carrera'");
    $f_carrera = mysqli_fetch_assoc($query_c);
    $siglas_c = $f_carrera['siglas'];

    // 2. Generar el consecutivo automático (01, 02, 03...)
    $sql_conteo = "SELECT COUNT(*) as total FROM grupos 
                   WHERE id_carrera = '$id_carrera' 
                   AND grado LIKE '$grado_num%' 
                   AND turno_letra = '$turno_letra'";
    
    $res_conteo = mysqli_query($conexion, $sql_conteo);
    $fila_conteo = mysqli_fetch_assoc($res_conteo);
    
    $consecutivo = str_pad($fila_conteo['total'] + 1, 2, "0", STR_PAD_LEFT);
    
    // 3. Crear grado final (ej: 501) y siglas (ej: ISC501-V)
    $grado_final = $grado_num . $consecutivo;
    $siglas_finales = $siglas_c . $grado_final . "-" . $turno_letra;

    // 4. Validación de duplicados
    $check_duplicado = mysqli_query($conexion, "SELECT id FROM grupos WHERE siglas = '$siglas_finales'");
    
    if (mysqli_num_rows($check_duplicado) > 0) {
        echo "<script>alert('Error: El grupo $siglas_finales ya existe.'); window.history.back();</script>";
    } else {
        // 5. Inserción en la base de datos
        $sql = "INSERT INTO grupos (id_carrera, grado, turno_letra, siglas) 
                VALUES ('$id_carrera', '$grado_final', '$turno_letra', '$siglas_finales')";

        if (mysqli_query($conexion, $sql)) {
            // Redireccionamiento correcto a la carpeta views
            echo "<script>alert('Grupo creado exitosamente: $siglas_finales'); window.location='../views/registro_grupo.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }
}
?>