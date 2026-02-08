<?php
// turnos.php - Catálogo de Turnos
require_once '../config/config.php';

$conn = getConnection();
$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'agregar') {
            $nombre = limpiarDatos($_POST['nombre']);
            $horario = limpiarDatos($_POST['horario']);
            
            if (!empty($nombre)) {
                $stmt = $conn->prepare("INSERT INTO turnos (nombre, horario, activo) VALUES (?, ?, 1)");
                $stmt->bind_param("ss", $nombre, $horario);
                
                if ($stmt->execute()) {
                    $mensaje = "Turno agregado exitosamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al agregar turno: " . $conn->error;
                    $tipo_mensaje = "error";
                }
                $stmt->close();
            } else {
                $mensaje = "El nombre del turno es obligatorio";
                $tipo_mensaje = "error";
            }
        }
        
        if ($action == 'editar') {
            $id = intval($_POST['id']);
            $nombre = limpiarDatos($_POST['nombre']);
            $horario = limpiarDatos($_POST['horario']);
            
            $stmt = $conn->prepare("UPDATE turnos SET nombre=?, horario=? WHERE id=?");
            $stmt->bind_param("ssi", $nombre, $horario, $id);
            
            if ($stmt->execute()) {
                $mensaje = "Turno actualizado exitosamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al actualizar turno";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
        
        if ($action == 'eliminar') {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE turnos SET activo=0 WHERE id=?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $mensaje = "Turno eliminado exitosamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al eliminar turno";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
    }
}

// Obtener lista de turnos activos
$turnos_query = "SELECT * FROM turnos WHERE activo = 1 ORDER BY nombre";
$turnos_result = $conn->query($turnos_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Turnos</title>
    <link rel="stylesheet" href="../assets/css/style.css"> </head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
        </header>

        <?php include 'menu.php'; ?> <div class="content">
            <h2>🕐 Catálogo de Turnos</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="action" value="agregar" id="action">
                <input type="hidden" name="id" id="turno_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre del Turno *</label>
                        <input type="text" id="nombre" name="nombre" required 
                               placeholder="Ej: MATUTINO, VESPERTINO">
                    </div>
                    
                    <div class="form-group">
                        <label for="horario">Horario</label>
                        <input type="text" id="horario" name="horario" 
                               placeholder="Ej: 7:00 AM - 1:00 PM">
                    </div>
                </div>

                <div style="text-align: right;">
                    <button type="submit" id="btnSubmit" class="btn">Agregar Turno</button>
                    <button type="button" onclick="cancelarEdicion()" class="btn btn-secondary" id="btnCancelar" style="display:none;">Cancelar</button>
                </div>
            </form>

            <h3 style="margin-top: 40px; color: #667eea;">Turnos Registrados</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Horario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($turno = $turnos_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $turno['id']; ?></td>
                            <td><strong><?php echo $turno['nombre']; ?></strong></td>
                            <td><?php echo $turno['horario'] ?: '-'; ?></td>
                            <td class="actions">
                                <button onclick='editarTurno(<?php echo json_encode($turno); ?>)' class="btn btn-edit">Editar</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este turno?');">
                                    <input type="hidden" name="action" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $turno['id']; ?>">
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
        function editarTurno(turno) {
            document.getElementById('nombre').value = turno.nombre;
            document.getElementById('horario').value = turno.horario || '';
            document.getElementById('turno_id').value = turno.id;
            document.getElementById('action').value = 'editar';
            document.getElementById('btnSubmit').textContent = 'Actualizar Turno';
            document.getElementById('btnCancelar').style.display = 'inline-block';
            
            document.querySelector('.content').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelarEdicion() {
            document.getElementById('action').value = 'agregar';
            document.querySelector('form').reset();
            document.getElementById('btnSubmit').textContent = 'Agregar Turno';
            document.getElementById('btnCancelar').style.display = 'none';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>