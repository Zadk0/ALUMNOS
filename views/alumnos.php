<?php
// alumnos.php
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
            $apellido_p = limpiarDatos($_POST['apellido_paterno']);
            $apellido_m = limpiarDatos($_POST['apellido_materno']);
            $correo = limpiarDatos($_POST['correo']);
            $telefono = limpiarDatos($_POST['telefono']);
            $genero = limpiarDatos($_POST['genero']);
            $grupo_id = !empty($_POST['grupo_id']) ? intval($_POST['grupo_id']) : NULL;
            
            if (!empty($nombre) && !empty($apellido_p)) {
                $stmt = $conn->prepare("INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno, correo, telefono, genero, grupo_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssi", $nombre, $apellido_p, $apellido_m, $correo, $telefono, $genero, $grupo_id);
                
                if ($stmt->execute()) {
                    $mensaje = "Alumno registrado exitosamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al registrar alumno: " . $conn->error;
                    $tipo_mensaje = "error";
                }
                $stmt->close();
            } else {
                $mensaje = "El nombre y apellido paterno son obligatorios";
                $tipo_mensaje = "error";
            }
        }
        
        if ($action == 'editar') {
            $id = intval($_POST['id']);
            $nombre = limpiarDatos($_POST['nombre']);
            $apellido_p = limpiarDatos($_POST['apellido_paterno']);
            $apellido_m = limpiarDatos($_POST['apellido_materno']);
            $correo = limpiarDatos($_POST['correo']);
            $telefono = limpiarDatos($_POST['telefono']);
            $genero = limpiarDatos($_POST['genero']);
            $grupo_id = !empty($_POST['grupo_id']) ? intval($_POST['grupo_id']) : NULL;
            
            $stmt = $conn->prepare("UPDATE alumnos SET nombre=?, apellido_paterno=?, apellido_materno=?, correo=?, telefono=?, genero=?, grupo_id=? WHERE id=?");
            $stmt->bind_param("ssssssii", $nombre, $apellido_p, $apellido_m, $correo, $telefono, $genero, $grupo_id, $id);
            
            if ($stmt->execute()) {
                $mensaje = "Alumno actualizado exitosamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al actualizar alumno";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
        
        if ($action == 'eliminar') {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE alumnos SET activo=0 WHERE id=?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $mensaje = "Alumno eliminado exitosamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al eliminar alumno";
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
    }
}

// Obtener lista de alumnos
$alumnos_query = "SELECT a.*, g.nombre as grupo_nombre 
                  FROM alumnos a 
                  LEFT JOIN grupos g ON a.grupo_id = g.id 
                  WHERE a.activo = 1 
                  ORDER BY a.apellido_paterno, a.apellido_materno, a.nombre";
$alumnos_result = $conn->query($alumnos_query);

// Obtener grupos para el formulario
$grupos_query = "SELECT g.id, g.nombre, g.cuatrimestre, c.nombre as carrera_nombre, t.nombre as turno_nombre 
                 FROM grupos g 
                 LEFT JOIN carreras c ON g.carrera_id = c.id
                 LEFT JOIN turnos t ON g.turno_id = t.id
                 WHERE g.activo = 1 
                 ORDER BY g.nombre";
$grupos_result = $conn->query($grupos_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos</title>
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
            <h2>Gestión de Alumnos</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de registro -->
            <form method="POST" action="">
                <input type="hidden" name="action" value="agregar">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido_paterno">Apellido Paterno *</label>
                        <input type="text" id="apellido_paterno" name="apellido_paterno" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido_materno">Apellido Materno</label>
                        <input type="text" id="apellido_materno" name="apellido_materno">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono">
                    </div>
                    
                    <div class="form-group">
                        <label for="genero">Género</label>
                        <select id="genero" name="genero">
                            <option value="">Seleccionar...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="grupo_id">Grupo</label>
                    <select id="grupo_id" name="grupo_id">
                        <option value="">Sin grupo asignado</option>
                        <?php 
                        $grupos_result->data_seek(0);
                        while ($grupo = $grupos_result->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $grupo['id']; ?>">
                                <?php echo $grupo['nombre'] . ' - ' . $grupo['carrera_nombre'] . ' - ' . ($grupo['cuatrimestre'] ? $grupo['cuatrimestre'] . '° Cuatrimestre' : '') . ' (' . $grupo['turno_nombre'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit">Registrar Alumno</button>
            </form>

            <!-- Lista de alumnos -->
            <h3 style="margin-top: 40px;">Lista de Alumnos</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Género</th>
                        <th>Grupo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($alumno = $alumnos_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $alumno['id']; ?></td>
                            <td>
                                <?php echo $alumno['apellido_paterno'] . ' ' . 
                                           $alumno['apellido_materno'] . ' ' . 
                                           $alumno['nombre']; ?>
                            </td>
                            <td><?php echo $alumno['correo'] ?: '-'; ?></td>
                            <td><?php echo $alumno['telefono'] ?: '-'; ?></td>
                            <td><?php echo $alumno['genero'] ?: '-'; ?></td>
                            <td><?php echo $alumno['grupo_nombre'] ?: 'Sin grupo'; ?></td>
                            <td class="actions">
                                <button onclick="editarAlumno(<?php echo htmlspecialchars(json_encode($alumno)); ?>)" class="btn btn-edit">Editar</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este alumno?');">
                                    <input type="hidden" name="action" value="eliminar">
                                    <input type="hidden" name="id" value="<?php echo $alumno['id']; ?>">
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
        function editarAlumno(alumno) {
            if (confirm('¿Desea editar este alumno?')) {
                document.getElementById('nombre').value = alumno.nombre;
                document.getElementById('apellido_paterno').value = alumno.apellido_paterno;
                document.getElementById('apellido_materno').value = alumno.apellido_materno || '';
                document.getElementById('correo').value = alumno.correo || '';
                document.getElementById('telefono').value = alumno.telefono || '';
                document.getElementById('genero').value = alumno.genero || '';
                document.getElementById('grupo_id').value = alumno.grupo_id || '';
                
                // Cambiar el formulario a modo edición
                const form = document.querySelector('form');
                form.querySelector('input[name="action"]').value = 'editar';
                
                // Agregar campo ID
                let idInput = form.querySelector('input[name="id"]');
                if (!idInput) {
                    idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'id';
                    form.appendChild(idInput);
                }
                idInput.value = alumno.id;
                
                // Cambiar texto del botón
                form.querySelector('button[type="submit"]').textContent = 'Actualizar Alumno';
                
                // Scroll al formulario
                form.scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
