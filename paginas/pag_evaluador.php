<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../bdconex.php'; 

$rut_evaluador_sesion = isset($_SESSION['rut']) ? $_SESSION['rut'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Evaluador</title>
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

        select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
            color: #334155;
            background-color: #ffffff;
            outline: none;
            transition: all 0.2s ease;
        }
        select:focus {
            border-color: #1d2127;
            box-shadow: 0 0 0 3px rgba(29, 33, 39, 0.1);
        }
        .btn-go {
            background-color: #0066ff;
            color: white;
            border: none;
            padding: 8px 14px;
            cursor: pointer;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s ease;
        }
        .btn-go:hover {
            background-color: #0051cc;
        }
        .error-message {
            padding: 16px;
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="dashboard-card">
        <h2>Postulaciones Gestionadas</h2>
        
        <?php include '../header.php'; ?>
        
        <?php
        $sql_estados = "SELECT Id_Estado, nombre_estado FROM estado_postulacion";
        $resultado_estados = $conexion->query($sql_estados);
        
        $estados = [];
        if ($resultado_estados) {
            while ($est = $resultado_estados->fetch_assoc()) {
                $estados[] = $est;
            }
        }

        $sql1 = "SELECT * FROM v_postulaciones_evaluador WHERE rut_evaluador = ?";
        $stmt = $conexion->prepare($sql1);
        $stmt->bind_param("s", $rut_evaluador_sesion);
        $stmt->execute();
        $resultado1 = $stmt->get_result();

        if ($resultado1) {
            if ($resultado1->num_rows > 0) {
                echo '<div class="table-container">';
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>N°</th>";
                echo "<th>Iniciativa</th>";
                echo "<th>Objetivo</th>";
                echo "<th>Región Origen</th>";
                echo "<th>Región Impacto</th>";
                echo "<th>Presupuesto</th>";
                echo "<th>Estado Actual</th>";
                echo "<th>Modificar Estado</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                while($row = $resultado1->fetch_assoc()) {
                    echo "<tr>";
                    echo '<td class="id-col">' . $row['N_Postulacion'] . '</td>';
                    echo "<td>" . $row['nombre_iniciativa'] . "</td>";
                    echo "<td>" . ($row['Obj_iniciativa'] ?? 'Sin objetivo') . "</td>";
                    echo "<td>" . $row['Region_Origen'] . "</td>";
                    echo "<td>" . $row['Region_Impacto'] . "</td>";
                    echo '<td class="presupuesto-col">$' . number_format($row['Presupuesto'], 0, ',', '.') . '</td>';
                    
                    // Lógica para asignar la clase de color según el estado
                    $estado_nombre = mb_strtolower($row['nombre_estado'], 'UTF-8');
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

                    echo '<td><span class="badge-status ' . $clase_estado . '">' . $row['nombre_estado'] . '</span></td>';
                    
                    echo "<td>";
                    echo "<form action='cambioseval.php' method='POST' style='display: flex; gap: 6px; align-items: center;'>";
                    echo "<input type='hidden' name='n_postulacion' value='" . $row['N_Postulacion'] . "'>";
                    
                    echo "<select name='id_estado' required>";
                    foreach ($estados as $e) {
                        $selected = ($e['Id_Estado'] == $row['id_estado_postu']) ? 'selected' : '';
                        echo "<option value='" . $e['Id_Estado'] . "' " . $selected . ">" . $e['nombre_estado'] . "</option>";
                    }
                    echo "</select>";
                    
                    echo "<button type='submit' class='btn-go'>Actualizar</button>";
                    echo "</form>";
                    echo "</td>";
                    
                    echo "</tr>";
                }
                
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo '<div style="text-align: center; color: #64748b; padding: 40px 20px;">No tienes postulaciones asignadas para evaluar actualmente.</div>';
            }
            $stmt->close();
        } else {
            echo '<div class="error-message">Error en la vista: ' . $conexion->error . '</div>';
        }
        ?>
    </div>
</div>

</body>
</html>