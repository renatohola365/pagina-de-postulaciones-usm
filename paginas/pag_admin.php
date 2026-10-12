<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../bdconex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_evaluador'])) {
    $postulacion_id = $_POST['postulacion_id'];
    $nuevo_evaluador_rut = !empty($_POST['rut_evaluador']) ? $_POST['rut_evaluador'] : null;

    $update_query = "UPDATE post_inicia SET rut_evaluador = ? WHERE N_Postulacion = ?";
    $stmt = mysqli_prepare($conexion, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $nuevo_evaluador_rut, $postulacion_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_responsable'])) {
    $postulacion_id = $_POST['postulacion_id'];
    $nuevo_responsable_rut = !empty($_POST['rut_responsable']) ? $_POST['rut_responsable'] : null;

    $update_query = "UPDATE post_inicia SET rut_responsable = ? WHERE N_Postulacion = ?";
    $stmt = mysqli_prepare($conexion, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $nuevo_responsable_rut, $postulacion_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$evaluadores_result = mysqli_query($conexion, "SELECT rut, nombre FROM web_usuarios WHERE cargo = 'Evaluador CT_USM' ORDER BY nombre ASC");
$evaluadores = [];
while ($ev = mysqli_fetch_assoc($evaluadores_result)) {
    $evaluadores[] = $ev;
}

$responsables_result = mysqli_query($conexion, "SELECT rut, nombre FROM web_usuarios WHERE cargo = 'Responsable Academico' ORDER BY nombre ASC");
$responsables = [];
while ($resp = mysqli_fetch_assoc($responsables_result)) {
    $responsables[] = $resp;
}

$query = "SELECT v.*, p.rut_responsable, u.nombre AS responsable_nombre 
          FROM vista_administrador_asignaciones v
          JOIN post_inicia p ON v.postulacion_id = p.N_Postulacion
          LEFT JOIN web_usuarios u ON p.rut_responsable = u.rut
          ORDER BY v.fecha_postulacion DESC";
$result = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrator - CT-USM</title>
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
            max-width: 1450px;
            margin: 0 auto;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.5px;
        }
        .subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 25px;
        }
        .role-badge {
            display: inline-block;
            background: #64748b;
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            border: 1px solid #e2e8f0;
            background: #ffffff;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }
        th {
            background-color: #f8fafc;
            color: #64748b;
            padding: 16px 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            border-bottom: 2px solid #edf2f7;
        }
        td {
            padding: 14px 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
            vertical-align: middle;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background-color: #f8fafc;
            color: #0f172a;
        }
        .id-col {
            font-weight: 600;
            color: #1d2127;
        }
        .evaluador-name, .responsable-name {
            font-weight: 600;
            color: #1e293b;
        }
        .evaluador-rut, .responsable-rut {
            font-size: 11px;
            color: #64748b;
        }
        .badge-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .status-aprobada {
            background: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }
        .status-rechazada {
            background: #fef2f2;
            color: #991b1b;
            border-color: #fee2e2;
        }
        .status-revision {
            background: #fffbeb;
            color: #92400e;
            border-color: #fef3c7;
        }
        .status-default {
            background: #f1f5f9;
            color: #475569;
            border-color: #e2e8f0;
        }
        .status-alert {
            background-color: #fef2f2;
            color: #991b1b;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
            border: 1px solid #fee2e2;
            display: inline-block;
        }
        .action-form {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .select-evaluador, .select-responsable {
            padding: 6px 8px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 12px;
            color: #334155;
            background-color: #fff;
            outline: none;
            min-width: 160px;
        }
        .select-evaluador:focus, .select-responsable:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }
        .btn-assign {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-assign:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="dashboard-card">
        <div class="header-section">
            <h2>Panel Administrativo CT-USM</h2>
            <span class="role-badge">Rol: Administrador</span>
        </div>
        <div class="subtitle">Gestión de Evaluadores, Responsables y Asignaciones de Proyectos</div>
        
        <?php include '../header.php'; ?>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N° Post.</th>
                        <th>Código</th>
                        <th>Iniciativa</th>
                        <th>Empresa Externa</th>
                        <th>Fecha Post.</th>
                        <th>Estado</th>
                        <th>Responsable Asignado</th>
                        <th>Asignar Responsable</th>
                        <th>Evaluador Asignado</th>
                        <th>Asignar Evaluador</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr>
                            <td class="id-col">#<?php echo htmlspecialchars($row['postulacion_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['postulacion_codigo']); ?></td>
                            <td><?php echo htmlspecialchars($row['iniciativa_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['empresa_externa']); ?></td>
                            <td><?php echo htmlspecialchars($row['fecha_postulacion']); ?></td>
                            <td>
                                <?php
                                $status_class = 'status-default';
                                if ($row['postulacion_estado'] == 'Aprobada') $status_class = 'status-aprobada';
                                if ($row['postulacion_estado'] == 'Rechazada') $status_class = 'status-rechazada';
                                if ($row['postulacion_estado'] == 'En Revision') $status_class = 'status-revision';
                                ?>
                                <span class="badge-status <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($row['postulacion_estado']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($row['responsable_nombre'])): ?>
                                    <div class="responsable-name"><?php echo htmlspecialchars($row['responsable_nombre']); ?></div>
                                    <div class="responsable-rut"><?php echo htmlspecialchars($row['rut_responsable']); ?></div>
                                <?php else: ?>
                                    <span class="status-alert">SIN RESPONSABLE</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="action-form">
                                    <input type="hidden" name="postulacion_id" value="<?php echo $row['postulacion_id']; ?>">
                                    <select name="rut_responsable" class="select-responsable">
                                        <option value="">-- Seleccionar Responsable --</option>
                                        <?php foreach ($responsables as $responsable): ?>
                                            <option value="<?php echo htmlspecialchars($responsable['rut']); ?>" <?php echo ($row['rut_responsable'] == $responsable['rut']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($responsable['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="cambiar_responsable" class="btn-assign">Actualizar</button>
                                </form>
                            </td>
                            <td>
                                <?php if (!empty($row['evaluador_nombre'])): ?>
                                    <div class="evaluador-name"><?php echo htmlspecialchars($row['evaluador_nombre']); ?></div>
                                    <div class="evaluador-rut"><?php echo htmlspecialchars($row['evaluador_rut']); ?></div>
                                <?php else: ?>
                                    <span class="status-alert">SIN EVALUADOR</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="action-form">
                                    <input type="hidden" name="postulacion_id" value="<?php echo $row['postulacion_id']; ?>">
                                    <select name="rut_evaluador" class="select-evaluador">
                                        <option value="">-- Seleccionar Evaluador --</option>
                                        <?php foreach ($evaluadores as $evaluador): ?>
                                            <option value="<?php echo htmlspecialchars($evaluador['rut']); ?>" <?php echo ($row['evaluador_rut'] == $evaluador['rut']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($evaluador['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="cambiar_evaluador" class="btn-assign">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                        <tr>
                            <td colspan="10" style="text-align: center; color: #94a3b8; padding: 30px;">No se encontraron postulaciones registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

</body>
</html>