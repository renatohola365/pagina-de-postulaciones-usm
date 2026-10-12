<?php
include '../bdconex.php';

$id_iniciativa  = $_POST['id_iniciativa']; 
$rut_empresa    = $_POST['empresa'];
$id_sede        = $_POST['sede'];
$n_postu        = $_POST['n_postulacion'] ?? null; 
$id_estado      = $_POST['estado'];
$id_region_or   = $_POST['region_or'];
$id_region_imp  = $_POST['region_imp'];
$presupuesto    = $_POST['presupuesto'];
$rut_evaluador  = !empty($_POST['evaluador']) ? $_POST['evaluador'] : '22.222.222-2';
$rut_responsable = $_POST['responsable'];
$tipo_iniciativa_postu = $_POST['tipoiniciativa'];

try {
    if ($n_postu) {
    
        $sql = "UPDATE post_inicia SET 
                    id_inicia = ?, 
                    rut_empresa_postu = ?, 
                    id_sede_postu = ?, 
                    id_estado_postu = ?, 
                    id_region_or = ?, 
                    id_region_imp = ?, 
                    rut_evaluador = ?, 
                    rut_responsable = ?,
                    presupuesto = ?,
                    tipo_iniciativa_postu = ?
                WHERE N_Postulacion = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isiiiissiii", 
            $id_iniciativa, $rut_empresa, $id_sede, $id_estado, 
            $id_region_or, $id_region_imp, $rut_evaluador, 
            $rut_responsable, $presupuesto,$tipo_iniciativa_postu, $n_postu
        );
    } else {
        $sql = "INSERT INTO post_inicia 
                (id_inicia, rut_empresa_postu, id_sede_postu, id_estado_postu, id_region_or, id_region_imp, rut_evaluador, rut_responsable, presupuesto, Fecha_Post,tipo_iniciativa_postu) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(),?)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isiiiissii", 
            $id_iniciativa, $rut_empresa, $id_sede, $id_estado, 
            $id_region_or, $id_region_imp, $rut_evaluador, 
            $rut_responsable, $presupuesto,$tipo_iniciativa_postu
        );
    }
if ($stmt->execute()) {
        $res_id = $n_postu ?: $conexion->insert_id;
        
    
        header("Location: trabj.php?msg=exito&id=" . $res_id);
    } else {
        throw new Exception($stmt->error);
    }

} catch (Exception $e) {
    die("Error en la operación: " . $e->getMessage());
}
?>