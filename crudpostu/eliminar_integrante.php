<?php
include_once __DIR__ . '/../bdconex.php';

if (!isset($_GET['rut'], $_GET['postu'])) {
    header('Location: trabj.php?id=' . urlencode($_GET['postu'] ?? '') . '&error=params');
    exit();
}

$rut_postu = $_GET['rut'];
$n_postu = intval($_GET['postu']);

if ($n_postu <= 0 || trim($rut_postu) === '') {
    header('Location: trabj.php?id=' . $n_postu . '&error=param_invalid');
    exit();
}

$sql = 'DELETE FROM int_eq WHERE N_Postulacion = ? AND Rut_int = ?';
if ($stmt = $conexion->prepare($sql)) {
    $stmt->bind_param('is', $n_postu, $rut_postu);
    if ($stmt->execute()) {
        $stmt->close();
        header('Location: trabj.php?id=' . $n_postu . '&msg=integrante_eliminado');
        exit();
    }
    $stmt->close();
}

die('Error al eliminar el integrante: ' . htmlspecialchars($conexion->error, ENT_QUOTES, 'UTF-8'));
?>
