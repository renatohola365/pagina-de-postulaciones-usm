<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../bdconex.php';

$res_iniciativas = $conexion->query("SELECT id_iniciativa, nombre_iniciativa FROM iniciativa");
$res_sedes = $conexion->query("SELECT id_sede, nombre_sede FROM sede");
$res_empresas = $conexion->query("SELECT rut_empresa, nombre_empresa FROM empresa");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Postulación</title>
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
            max-width: 700px;
            margin: 0 auto;
        }
        h1 {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
            letter-spacing: -0.5px;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        label {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }
        select, input[type="number"] {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            color: #334155;
            background-color: #ffffff;
            transition: all 0.3s ease;
        }
        select:focus, input[type="number"]:focus {
            outline: none;
            border-color: #1d2127;
            box-shadow: 0 0 0 3px rgba(29, 33, 39, 0.1);
        }
        .btn-container {
            margin-top: 30px;
        }
        .submit-btn {
            width: 100%;
            background: #1d2127;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 14px 24px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .submit-btn:hover {
            background: #334155;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="dashboard-card">
        <h1>Crear Nueva Postulación</h1>

        <form action="guardar.php" method="POST">
            
            <div class="form-group">
                <label>Iniciativa:</label>
                <select name="id_iniciativa" required>
                    <option value="">-- Seleccione --</option>
                    <?php while($i = $res_iniciativas->fetch_assoc()): ?>
                        <option value="<?php echo $i['id_iniciativa']; ?>"><?php echo $i['nombre_iniciativa']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Empresa Responsable:</label>
                <select name="empresa" required>
                    <option value="">-- Seleccione una empresa registrada --</option>
                    <?php while($e = $res_empresas->fetch_assoc()): ?>
                        <option value="<?php echo $e['rut_empresa']; ?>">
                            <?php echo $e['nombre_empresa']; ?> (<?php echo $e['rut_empresa']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Sede:</label>
                <select name="sede" required>
                    <option value="">-- Seleccione --</option>
                    <?php while($s = $res_sedes->fetch_assoc()): ?>
                        <option value="<?php echo $s['id_sede']; ?>"><?php echo $s['nombre_sede']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Presupuesto:</label>
                <input type="number" name="presupuesto" placeholder="Ej: 500000" required>
            </div>

            <input type="hidden" name="estado" value="5"> 
            <input type="hidden" name="region_or" value="1">
            <input type="hidden" name="region_imp" value="1">
            <input type="hidden" name="responsable" value='33.333.333-3'> 
            <input type="hidden" name="tipoiniciativa" value="1">

            <div class="btn-container">
                <button type="submit" class="submit-btn">Crear Proyecto</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>