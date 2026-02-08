<?php
// grupos.php - Gestión de Grupos
require_once 'includes/config.php';

$conn = getConnection();
$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'agregar') {
            $nombre = limpiarDatos($_POST['nombre']);
            $carrera_id = !empty($_POST['carrera_id']) ? intval($_POST['carrera_id']) : NULL;
            $turno_id = !empty($_POST['turno_id']) ? intval($_POST['turno_id']) : NULL;
            $cuatrimestre = !empty($_POST['cuatrimestre']) ? intval($_POST['cuatrimestre']) : NULL;
            $cupo_maximo = intval($_POST['cupo_maximo']);
            
            if (!empty($nombre)) {
                $stmt = $conn->prepare("INSERT INTO grupos (nombre, carrera_id, turno_id, cuatrimestre, cupo_maximo) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("siiii", $nombre, $carrera_id, $turno_id, $cuatrimestre, $cupo_maximo);
                
                if ($stmt->execute()) {
                    $mensaje = "Grupo agregado exitosamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al agregar grupo: " . $conn->error;
                    $tipo_mensaje = "error";
                }
                $stmt->close();
            } else {
                $mensaje = "El nombre del grupo es obligatorio";
                $tipo_mensaje = "error";
            }
        }
        
        if ($action == 'editar') {
            $id = intval($_POST['id']);
            $nombre = limpiarDatos($_POST['nombre']);
            $carrera_id = !empty($_POST['carrera_id']) ? intval($_POST['carrera_id']) : NULL;
            $turno_id = !empty($_POST['turno_id']) ? intval($_POST['turno_id']) : NULL;
            $cuatrimestre = !empty($_POST['cuatrimestre']) ? intval($_POST['cuatrimestre']) : NULL;
            $cupo_maximo = intval($_POST['cupo_maximo']);
            
            $stmt = $conn->prepare("UPDATE grupos SET nombre=?, carrera_id=?, turno_id=?, cuatrimestre=?, cupo_maximo=? WHERE id=?");
            $stmt->bind_param("siiiii", $nombre, $carrera_id, $turno_id, $cuatrimestre, $cupo_maximo, $id);
            
            if ($stmt->execute()) {
                $mensaje = "Grupo actualizado exitosamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al actualizar grupo";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
        
        if ($action == 'eliminar') {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE grupos SET activo=0 WHERE id=?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $mensaje = "Grupo eliminado exitosamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al eliminar grupo";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
    }
}

// Obtener lista de grupos con información relacionada
$grupos_query = "SELECT g.*, 
                        c.nombre as carrera_nombre, 
                        t.nombre as turno_nombre,
                        (SELECT COUNT(*) FROM alumnos WHERE grupo_id = g.id AND activo = 1) as total_alumnos
                 FROM grupos g 
                 LEFT JOIN carreras c ON g.carrera_id = c.id
                 LEFT JOIN turnos t ON g.turno_id = t.id
                 WHERE g.activo = 1 
                 ORDER BY g.nombre";
$grupos_result = $conn->query($grupos_query);

// Obtener carreras para el formulario
$carreras_query = "SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre";
$carreras_result = $conn->query($carreras_query);

// Obtener turnos para el formulario
$turnos_query = "SELECT * FROM turnos WHERE activo = 1 ORDER BY nombre";
$turnos_result = $conn->query($turnos_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Grupos</title>
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
            <h2>👥 Gestión de Grupos</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de registro -->
            <form method="POST" action="">
                <input type="hidden" name="action" value="agregar" id="action">
                <input type="hidden" name="id" id="grupo_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre del Grupo *</label>
                        <input type="text" id="nombre" name="nombre" required 
                               placeholder="Ej: ISC-SOT-U">
                    </div>
                    
                    <div class="form-group">
                        <label for="carrera_id">Carrera</label>
                        <select id="carrera_id" name="carrera_id">
                            <option value="">Seleccionar carrera...</option>
                            <?php while ($carrera = $carreras_result->fetch_assoc()): ?>
                                <option value="<?php echo $carrera['id']; ?>">
                                    <?php echo $carrera['nombre'] . ' (' . $carrera['clave'] . ')'; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="turno_id">Turno</label>
                        <select id="turno_id" name="turno_id">
                            <option value="">Seleccionar turno...</option>
                            <?php while ($turno = $turnos_result->fetch_assoc()): ?>
                                <option value="<?php echo $turno['id']; ?>">
                                    <?php echo $turno['nombre'] . ' - ' . $turno['horario']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="cuatrimestre">Cuatrimestre *</label>
                        <select id="cuatrimestre" name="cuatrimestre" required>
                            <option value="">Seleccionar...</option>
                            <?php for($i = 1; $i <= 11; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?>°</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="cupo_maximo">Cupo Máximo</label>
                        <input type="number" id="cupo_maximo" name="cupo_maximo" value="30" min="1" max="100">
                    </div>
                </div>

                <button type="submit" id="btnSubmit">Agregar Grupo</button>
                <button type="button" onclick="cancelarEdicion()" class="btn btn-secondary" id="btnCancelar" style="display:none;">Cancelar</button>
            </form>

            <!-- Lista de grupos -->
            <h3 style="margin-top: 40px;">Grupos Registrados</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Carrera</th>
                        <th>Turno</th>
                        <th>Cuatrimestre</th>
                        <th>Alumnos / Cupo</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grupos_result->data_seek(0);
                    while ($grupo = $grupos_result->fetch_assoc()): 
                        $disponibles = $grupo['cupo_maximo'] - $grupo['total_alumnos'];
                        $porcentaje = ($grupo['total_alumnos'] / $grupo['cupo_maximo']) * 100;
                        $clase_disponibilidad = $porcentaje < 70 ? 'success' : ($porcentaje < 90 ? 'warning' : 'error');
                    ?>
                        <tr>
                            <td><?php echo $grupo['id']; ?></td>
                            <td><strong><?php echo $grupo['nombre']; ?></strong></td>
                            <td><?php echo $grupo['carrera_nombre'] ?: '-'; ?></td>
                            <td><?php echo $grupo['turno_nombre'] ?: '-'; ?></td>
                            <td><?php echo $grupo['cuatrimestre'] ? $grupo['cuatrimestre'] . '°' : '-'; ?></td>
                            <td><?php echo $grupo['total_alumnos'] . ' / ' . $grupo['cupo_maximo']; ?></td>
                            <td>
                                <span style="color: <?php echo $clase_disponibilidad == 'success' ? 'green' : ($clase_disponibilidad == 'warning' ? 'orange' : 'red'); ?>">
                                    <?php echo $disponibles; ?> lugares disponibles
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick='editarGrupo(<?php echo json_encode($grupo); ?>)' class="btn btn-edit">Editar</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este grupo?');">
                                    <input type="hidden" name="action" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $grupo['id']; ?>">
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
        function editarGrupo(grupo) {
            document.getElementById('nombre').value = grupo.nombre;
            document.getElementById('carrera_id').value = grupo.carrera_id || '';
            document.getElementById('turno_id').value = grupo.turno_id || '';
            document.getElementById('cuatrimestre').value = grupo.cuatrimestre || '';
            document.getElementById('cupo_maximo').value = grupo.cupo_maximo;
            document.getElementById('grupo_id').value = grupo.id;
            document.getElementById('action').value = 'editar';
            document.getElementById('btnSubmit').textContent = 'Actualizar Grupo';
            document.getElementById('btnCancelar').style.display = 'inline-block';
            
            document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelarEdicion() {
            document.getElementById('nombre').value = '';
            document.getElementById('carrera_id').value = '';
            document.getElementById('turno_id').value = '';
            document.getElementById('cuatrimestre').value = '';
            document.getElementById('cupo_maximo').value = '30';
            document.getElementById('grupo_id').value = '';
            document.getElementById('action').value = 'agregar';
            document.getElementById('btnSubmit').textContent = 'Agregar Grupo';
            document.getElementById('btnCancelar').style.display = 'none';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
