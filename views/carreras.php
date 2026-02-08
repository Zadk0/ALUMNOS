<?php
// carreras.php - Catálogo de Carreras
require_once 'config/config.php';

$conn = getConnection();
$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'agregar') {
            $nombre = limpiarDatos($_POST['nombre']);
            $clave = limpiarDatos($_POST['clave']);
            $descripcion = limpiarDatos($_POST['descripcion']);
            
            if (!empty($nombre) && !empty($clave)) {
                $stmt = $conn->prepare("INSERT INTO carreras (nombre, clave, descripcion) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nombre, $clave, $descripcion);
                
                if ($stmt->execute()) {
                    $mensaje = "Carrera agregada exitosamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al agregar carrera: " . $conn->error;
                    $tipo_mensaje = "error";
                }
                $stmt->close();
            } else {
                $mensaje = "El nombre y la clave son obligatorios";
                $tipo_mensaje = "error";
            }
        }
        
        if ($action == 'editar') {
            $id = intval($_POST['id']);
            $nombre = limpiarDatos($_POST['nombre']);
            $clave = limpiarDatos($_POST['clave']);
            $descripcion = limpiarDatos($_POST['descripcion']);
            
            $stmt = $conn->prepare("UPDATE carreras SET nombre=?, clave=?, descripcion=? WHERE id=?");
            $stmt->bind_param("sssi", $nombre, $clave, $descripcion, $id);
            
            if ($stmt->execute()) {
                $mensaje = "Carrera actualizada exitosamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al actualizar carrera";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
        
        if ($action == 'eliminar') {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE carreras SET activo=0 WHERE id=?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $mensaje = "Carrera eliminada exitosamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al eliminar carrera";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
    }
}

// Obtener lista de carreras
$carreras_query = "SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre";
$carreras_result = $conn->query($carreras_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Carreras</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
        </header>

        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="alumnos.php">Alumnos</a></li>
                <li><a href="grupos.php">Grupos</a></li>
                <li><a href="carreras.php">Carreras</a></li>
                <li><a href="turnos.php">Turnos</a></li>
            </ul>
        </nav>

        <div class="content">
            <h2>📚 Catálogo de Carreras</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de registro -->
            <form method="POST" action="">
                <input type="hidden" name="action" value="agregar" id="action">
                <input type="hidden" name="id" id="carrera_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre de la Carrera *</label>
                        <input type="text" id="nombre" name="nombre" required 
                               placeholder="Ej: Ingeniería en Sistemas">
                    </div>
                    
                    <div class="form-group">
                        <label for="clave">Clave *</label>
                        <input type="text" id="clave" name="clave" required 
                               placeholder="Ej: ISC-SOT-U">
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" 
                           placeholder="Breve descripción de la carrera">
                </div>

                <button type="submit" id="btnSubmit">Agregar Carrera</button>
                <button type="button" onclick="cancelarEdicion()" class="btn btn-secondary" id="btnCancelar" style="display:none;">Cancelar</button>
            </form>

            <!-- Lista de carreras -->
            <h3 style="margin-top: 40px;">Carreras Registradas</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($carrera = $carreras_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $carrera['id']; ?></td>
                            <td><?php echo $carrera['nombre']; ?></td>
                            <td><strong><?php echo $carrera['clave']; ?></strong></td>
                            <td><?php echo $carrera['descripcion'] ?: '-'; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($carrera['fecha_registro'])); ?></td>
                            <td class="actions">
                                <button onclick='editarCarrera(<?php echo json_encode($carrera); ?>)' class="btn btn-edit">Editar</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar esta carrera?');">
                                    <input type="hidden" name="action" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $carrera['id']; ?>">
                                    <button type="submit" class="btn btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editarCarrera(carrera) {
            document.getElementById('nombre').value = carrera.nombre;
            document.getElementById('clave').value = carrera.clave;
            document.getElementById('descripcion').value = carrera.descripcion || '';
            document.getElementById('carrera_id').value = carrera.id;
            document.getElementById('action').value = 'editar';
            document.getElementById('btnSubmit').textContent = 'Actualizar Carrera';
            document.getElementById('btnCancelar').style.display = 'inline-block';
            
            document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelarEdicion() {
            document.getElementById('nombre').value = '';
            document.getElementById('clave').value = '';
            document.getElementById('descripcion').value = '';
            document.getElementById('carrera_id').value = '';
            document.getElementById('action').value = 'agregar';
            document.getElementById('btnSubmit').textContent = 'Agregar Carrera';
            document.getElementById('btnCancelar').style.display = 'none';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
