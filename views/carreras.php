<?php
// carreras.php
require_once '../config/config.php'; // Conexión unificada

$conn = getConnection(); // Función de conexión del compañero
$mensaje = '';
$tipo_mensaje = '';

// Procesar acciones del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        // Agregar Carrera
        if ($action == 'agregar') {
            $nombre = limpiarDatos($_POST['nombre']);
            $siglas = limpiarDatos($_POST['siglas']);
            $descripcion = limpiarDatos($_POST['descripcion']);
            
            if (!empty($nombre) && !empty($siglas)) {
                $stmt = $conn->prepare("INSERT INTO carreras (nombre, siglas, descripcion, activo) VALUES (?, ?, ?, 1)");
                $stmt->bind_param("sss", $nombre, $siglas, $descripcion);
                
                if ($stmt->execute()) {
                    $mensaje = "Carrera registrada exitosamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al registrar: " . $conn->error;
                    $tipo_mensaje = "error";
                }
                $stmt->close();
            }
        }
        
        // Editar Carrera
        if ($action == 'editar') {
            $id = intval($_POST['id']);
            $nombre = limpiarDatos($_POST['nombre']);
            $siglas = limpiarDatos($_POST['siglas']);
            $descripcion = limpiarDatos($_POST['descripcion']);
            
            $stmt = $conn->prepare("UPDATE carreras SET nombre=?, siglas=?, descripcion=? WHERE id=?");
            $stmt->bind_param("sssi", $nombre, $siglas, $descripcion, $id);
            
            if ($stmt->execute()) {
                $mensaje = "Carrera actualizada correctamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al actualizar";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
        
        // Eliminar (Desactivar) Carrera
        if ($action == 'eliminar') {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE carreras SET activo=0 WHERE id=?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $mensaje = "Carrera desactivada correctamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al eliminar";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
    }
}

// Obtener lista de carreras activas
$resultado = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Carreras</title>
    <link rel="stylesheet" href="../assets/css/style.css"> </head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
        </header>

        <?php include 'menu.php'; ?> <div class="content">
            <h2>Gestión de Carreras</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="action" id="form_action" value="agregar">
                <input type="hidden" name="id" id="carrera_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre de la Carrera *</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej. Ingeniería en Sistemas" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="siglas">Siglas (Para grupos) *</label>
                        <input type="text" id="siglas" name="siglas" placeholder="Ej. ISC" required maxlength="10">
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; min-height: 80px;"></textarea>
                </div>

                <button type="submit" id="btn_submit" class="btn">Registrar Carrera</button>
                <button type="button" id="btn_cancelar" class="btn btn-secondary" style="display:none;" onclick="cancelarEdicion()">Cancelar</button>
            </form>

            <h3 style="margin-top: 40px; color: #667eea;">Carreras Disponibles</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Siglas</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($carrera = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $carrera['id']; ?></td>
                            <td><strong><?php echo $carrera['siglas']; ?></strong></td>
                            <td><?php echo $carrera['nombre']; ?></td>
                            <td><?php echo $carrera['descripcion'] ?: '-'; ?></td>
                            <td class="actions">
                                <button onclick="editarCarrera(<?php echo htmlspecialchars(json_encode($carrera)); ?>)" class="btn btn-edit">Editar</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Desea eliminar esta carrera?');">
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
            document.getElementById('form_action').value = 'editar';
            document.getElementById('carrera_id').value = carrera.id;
            document.getElementById('nombre').value = carrera.nombre;
            document.getElementById('siglas').value = carrera.siglas;
            document.getElementById('descripcion').value = carrera.descripcion || '';
            
            document.getElementById('btn_submit').textContent = 'Actualizar Carrera';
            document.getElementById('btn_cancelar').style.display = 'inline-block';
            
            document.querySelector('.content').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelarEdicion() {
            document.getElementById('form_action').value = 'agregar';
            document.querySelector('form').reset();
            document.getElementById('btn_submit').textContent = 'Registrar Carrera';
            document.getElementById('btn_cancelar').style.display = 'none';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>