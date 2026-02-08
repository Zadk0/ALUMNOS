<?php
// grupos.php - Gestión de Grupos
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
            $carrera_id = !empty($_POST['carrera_id']) ? intval($_POST['carrera_id']) : NULL;
            $turno_id = !empty($_POST['turno_id']) ? intval($_POST['turno_id']) : NULL;
            $cuatrimestre = !empty($_POST['cuatrimestre']) ? intval($_POST['cuatrimestre']) : NULL;
            $cupo_maximo = intval($_POST['cupo_maximo']);
            
            if (!empty($nombre)) {
                $stmt = $conn->prepare("INSERT INTO grupos (nombre, carrera_id, turno_id, cuatrimestre, cupo_maximo, activo) VALUES (?, ?, ?, ?, ?, 1)");
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
            // Usamos 'activo=0' para borrado lógico como en tu BD unificada
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

// Obtener lista de grupos con información relacionada (Usando columna 'estatus' para alumnos si aplica)
// Busca esta línea cerca de la 88 y déjala así:
$grupos_query = "SELECT g.*, c.nombre as carrera_nombre, t.nombre as turno_nombre 
                 FROM grupos g 
                 LEFT JOIN carreras c ON g.carrera_id = c.id 
                 LEFT JOIN turnos t ON g.turno_id = t.id 
                 ORDER BY g.grado, g.siglas";
$grupos_result = $conn->query($grupos_query);

// Obtener carreras para el formulario
$carreras_result = $conn->query("SELECT * FROM carreras WHERE activo = 1 ORDER BY nombre");

// Obtener turnos para el formulario
$turnos_result = $conn->query("SELECT * FROM turnos WHERE activo = 1 ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Grupos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Sistema de Gestión Escolar</h1>
        </header>

        <?php include 'menu.php'; ?>

        <div class="content">
            <h2>👥 Gestión de Grupos</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="action" value="agregar" id="action">
                <input type="hidden" name="id" id="grupo_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre del Grupo *</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Ej: ISC-501-M">
                    </div>
                    
                    <div class="form-group">
                        <label for="carrera_id">Carrera</label>
                        <select id="carrera_id" name="carrera_id">
                            <option value="">Seleccionar carrera...</option>
                            <?php while ($carrera = $carreras_result->fetch_assoc()): ?>
                                <option value="<?php echo $carrera['id']; ?>">
                                    <?php echo $carrera['nombre'] . ' (' . $carrera['siglas'] . ')'; ?>
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
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?>°</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="cupo_maximo">Cupo Máximo</label>
                        <input type="number" id="cupo_maximo" name="cupo_maximo" value="30" min="1" max="100">
                    </div>
                </div>

                <div style="text-align: right; margin-top: 10px;">
                    <button type="submit" id="btnSubmit" class="btn">Agregar Grupo</button>
                    <button type="button" onclick="cancelarEdicion()" class="btn btn-secondary" id="btnCancelar" style="display:none;">Cancelar</button>
                </div>
            </form>

            <h3 style="margin-top: 40px; color: #667eea;">Grupos Registrados</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Siglas/Nombre</th>
                        <th>Carrera</th>
                        <th>Turno</th>
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
                        $color_disponibilidad = $porcentaje < 70 ? '#22c55e' : ($porcentaje < 90 ? '#f1c40f' : '#ef4444');
                    ?>
                        <tr>
                            <td><?php echo $grupo['id']; ?></td>
                            <td><strong><?php echo $grupo['nombre']; ?></strong></td>
                            <td><?php echo $grupo['carrera_nombre'] ?: '-'; ?></td>
                            <td><?php echo $grupo['turno_nombre'] ?: '-'; ?></td>
                            <td><?php echo $grupo['total_alumnos'] . ' / ' . $grupo['cupo_maximo']; ?></td>
                            <td>
                                <span style="color: <?php echo $color_disponibilidad; ?>; font-weight: bold;">
                                    <?php echo $disponibles; ?> lugares
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
            
            document.querySelector('.content').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelarEdicion() {
            document.getElementById('action').value = 'agregar';
            document.querySelector('form').reset();
            document.getElementById('btnSubmit').textContent = 'Agregar Grupo';
            document.getElementById('btnCancelar').style.display = 'none';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>