<?php
include '../bdconex.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $n_postulacion = isset($_POST['n_postulacion']) ? intval($_POST['n_postulacion']) : 0;
    $id_estado_nuevo = isset($_POST['id_estado']) ? intval($_POST['id_estado']) : 0;

    if ($n_postulacion > 0 && $id_estado_nuevo > 0) {
        
        $sql = "UPDATE post_inicia SET id_estado_postu = ? WHERE N_Postulacion = ?";
        
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("ii", $id_estado_nuevo, $n_postulacion);
            if ($stmt->execute()) {
                header("Location: pag_evaluador.php?mensaje=actualizado");
                exit();
            } else {
                echo "Error al actualizar la base de datos: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conexion->error;
        }

    } else {
        echo "Datos inválidos o incompletos.";
    }
} else {
    header("Location: pag_evaluador.php");
    exit();
}
?>