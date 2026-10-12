<?php 
include 'bdconex.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$condiciones=[];
$parametros=[];
$tipos="";

if (!empty($_GET['estado'])) {
    $condiciones[] = "p.id_estado_postu = ?";
    $parametros[] = $_GET['estado'];
    $tipos .= "i";
}

if (!empty($_GET['sede'])) {
    $condiciones[] = "p.id_sede_postu = ?";
    $parametros[] = $_GET['sede'];
    $tipos .= "i";
}
if (!empty($_GET['region_imp'])) {
    $condiciones[] = "p.id_region_imp = ?"; 
    $parametros[] = $_GET['region_imp'];
    $tipos .= "i"; 
}
if (!empty($_GET['region_or'])) {
    $condiciones[] = "p.id_region_or = ?"; 
    $parametros[] = $_GET['region_or'];
    $tipos .= "i"; 
}
if (!empty($_GET['tipo_iniciativa'])) {
    $condiciones[] = "p.tipo_iniciativa_postu = ?"; 
    $parametros[] = $_GET['tipo_iniciativa'];
    $tipos .= "i";
}
if (!empty($_GET['tam_empresa'])) {
    $condiciones[] = "e.tam_empresa = ?"; 
    $parametros[] = $_GET['tam_empresa'];
    $tipos .= "i";
}
if (!empty($_GET['Convenio'])) {
    $condiciones[] = "e.Convenio = ?"; 
    $parametros[] = $_GET['Convenio'];
    $tipos .= "i";
}
if (!empty($_GET['evaluador'])) {
    $condiciones[] = "p.rut_evaluador = ?"; 
    $parametros[] = $_GET['evaluador'];
    $tipos .= "s";
}

$sql = "SELECT 
            p.N_Postulacion, 
            e.Nombre_Empresa, 
            i.Nombre_Iniciativa, 
            est.Nombre_Estado, 
            s.Nombre_sede,
            r1.Nombre_Region AS Region_Origen,
            r2.Nombre_Region AS Region_Impacto,
            p.tipo_iniciativa_postu AS Tipo_Iniciativa,
            e.tam_empresa AS Tamaño_Empresa,
            e.Convenio,
            w.rut AS Rut_Evaluador
        FROM post_inicia p
        JOIN empresa e ON p.rut_empresa_postu = e.Rut_Empresa
        JOIN iniciativa i ON p.id_inicia = i.Id_iniciativa
        JOIN sede s ON p.id_sede_postu = s.id_sede
        JOIN estado_postulacion est ON p.id_estado_postu = est.Id_Estado
        JOIN region r1 ON p.id_region_or = r1.Id_region
        JOIN region r2 ON p.id_region_imp= r2.Id_region
        JOIN web_usuarios w ON p.rut_evaluador = w.rut";

if (count($condiciones) > 0) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

$stmt = $conexion->prepare($sql);

