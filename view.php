<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'bdconex.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Vistas</title>
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
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        h3 {
            font-size: 16px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stats-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }
        .custom-list {
            list-style: none;
        }
        .custom-list li {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .custom-list li:last-child {
            border-bottom: none;
        }
        .custom-list li:hover {
            background-color: #f8fafc;
            color: #0f172a;
        }
        .project-list li {
            justify-content: flex-start;
            font-weight: 500;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 40px 0;
            border: none;
        }
        .error-message {
            padding: 16px;
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="dashboard-card">
        <h1>Bienvenido a la Pagina de vistas</h1>
        
        <?php include 'header.php'; ?>
        
        <div class="grid-container">
            <?php
            $sql = "CALL p_region_performance()";
            $contador = 1; 

            if ($conexion->multi_query($sql)) {
                do {
                    if ($resultado = $conexion->store_result()) {
                        
                        if ($contador == 1) {
                            echo '<div class="stats-box">';
                            echo "<h3>Regiones con mas Postulaciones</h3>";
                            echo '<ul class="custom-list">';
                            while ($fila = $resultado->fetch_assoc()) {
                                echo "<li><span>" . $fila['Region'] . "</span><strong>" . $fila['Total_Postulaciones'] . "</strong></li>";
                            }
                            echo "</ul>";
                            echo '</div>';
                        } 
                        else if ($contador == 2) {
                            echo '<div class="stats-box">';
                            echo "<h3>Regiones con mas proyectos</h3>";
                            echo '<ul class="custom-list">';
                            while ($fila = $resultado->fetch_assoc()) {
                                echo "<li><span>" . ($fila['Region_Impacto'] ?? $fila['Region'] ?? 'Otra') . "</span><strong>" . ($fila['Cantidad_Proyectos'] ?? $fila['Total_Postulaciones']) . "</strong></li>";
                            }
                            echo "</ul>";
                            echo '</div>';
                        }

                        $resultado->free();
                        $contador++;
                    }
                } while ($conexion->more_results() && $conexion->next_result());
            }
            ?>
        </div>

        <?php
        $sql1 = "SELECT * FROM vista_postulaciones_aceptadas";
        $resultado1 = $conexion->query($sql1);

        if ($resultado1) {
            echo "<h2>Listado de Proyectos Aprobados</h2>";
            echo '<div class="stats-box" style="padding: 10px 0;">';
            echo '<ul class="custom-list project-list">';
            while($row = $resultado1->fetch_assoc()) {
                echo "<li>" . ($row['Nombre_Iniciativa'] ?? $row['nombre_iniciativa'] ?? 'Sin nombre') . "</li>";
            }
            echo "</ul>";
            echo '</div>';
        } else {
            echo '<div class="error-message">Error en la vista: ' . $conexion->error . '</div>';
        }
        ?>
        
        <hr class="divider">
        
        <?php include 'busquedafiltro.php'; ?>
    </div>
</div>

</body>
</html>