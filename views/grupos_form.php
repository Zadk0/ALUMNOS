<div class="container">
    <div class="content">
        <h2>🏫 Registrar Nuevo Grupo</h2>
        <p style="color: #667eea; margin-bottom: 20px; font-size: 0.9em;">
            Las siglas (ej. ISC5-M) se generarán automáticamente al guardar.
        </p>

        <form action="../controllers/guardar_grupo.php" method="POST">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Carrera</label>
                    <select name="carrera_id" required>
                        <option value="">Selecciona Carrera</option>
                        <?php
                        require_once "../config/config.php"; 
                        $conn = getConnection();
                        $res_c = $conn->query("SELECT id, nombre FROM carreras WHERE activo = 1");
                        while($c = $res_c->fetch_assoc()){
                            echo "<option value='{$c['id']}'>{$c['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Turno</label>
                    <select name="turno_id" required>
                        <option value="">Selecciona Horario</option>
                        <?php
                        $res_t = $conn->query("SELECT id, nombre FROM turnos");
                        while($t = $res_t->fetch_assoc()){
                            echo "<option value='{$t['id']}'>{$t['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Grado (Número)</label>
                    <input type="number" name="grado" placeholder="Ej. 1" min="1" max="12" required>
                </div>

                <div class="form-group">
                    <label>Letra de Turno (Para Siglas)</label>
                    <select name="turno_letra" required>
                        <option value="M">M (Matutino)</option>
                        <option value="V">V (Vespertino)</option>
                        <option value="X">X (Mixto)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Cuatrimestre</label>
                    <input type="number" name="cuatrimestre" min="1" max="12" required>
                </div>
                <div class="form-group">
                    <label>Cupo Máximo</label>
                    <input type="number" name="cupo_maximo" value="30">
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn">Crear Grupo</button>
            </div>
        </form>
    </div>
</div>