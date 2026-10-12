<?php
include '../bdconex.php';

$n_postu    = $_POST['n_postulacion'];
$etapa      = $_POST['etapa_crono'];
$entregable = $_POST['entregable_crono'];
$plazos     = $_POST['plazos_crono'];

try {
    $sql = "INSERT INTO cronograma (N_Postulacion, Etapa_Crono, Entregable_Crono, Plazos_Crono) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    $stmt->bind_param("iisi", $n_postu, $etapa, $entregable, $plazos);
    
    if($stmt->execute()){
        header("Location: trabj.php?id=" . $n_postu . "&msg=etapa_ok");
        exit();
    }
} catch (Exception $e) {
    die("Error al guardar en el cronograma: " . $e->getMessage());
}
?>