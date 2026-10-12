<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../bdconex.php'; 

// Obtener el RUT del responsable logueado
$rut_responsable = isset($_SESSION['rut']) ? $_SESSION['rut'] : null;

// Si no hay sesión iniciada, redirigir al login
if (!$rut_responsable) {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT p.N_Postulacion, i.Nombre_Iniciativa, p.Presupuesto, e.Nombre_Estado, p.id_estado_postu
        FROM post_inicia p
        JOIN iniciativa i ON p.id_inicia = i.Id_iniciativa
        JOIN estado_postulacion e ON p.id_estado_postu = e.Id_Estado
        WHERE p.rut_responsable = ?
          AND p.id_estado_postu = 5
        ORDER BY p.Fecha_Post DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $rut_responsable);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Responsable</title>
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
        h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
            letter-spacing: -0.5px;
        }
        h3 {
            font-size: 18px;
            font-weight: 600;
            color: #334155;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .alert {
            padding: 14px 18px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid transparent;
        }
        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }
        .action-bar {
            margin: 20px 0 25px 0;
        }
        .btn-crear {
            display: inline-block;
            background: #1d2127;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .btn-crear:hover {
            background: #334155;
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            border: 1px solid #e2e8f0;
            background: #ffffff;
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
            padding: 16px 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            border-bottom: 2px solid #edf2f7;
        }
        td {
            padding: 14px 20px;
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
        .presupuesto-col {
            font-weight: 600;
            color: #0f172a;
        }
        
        /* Estilos base para los badges de estado */
        .badge-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid transparent;
        }

        /* Colores específicos por estado */
        .status-revision {
            background: #fffbeb;
            color: #92400e;
            border-color: #fef3c7;
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
        .status-cerrada {
            background: #f1f5f9;
            color: #475569;
            border-color: #e2e8f0;
        }
        .status-borrador {
            background: #f0f9ff;
            color: #0369a1;
            border-color: #bae6fd;
        }
        .status-default {
            background: #f8fafc;
            color: #64748b;
            border-color: #cbd5e1;
        }

        .btn-editar {
            display: inline-block;
            background-color: #fdf2e9;
            color: #c2410c;
            padding: 6px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #ffedd5;
            transition: all 0.2s ease;
        }
        .btn-editar:hover {
            background-color: #ffedd5;
            color: #9a3412;
        }
        .btn-bloqueado {
            display: inline-block;
            background-color: #f1f5f9;
            color: #94a3b8;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="dashboard-card">
        <h2>Panel de Gestión - Responsable Académico</h2>
        
        <?php include '../header.php'; ?>

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'creado'): ?>
            <div class="alert alert-success">
                ¡Éxito! La postulación ha sido creada correctamente en estado Borrador.
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'actualizado'): ?>
            <div class="alert alert-success">
                ¡Éxito! Los datos de la postulación han sido modificados.
            </div>
        <?php endif; ?>

        <div class="action-bar">
            <a href="../crudpostu/trabj.php" class="btn-crear">+ Nueva Postulación</a>
        </div>

        <h3>Mis Postulaciones</h3>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Iniciativa</th>
                        <th>Presupuesto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td class="id-col"><?php echo $row['N_Postulacion']; ?></td>
                                <td><?php echo $row['Nombre_Iniciativa']; ?></td>
                                <td class="presupuesto-col">$<?php echo number_format($row['Presupuesto'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php
                                    // Evaluar el string para asignar la clase correcta sin problemas de tildes o mayúsculas
                                    $estado_nombre = mb_strtolower($row['Nombre_Estado'], 'UTF-8');
                                    $clase_estado = 'status-default';
                                    
                                    if (strpos($estado_nombre, 'revis') !== false) {
                                        $clase_estado = 'status-revision';
                                    } elseif (strpos($estado_nombre, 'aprobada') !== false) {
                                        $clase_estado = 'status-aprobada';
                                    } elseif (strpos($estado_nombre, 'rechazada') !== false) {
                                        $clase_estado = 'status-rechazada';
                                    } elseif (strpos($estado_nombre, 'cerrada') !== false) {
                                        $clase_estado = 'status-cerrada';
                                    } elseif (strpos($estado_nombre, 'borrador') !== false) {
                                        $clase_estado = 'status-borrador';
                                    }
                                    ?>
                                    <span class="badge-status <?php echo $clase_estado; ?>">
                                        <?php echo $row['Nombre_Estado']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['id_estado_postu'] == 5): ?>
                                        <a href="../crudpostu/trabj.php?id=<?php echo $row['N_Postulacion']; ?>" class="btn-editar">Editar</a>
                                    <?php else: ?>
                                        <span class="btn-bloqueado" title="No puedes editar un proyecto que ya está en revisión/aprobado">Bloqueado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 30px;">No tienes postulaciones registradas aún.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>