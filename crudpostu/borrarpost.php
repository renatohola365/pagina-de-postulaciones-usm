<?php
function eliminarPostulacion($conexion, $n_postulacion) {
    $sql = "CALL pi_eliminar_postulacion(?)";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param('i', $n_postulacion);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
    }

    error_log("Error al ejecutar: " . $conexion->error);
    return false;
}
?>