<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['cargo']) && stripos($_SESSION['cargo'], 'admin') !== false) {
    header('Location: paginas/pag_admin.php');
    exit();
}

include 'bdconex.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Principal</title>
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
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .search-wrapper {
            margin: 20px 0 30px 0;
        }
        .search-form {
            display: flex;
            max-width: 450px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 30px;
            padding: 4px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .search-form:focus-within {
            border-color: #1d2127;
            box-shadow: 0 0 0 3px rgba(29, 33, 39, 0.1);
        }
        .search-input {
            flex: 1;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            background: transparent;
            color: #334155;
        }
        .search-input:focus {
            outline: none;
        }
        .search-btn {
            background: #1d2127;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .search-btn:hover {
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
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
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
        .badge-status {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
        }
        .presupuesto-col {
            font-weight: 600;
            color: #0f172a;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="dashboard-card">
        <h1>Bienvenido a la página principal</h1>
        
        <?php include 'header.php'; ?>
        
        <div class="search-wrapper">
            <form action="" method="GET" class="search-form"> 
                <input type="text" name="busqueda" placeholder="Buscar Postulación..." class="search-input">
                <button type="submit" class="search-btn">Buscar</button>
            </form>
        </div>

        <?php
        include 'barrabusq.php';
        $rol = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : '';
        ?>

        <?php
            $rut_usuario = isset($_SESSION['rut']) ? $_SESSION['rut'] : '';
            $sql = "SELECT * FROM vista_postulaciones_gestionadas 
                    WHERE rut_evaluador = '$rut_usuario' 
                       OR rut_responsable = '$rut_usuario'";
            $resultado = mysqli_query($conexion, $sql);
        ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Empresa</th>
                        <th>Iniciativa</th>
                        <th>Sede</th>
                        <th>Región Origen</th>
                        <th>Región Impacto</th>
                        <th>Presupuesto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                        <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td class="id-col"><?php echo $fila['N_Postulacion']; ?></td>
                            <td><?php echo $fila['Nombre_Empresa']; ?></td>
                            <td><?php echo $fila['Nombre_Iniciativa']; ?></td>
                            <td><?php echo $fila['Nombre_sede']; ?></td>
                            <td><?php echo $fila['Region Origen']; ?></td>
                            <td><?php echo $fila['Region Impacto']; ?></td>
                            <td class="presupuesto-col">$<?php echo number_format($fila['Presupuesto'], 0, ',', '.'); ?></td>
                            <td><span class="badge-status"><?php echo $fila['Nombre_Estado']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: #94a3b8; padding: 30px;">No se encontraron postulaciones asociadas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($rol == 'Responsable Academico'): ?>
            <?php 
            $rut_responsable = isset($_SESSION['rut']) ? $_SESSION['rut'] : '';
            $sql = "SELECT * FROM vista_postulaciones_gestionadas 
                    WHERE rut_responsable = '$rut_responsable'
                    ORDER BY N_Postulacion DESC";
            $resultado = mysqli_query($conexion, $sql);
            ?>

            <h2>Mis Postulaciones Asignadas</h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Empresa</th>
                            <th>Iniciativa</th>
                            <th>Presupuesto</th>
                            <th>Estado</th>
                            <th>Sede</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                            <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td class="id-col"><?php echo $fila['N_Postulacion']; ?></td>
                                <td><?php echo $fila['Nombre_Empresa']; ?></td>
                                <td><?php echo $fila['Nombre_Iniciativa']; ?></td>
                                <td class="presupuesto-col">$<?php echo number_format($fila['Presupuesto'], 0, ',', '.'); ?></td>
                                <td><span class="badge-status"><?php echo $fila['Nombre_Estado']; ?></span></td>
                                <td><?php echo $fila['Nombre_sede']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">No tienes postulaciones asignadas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>