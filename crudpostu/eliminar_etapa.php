<?php
include_once __DIR__ . '/../bdconex.php';

if (!isset($_GET['n_etapa'], $_GET['postu'])) {
    header('Location: trabj.php?id=' . urlencode($_GET['postu'] ?? '') . '&error=params');
    exit();
}

$n_etapa = intval($_GET['n_etapa']);
$n_postu = intval($_GET['postu']);

if ($n_postu <= 0 || $n_etapa <= 0) {
    header('Location: trabj.php?id=' . $n_postu . '&error=param_invalid');
    exit();
}

$sql = 'DELETE FROM cronograma WHERE N_Postulacion = ? AND Etapa_Crono = ?';
if ($stmt = $conexion->prepare($sql)) {
    $stmt->bind_param('ii', $n_postu, $n_etapa);
    if ($stmt->execute()) {
        $stmt->close();
        header('Location: trabj.php?id=' . $n_postu . '&msg=etapa_eliminada');
        exit();
    }
    $stmt->close();
}

die('Error al eliminar la etapa: ' . htmlspecialchars($conexion->error, ENT_QUOTES, 'UTF-8'));
?>
