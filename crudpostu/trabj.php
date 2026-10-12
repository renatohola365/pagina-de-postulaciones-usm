<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../bdconex.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    $datos_cabecera = [
        'id_iniciativa' => '','tipo_iniciativa_postu' => '', 'id_sede_postu' => '', 'id_region_or' => '', 
        'id_region_imp' => '', 'presupuesto' => '0', 'Presupuesto' => '0',
        'rut_empresa_postu' => '', 'nombre_iniciativa' => '', 'nombre_sede' => '', 
        'nombre_empresa' => 'Sin empresa', 'NOrigen' => '', 'NImpacto' => '', 
        'Nombre_Estado' => 'Borrador', 'id_estado_postu' => 5
    ];
} else {

    $sql_cabecera = "SELECT p.*, i.nombre_iniciativa, s.nombre_sede, e.nombre_empresa, r1.Nombre_Region as NOrigen, r2.Nombre_Region as NImpacto, est.Nombre_Estado
                    FROM post_inicia p
                    LEFT JOIN iniciativa i ON p.id_inicia = i.Id_iniciativa
                    LEFT JOIN sede s ON p.id_sede_postu = s.id_sede
                    LEFT JOIN empresa e ON p.rut_empresa_postu = e.rut_empresa
                    JOIN region r1 ON p.Id_region_or = r1.Id_region
                    JOIN region r2 ON p.Id_region_imp = r2.Id_region
                    JOIN estado_postulacion est ON p.id_estado_postu = est.Id_Estado
                    WHERE p.N_Postulacion = ?";

    $stmt = $conexion->prepare($sql_wcabecera);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $datos_cabecera = $stmt->get_result()->fetch_assoc();
    

    $res_equipo = $conexion->query("SELECT * FROM int_eq WHERE N_Postulacion = $id");
    $res_cronograma = $conexion->query("SELECT * FROM cronograma WHERE N_Postulacion = $id ORDER BY Etapa_Crono ASC");
}

$regiones = $conexion->query("SELECT Id_region, Nombre_Region FROM region ORDER BY Nombre_Region ASC");
$sedes = $conexion->query("SELECT id_sede, Nombre_sede FROM sede ORDER BY Nombre_sede ASC");
$roles = $conexion->query("SELECT Id_rol, Nombre_rol FROM rol ORDER BY Nombre_rol ASC");
$iniciativas = $conexion->query("SELECT Id_iniciativa, Nombre_Iniciativa FROM iniciativa ORDER BY Nombre_Iniciativa ASC");

