<div class="container">
    <div class="content">
        <h2>🎓 Registrar Nuevo Alumno</h2>
        <form action="../controllers/AlumnoController.php" method="POST">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre(s)</label>
                    <input type="text" name="nombre" placeholder="Ej. Juan" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" placeholder="Ej. Pérez" required>
                </div>
                <div class="form-group">
                    <label>Apellido Materno</label>
                    <input type="text" name="apellido_materno" placeholder="Ej. García">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Carrera</label>
                    <select onchange="cargarGrupos(this.value)" required>
                        <option value="">Selecciona una carrera</option>
                        <?php
                        // Usamos la conexión unificada y el nombre de tabla 'carreras'
                        require_once "../config/config.php"; 
                        $conn = getConnection();
                        $c = $conn->query("SELECT id, nombre FROM carreras WHERE activo = 1");
                        while($row = $c->fetch_assoc()){
                            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Grupo (Siglas automáticas)</label>
                    <select name="grupo_id" id="grupo_select" required>
                        <option value="">Primero selecciona una carrera</option>
                    </select>
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" name="crear" class="btn">Guardar Alumno</button>
            </div>
        </form>
    </div>
</div>