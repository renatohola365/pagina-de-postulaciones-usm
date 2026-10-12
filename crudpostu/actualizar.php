<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../bdconex.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $n_postulacion   = intval($_POST['n_postulacion']);
    $id_iniciativa   = intval($_POST['id_iniciativa']);
    $id_sede         = intval($_POST['id_sede']);
    $id_region_or    = intval($_POST['id_region_or']);  
    $id_region_imp   = intval($_POST['id_region_imp']); 
    $presupuesto     = intval($_POST['presupuesto']);
    $rut_empresa     = !empty($_POST['empresa']) ? $_POST['empresa'] : null;

    $query_actual = $conexion->query("SELECT id_estado_postu, tipo_iniciativa_postu FROM post_inicia WHERE N_Postulacion = $n_postulacion");
    $resultado_actual = $query_actual->fetch_assoc();

    $tipo_iniciativa = intval($resultado_actual['tipo_iniciativa_postu']);

    $accion = $_POST['accion'] ?? 'guardar';

    if ($accion === 'enviar') {
        $id_estado_nuevo = 1;
    } else {
        $id_estado_nuevo = 5;
    }

    if (actualizarPostulacion($conexion, $n_postulacion, $id_iniciativa, $tipo_iniciativa, $rut_empresa, $id_sede, $id_estado_nuevo, $id_region_or, $id_region_imp, $presupuesto)) {
        if ($accion === 'enviar') {
            header("Location: ../paginas/pag_responsable.php?mensaje=enviado_exito");
        } else {
            header("Location: trabj.php?id=" . $n_postulacion . "&mensaje=actualizado");
        }
        exit();
    } else {
        echo "Error: No se pudieron actualizar los datos de la postulación.";
    }
}

function actualizarPostulacion($conexion, $n_postulacion, $id_iniciativa, $tipo_iniciativa, $rut_empresa, $id_sede, $id_estado, $id_region_or, $id_region_imp, $presupuesto) {
    $sql = "UPDATE post_inicia SET 
                id_inicia = ?, 
                tipo_iniciativa_postu = ?, 
                rut_empresa_postu = ?, 
                id_sede_postu = ?, 
                id_estado_postu = ?, 
                Id_region_or = ?, 
                Id_region_imp = ?, 
                Presupuesto = ? 
            WHERE N_Postulacion = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param('iisiiiiii', 
            $id_iniciativa, $tipo_iniciativa, $rut_empresa, $id_sede, $id_estado, $id_region_or, $id_region_imp, $presupuesto, $n_postulacion
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        echo "Error al ejecutar la sentencia: " . $stmt->error;
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
    return false;
}
?>