if (!empty($parametros)) {
    $stmt->bind_param($tipos, ...$parametros);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador de Postulaciones</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-family: Arial, sans-serif; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #eee; }
        .filtros { background: #f9f9f9; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>

    <h2>Gestión de Postulaciones</h2>

    <div class="filtros">
        <form method="GET">
            <select name="evaluador">
                <option value="">-- Evaluador --</option>
                <?php
                $res_eval = $conexion->query("SELECT rut, nombre FROM web_usuarios WHERE cargo=2");
                if($res_eval){
                    while($u = $res_eval->fetch_assoc()) {
                        $sel = (isset($_GET['evaluador']) && $_GET['evaluador'] == $u['rut']) ? 'selected' : '';
                        echo "<option value='{$u['rut']}' $sel>{$u['nombre']}</option>";
        }
    }
    ?>
</select>
        <select name="region_or">
            <option value="">-- Region Origen --</option>
            <?php
            $res_reg = $conexion->query("SELECT Id_region, Nombre_Region FROM region");
            if($res_reg){
                while($r = $res_reg->fetch_assoc()) {
                    $sel = (isset($_GET['region_or']) && $_GET['region_or'] == $r['Id_region']) ? 'selected' : '';
                    echo "<option value='{$r['Id_region']}' $sel>{$r['Nombre_Region']}</option>";
        }
    }
    ?>
</select>
           <select name="Convenio">
            <option value="">-- Convenio --</option>
            <option value="1" <?php echo (isset($_GET['Convenio']) && $_GET['Convenio'] == '1') ? 'selected' : ''; ?>>Sí</option>
            <option value="0" <?php echo (isset($_GET['Convenio']) && $_GET['Convenio'] == '0') ? 'selected' : ''; ?>>No</option>
        </select>

            <select name="tipo_iniciativa">
                <option value="">-- Tipo Iniciativa --</option>
                <?php
                $res_ini = $conexion->query("SELECT Id_iniciativa, Nombre_Iniciativa FROM iniciativa");
                if($res_ini){
                    while($i = $res_ini->fetch_assoc()) {
                        $sel = (isset($_GET['tipo_iniciativa']) && $_GET['tipo_iniciativa'] == $i['Id_iniciativa']) ? 'selected' : '';
                        echo "<option value='{$i['Id_iniciativa']}' $sel>{$i['Nombre_Iniciativa']}</option>";
        }
    }
    ?>
</select>
           <select name="tam_empresa">
            <option value="">-- Tamaño empresa --</option>
            <?php
            $res_tam = $conexion->query("SELECT Id_tam, Tamaño_tipo FROM tamaño_empresa");
            while($t = $res_tam->fetch_assoc()) {
                $sel = (isset($_GET['tam_empresa']) && $_GET['tam_empresa'] == $t['Id_tam']) ? 'selected' : '';
                echo "<option value='{$t['Id_tam']}' $sel>{$t['Tamaño_tipo']}</option>";
            }
            ?>
             </select>
            <select name="region_imp">
            <option value="">-- Region Impacto --</option>
            <?php
            $res_reg_imp = $conexion->query("SELECT Id_region, Nombre_Region FROM region");
            while($r = $res_reg_imp->fetch_assoc()) {
                $sel = (isset($_GET['region_imp']) && $_GET['region_imp'] == $r['Id_region']) ? 'selected' : '';
                echo "<option value='{$r['Id_region']}' $sel>{$r['Nombre_Region']}</option>";
            }
            ?>
        </select>
           <select name="estado">
            <option value="">-- Estado --</option>
            <?php
            $res_est = $conexion->query("SELECT Id_Estado, Nombre_Estado FROM estado_postulacion");
            while($e = $res_est->fetch_assoc()) {
                $sel = (isset($_GET['estado']) && $_GET['estado'] == $e['Id_Estado']) ? 'selected' : '';
                echo "<option value='{$e['Id_Estado']}' $sel>{$e['Nombre_Estado']}</option>";
            }
            ?>
        </select>

            <select name="sede">
            <option value="">-- Sede --</option>
            <?php
            $res_sed = $conexion->query("SELECT id_sede, Nombre_sede FROM sede");
            while($s = $res_sed->fetch_assoc()) {
                $sel = (isset($_GET['sede']) && $_GET['sede'] == $s['id_sede']) ? 'selected' : '';
                echo "<option value='{$s['id_sede']}' $sel>{$s['Nombre_sede']}</option>";
            }
            ?>
        </select>

            <button type="submit">Buscar</button>
            <a href="?">Limpiar</a>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Iniciativa</th>
                <th>Estado</th>
                <th>Sede</th>
                <th>Region Origen</th>
                <th>Region Impacto</th>
                <th>Evaluador</th>
                <th>Tamaño Empresa</th>

            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['N_Postulacion']; ?></td>
                    <td><?php echo $row['Nombre_Iniciativa']; ?></td>
                    <td><?php echo $row['Nombre_Estado']; ?></td>
                    <td><?php echo $row['Nombre_sede']; ?></td>
                    <td><?php echo $row['Region_Origen']; ?></td>
                    <td><?php echo $row['Region_Impacto']; ?></td>
                    <td><?php echo $row['Rut_Evaluador']; ?></td>
                    <td><?php echo $row['Tamaño_Empresa']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>