$regiones_arr = $regiones ? $regiones->fetch_all(MYSQLI_ASSOC) : [];
$sedes_arr = $sedes ? $sedes->fetch_all(MYSQLI_ASSOC) : [];
$roles_arr = $roles ? $roles->fetch_all(MYSQLI_ASSOC) : [];
$iniciativas_arr = $iniciativas ? $iniciativas->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? "Detalle Postulación #$id" : "Nueva Postulación"; ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: '-apple-system', BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            color: #2d3748;
            padding: 30px 20px;
        }
        .main-wrapper {
            max-width: 1250px;
            margin: 0 auto;
        }
        h1 {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
            letter-spacing: -0.5px;
        }
        h2 {
            font-size: 18px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 20px;
            border-bottom: 2px solid #edf2f7;
            padding-bottom: 8px;
        }
        h3, h4 {
            font-size: 16px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 15px;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
        }
        
        .grid-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 15px;
        }
        .info-group p {
            font-size: 14px;
            color: #475569;
            margin-bottom: 8px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 15px;
        }
        label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }
        input[type="text"],
        input[type="number"],
        input[type="email"],
        select {
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            color: #334155;
            background-color: #ffffff;
            outline: none;
            transition: all 0.2s ease;
            width: 100%;
        }
        input:focus, select:focus {
            border-color: #1d2127;
            box-shadow: 0 0 0 3px rgba(29, 33, 39, 0.1);
        }
        
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            border: 1px solid #e2e8f0;
            background: #ffffff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }
        th {
            background-color: #f8fafc;
            color: #64748b;
            padding: 14px 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            border-bottom: 2px solid #edf2f7;
        }
        td {
            padding: 12px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
            vertical-align: middle;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background-color: #f8fafc;
        }

        .btn-primary { background-color: #0275d8; color: white; }
        .btn-primary:hover { background-color: #025aa5; }
        .btn-success { background-color: #5cb85c; color: white; }
        .btn-success:hover { background-color: #449d44; }
        .btn-dark { background-color: #1d2127; color: white; }
        .btn-dark:hover { background-color: #334155; }
        .btn-danger { background-color: #d9534f; color: white; }
        .btn-danger:hover { background-color: #c9302c; }
        .btn-secondary { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background-color: #e2e8f0; }

        button, .btn-link {
            display: inline-block;
            padding: 10px 18px;
            cursor: pointer;
            font-weight: 600;
            border-radius: 8px;
            font-size: 13px;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .btn-quitar {
            color: #d9534f;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-quitar:hover {
            text-decoration: underline;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .status-revision { background: #fffbeb; color: #92400e; border-color: #fef3c7; }
        .status-aprobada { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .status-rechazada { background: #fef2f2; color: #991b1b; border-color: #fee2e2; }
        .status-cerrada { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
        .status-borrador { background: #f0f9ff; color: #0369a1; border-color: #bae6fd; }
        .status-default { background: #f8fafc; color: #64748b; border-color: #cbd5e1; }

        .empresa-asociada-box {
            margin-top: 20px;
            padding: 20px;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            background-color: #f8fafc;
        }
        .empresa-actual {
            display: inline-block;
            color: #0f172a;
            font-weight: 600;
            background: #e2e8f0;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
        }
        .form-oculto {
            display: none;
            background: #f8fafc;
            padding: 20px;
            margin-top: 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        .zona-peligro {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #fee2e2;
            background-color: #fef2f2;
            border-radius: 12px;
        }
        .zona-peligro h3 { color: #991b1b; }
        .zona-peligro p { color: #661e1e; font-size: 14px; margin-bottom: 15px; }

        .btn-volver {
            display: inline-block;
            margin-top: 15px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        .btn-volver:hover {
            color: #1e293b;
            text-decoration: underline;
        }
        .flex-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .inline-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            align-items: end;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <h1><?php echo $id ? "Gestión de Postulación #$id" : "Crear Nueva Postulación"; ?></h1>
    
    <?php include '../header.php'; ?>

    <?php if ($id): ?>
    <div class="dashboard-card">
        <h2>1. Información General</h2>
        <div class="grid-info">
            <div class="info-group">
                <p><strong>Iniciativa:</strong> <?php echo htmlspecialchars($datos_cabecera['nombre_iniciativa'] ?? ''); ?></p>
                <p><strong>Sede:</strong> <?php echo htmlspecialchars($datos_cabecera['nombre_sede'] ?? ''); ?></p>
                <p><strong>Presupuesto:</strong> $<?php echo isset($datos_cabecera['Presupuesto']) ? number_format($datos_cabecera['Presupuesto'], 0, ',', '.') : '0'; ?></p>
            </div>
            <div class="info-group">
                <p><strong>Fecha de Postulación:</strong> <?php echo htmlspecialchars($datos_cabecera['Fecha_Post'] ?? ''); ?></p>
                <p><strong>Región Origen:</strong> <?php echo htmlspecialchars($datos_cabecera['NOrigen'] ?? ''); ?></p>
                <p><strong>Región Impacto:</strong> <?php echo htmlspecialchars($datos_cabecera['NImpacto'] ?? ''); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="dashboard-card">
        <h2>Formulario de Datos</h2>
        <form action="<?php echo $id ? 'actualizar.php' : 'insertar.php'; ?>" method="POST">
            
            <input type="hidden" name="n_postulacion" value="<?php echo $id; ?>">
            <input type="hidden" name="id_estado" value="<?php echo $datos_cabecera['id_estado_postu']; ?>">
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px;">Estado Actual:</label>
                <?php
                $estado_nombre = mb_strtolower($datos_cabecera['Nombre_Estado'] ?? 'Borrador', 'UTF-8');
                $clase_estado = 'status-default';
                
                if (strpos($estado_nombre, 'revis') !== false) $clase_estado = 'status-revision';
                elseif (strpos($estado_nombre, 'aprobada') !== false) $clase_estado = 'status-aprobada';
                elseif (strpos($estado_nombre, 'rechazada') !== false) $clase_estado = 'status-rechazada';
                elseif (strpos($estado_nombre, 'cerrada') !== false) $clase_estado = 'status-cerrada';
                elseif (strpos($estado_nombre, 'borrador') !== false) $clase_estado = 'status-borrador';
                ?>
                <span class="badge-status <?php echo $clase_estado; ?>">
                    <?php echo htmlspecialchars($datos_cabecera['Nombre_Estado'] ?? 'Borrador'); ?>
                </span>
            </div>

            <div class="grid-info">
                <div class="form-group">
                    <label>Iniciativa:</label>
                    <select name="id_iniciativa" required>
                        <option value="">-- Seleccione iniciativa --</option>
                        <?php foreach ($iniciativas_arr as $ini):
                            $selected = ($ini['Id_iniciativa'] == ($datos_cabecera['id_iniciativa'] ?? '')) ? 'selected' : '';
                            echo '<option value="' . $ini['Id_iniciativa'] . '" ' . $selected . '>' . htmlspecialchars($ini['Nombre_Iniciativa']) . '</option>';
                        endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Sede:</label>
                    <select name="id_sede" required>
                        <option value="">-- Seleccione sede --</option>
                        <?php foreach ($sedes_arr as $sede):
                            $selected = ($sede['id_sede'] == $datos_cabecera['id_sede_postu']) ? 'selected' : '';
                            echo '<option value="' . $sede['id_sede'] . '" ' . $selected . '>' . htmlspecialchars($sede['Nombre_sede']) . '</option>';
                        endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Región de Origen:</label>
                    <select name="id_region_or" required>
                        <option value="">-- Seleccione región de origen --</option>
                        <?php foreach ($regiones_arr as $region):
                            $selected = ($region['Id_region'] == $datos_cabecera['id_region_or']) ? 'selected' : '';
                            echo '<option value="' . $region['Id_region'] . '" ' . $selected . '>' . htmlspecialchars($region['Nombre_Region']) . '</option>';
                        endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Región de Impacto:</label>
                    <select name="id_region_imp" required>
                        <option value="">-- Seleccione región de impacto --</option>
                        <?php foreach ($regiones_arr as $region):
                            $selected = ($region['Id_region'] == $datos_cabecera['id_region_imp']) ? 'selected' : '';
                            echo '<option value="' . $region['Id_region'] . '" ' . $selected . '>' . htmlspecialchars($region['Nombre_Region']) . '</option>';
                        endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Presupuesto:</label>
                    <input type="number" name="presupuesto" value="<?php echo htmlspecialchars($datos_cabecera['presupuesto'] ?? $datos_cabecera['Presupuesto'] ?? '0'); ?>" required>
                </div>
            </div>

            <div class="empresa-asociada-box">
                <h3>Asociación de Empresa Externa</h3>
                <p style="font-size: 14px; margin-bottom: 12px; color: #475569;">
                    Empresa actualmente asociada: <span class="empresa-actual"><?php echo htmlspecialchars($datos_cabecera['nombre_empresa'] ?? 'Sin empresa'); ?></span>
                </p>
                
                <div class="form-group" style="max-width: 400px; margin-bottom: 0;">
                    <label>Cambiar/Asociar Empresa:</label>
                    <select name="empresa">
                        <option value="">-- Seleccione una empresa --</option>
                        <?php
                        $query_emp = $conexion->query("SELECT rut_empresa, nombre_empresa FROM empresa"); 
                        while ($emp = $query_emp->fetch_assoc()) {
                            $selected = ($emp['rut_empresa'] == $datos_cabecera['rut_empresa_postu']) ? "selected" : "";
                            echo "<option value='".htmlspecialchars($emp['rut_empresa'])."' $selected>".htmlspecialchars($emp['nombre_empresa'])."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <br>
            
            <div class="flex-buttons">
                <?php if ($id): ?>
                    <button type="submit" name="accion" value="guardar" class="btn-primary">
                        Guardar Todos los Cambios
                    </button>

                    <button type="submit" name="accion" value="enviar" class="btn-success" onclick="return confirm('¿Estás seguro de enviar la postulación definitiva? Al hacerlo, pasará al Evaluador y ya no podrás editarla como borrador.');">
                        Enviar Postulación
                    </button>
                <?php else: ?>
                    <button type="submit" class="btn-primary">
                        Registrar Nueva Postulación
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if ($id && isset($res_equipo)): ?>
    <div class="dashboard-card">
        <h2>2. Equipo de Trabajo</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>RUT</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Departamento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res_equipo->num_rows > 0): ?>
                        <?php while($integ = $res_equipo->fetch_assoc()): ?>
                        <tr>
                            <td class="id-col"><?php echo htmlspecialchars($integ['Rut_int']); ?></td>
                            <td style="font-weight: 500; color: #1e293b;"><?php echo htmlspecialchars($integ['Nombre_int']); ?></td>
                            <td><?php echo htmlspecialchars($integ['Email_int']); ?></td>
                            <td><?php echo htmlspecialchars($integ['Departamento_int']); ?></td>
                            <td>
                                <a href="eliminar_integrante.php?rut=<?php echo urlencode($integ['Rut_int']); ?>&postu=<?php echo $id; ?>" class="btn-quitar">Quitar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">No hay integrantes añadidos a esta postulación.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <button type="button" class="btn-dark" onclick="document.getElementById('form-equipo').style.display='block'">+ Añadir Integrante</button>

        <div id="form-equipo" class="form-oculto">
            <h3>Nuevo Integrante</h3> <br>
            <form action="integrantegd.php" method="POST">
                <input type="hidden" name="n_postulacion" value="<?php echo $id; ?>">
                
                <div class="grid-info">
                    <div class="form-group"><label>RUT:</label><input type="text" name="rut_int" placeholder="RUT (con guion)" required></div>
                    <div class="form-group"><label>Nombre Completo:</label><input type="text" name="nombre_int" placeholder="Nombre completo" required></div>
                    <div class="form-group"><label>Email:</label><input type="email" name="email_int" placeholder="Correo electrónico"></div>
                    <div class="form-group"><label>Departamento:</label><input type="text" name="depto_int" placeholder="Departamento"></div>
                    <div class="form-group"><label>Teléfono:</label><input type="text" name="tel_int" placeholder="Teléfono"></div>
                    <div class="form-group"><label>Rol:</label>
                        <select name="rol_int" required>
                                <option value="">-- Seleccione rol --</option>
                                <?php foreach ($roles_arr as $rol):
                                    echo '<option value="' . $rol['Id_rol'] . '">' . htmlspecialchars($rol['Nombre_rol']) . '</option>';
                                endforeach; ?>
                            </select>
                    </div>
                        <div class="form-group"><label>Sede:</label>
                            <select name="sede_int" required>
                                <option value="">-- Seleccione sede --</option>
                                <?php foreach ($sedes_arr as $sede):
                                    echo '<option value="' . $sede['id_sede'] . '">' . htmlspecialchars($sede['Nombre_sede']) . '</option>';
                                endforeach; ?>
                            </select>
                        </div>
                </div>
                
                <div class="flex-buttons" style="margin-top: 15px;">
                    <button type="submit" class="btn-success">Guardar Integrante</button>
                    <button type="button" class="btn-secondary" onclick="document.getElementById('form-equipo').style.display='none'">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($id && isset($res_cronograma)): ?>
    <div class="dashboard-card">
        <h2>3. Cronograma de Actividades</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N° Etapa</th>
                        <th>Entregable</th>
                        <th>Plazo (Días)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res_cronograma->num_rows > 0): ?>
                        <?php while($etapa = $res_cronograma->fetch_assoc()){ ?>
                            <tr>
                                <td class="id-col">#<?php echo htmlspecialchars($etapa['Etapa_Crono']); ?></td>
                                <td style="color: #1e293b; font-weight: 500;"><?php echo htmlspecialchars($etapa['Entregable_Crono']); ?></td>
                                <td><?php echo htmlspecialchars($etapa['Plazos_Crono']); ?> días</td>
                                <td>
                                    <a href="eliminar_etapa.php?n_etapa=<?php echo urlencode($etapa['Etapa_Crono']); ?>&postu=<?php echo $id; ?>" class="btn-quitar">Eliminar</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">No hay etapas registradas para este proyecto.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h4 style="margin-top: 25px; margin-bottom: 10px;">Añadir Nueva Etapa</h4>
        <form action="etapagd.php" method="POST" class="inline-form-grid">
            <input type="hidden" name="n_postulacion" value="<?php echo $id; ?>">
            <div class="form-group">
                <label>N° Etapa:</label>
                <input type="number" name="etapa_crono" placeholder="N°" required>
            </div>
            <div class="form-group">
                <label>Entregable:</label>
                <input type="text" name="entregable_crono" placeholder="Descripción" required>
            </div>
            <div class="form-group">
                <label>Plazo (Días):</label>
                <input type="number" name="plazos_crono" placeholder="Días" required>
            </div>
            <button type="submit" class="btn-dark" style="height: 41px;">Guardar Etapa</button>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if ($id): ?>
    <div class="zona-peligro">
        <h3>Zona de Peligro</h3>
        <p>Al eliminar esta postulación, se borrarán permanentemente la cabecera, todos sus integrantes asignados y su correspondiente cronograma de actividades.</p>
        
        <form action="procelimin.php" method="POST" onsubmit="return confirm('¿Estás absolutamente seguro de que quieres eliminar esta postulación y todos sus componentes? Esta acción no se puede deshacer.');">
            <input type="hidden" name="n_postulacion" value="<?php echo $id; ?>">
            <button type="submit" class="btn-danger">
                Eliminar Postulación Completa
            </button>
        </form>
    </div>
    <?php endif; ?>
    
    <a href="../principal.php" class="btn-volver">← Volver al Buscador</a>
</div>

</body>
</html>