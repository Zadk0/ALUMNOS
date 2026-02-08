<?php
// alumnos.php
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
            $stmt = $conn->prepare("UPDATE alumnos SET estatus=0 WHERE id=?");
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

// Obtener lista de alumnos (Usando columna 'estatus' de tu BD unificada)
$alumnos_query = "SELECT a.*, g.siglas as grupo_nombre 
                  FROM alumnos a 
                  LEFT JOIN grupos g ON a.grupo_id = g.id 
                  WHERE a.estatus = 1 
                  ORDER BY a.apellido_paterno, a.apellido_materno, a.nombre";
$alumnos_result = $conn->query($alumnos_query);

// Obtener grupos para el formulario
$grupos_query = "SELECT g.id, g.siglas, g.cuatrimestre, c.nombre as carrera_nombre, t.nombre as turno_nombre 
                 FROM grupos g 
                 LEFT JOIN carreras c ON g.carrera_id = c.id
                 LEFT JOIN turnos t ON g.turno_id = t.id
                 ORDER BY g.siglas";
$grupos_result = $conn->query($grupos_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
        </header>

        <?php include 'menu.php'; ?>

        <div class="content">
            <h2>Gestión de Alumnos</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

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
                                <?php echo $grupo['siglas'] . ' - ' . $grupo['carrera_nombre'] . ' (' . $grupo['turno_nombre'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn">Registrar Alumno</button>
            </form>

            <h3 style="margin-top: 40px; color: #667eea;">Lista de Alumnos Activos</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
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
                            <td><?php echo $alumno['grupo_nombre'] ?: '<span style="color:gray">Sin grupo</span>'; ?></td>
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
            document.getElementById('nombre').value = alumno.nombre;
            document.getElementById('apellido_paterno').value = alumno.apellido_paterno;
            document.getElementById('apellido_materno').value = alumno.apellido_materno || '';
            document.getElementById('correo').value = alumno.correo || '';
            document.getElementById('telefono').value = alumno.telefono || '';
            document.getElementById('genero').value = alumno.genero || '';
            document.getElementById('grupo_id').value = alumno.grupo_id || '';
            
            const form = document.querySelector('form');
            form.querySelector('input[name="action"]').value = 'editar';
            
            let idInput = form.querySelector('input[name="id"]');
            if (!idInput) {
                idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                form.appendChild(idInput);
            }
            idInput.value = alumno.id;
            
            form.querySelector('button[type="submit"]').textContent = 'Actualizar Alumno';
            form.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>