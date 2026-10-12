<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../bdconex.php';
include_once __DIR__ . '/borrarpost.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['n_postulacion'])) {
    $n_postulacion = intval($_POST['n_postulacion']);

    if (eliminarPostulacion($conexion, $n_postulacion)) {
        header("Location: ../paginas/pag_responsable.php?mensaje=eliminado_exito");
        exit();
    } else {
        echo "Error crítico: No se pudo eliminar la postulación de la base de datos.";
    }
} else {
    echo "Solicitud no válida.";
}
?>