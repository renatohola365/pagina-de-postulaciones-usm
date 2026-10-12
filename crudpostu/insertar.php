<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../bdconex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_iniciativa   = !empty($_POST['id_iniciativa']) ? (int)$_POST['id_iniciativa'] : 0;
    $tipo_iniciativa = 1;
    $id_sede         = !empty($_POST['id_sede']) ? (int)$_POST['id_sede'] : 0;
    $id_region_or    = !empty($_POST['id_region_or']) ? (int)$_POST['id_region_or'] : 0;
    $id_region_imp   = !empty($_POST['id_region_imp']) ? (int)$_POST['id_region_imp'] : 0;
    
    $presupuesto     = !empty($_POST['presupuesto']) ? $_POST['presupuesto'] : '0.00';
    $rut_empresa     = !empty($_POST['empresa']) ? $_POST['empresa'] : null; 
    
    if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'Responsable Academico') {
        $id_estado = 5; 
    } else {
        $id_estado = !empty($_POST['id_estado']) ? (int)$_POST['id_estado'] : 1;
    }

    $fecha_actual = date('Y-m-d');

    $rut_evaluador_defecto = '22.222.222-2'; 
    $rut_responsable_defecto = $_SESSION['rut'] ?? null; 

    if ($id_iniciativa === 0 || $id_sede === 0 || $id_region_or === 0 || $id_region_imp === 0) {
        die("Error: Faltan campos obligatorios por seleccionar (Iniciativa, Sede o Regiones).");
    }

    $sql_insert = "INSERT INTO post_inicia (id_inicia, tipo_iniciativa_postu, id_sede_postu, Id_region_or, Id_region_imp, Presupuesto, rut_empresa_postu, id_estado_postu, Fecha_Post, rut_evaluador, rut_responsable) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql_insert);
    
    if ($stmt) {
        // CORRECCIÓN DE TIPOS EN BIND_PARAM:
        // id_inicia (i), tipo_iniciativa_postu (i), id_sede_postu (i), Id_region_or (i), Id_region_imp (i)
        // Presupuesto (s - por ser DECIMAL), rut_empresa_postu (s), id_estado_postu (i), Fecha_Post (s), rut_evaluador (s), rut_responsable (s)
        // Cadena de tipos resultante: "iiiiississs"
        
        $stmt->bind_param(
            "iiiiississs", 
            $id_iniciativa, 
            $tipo_iniciativa, 
            $id_sede, 
            $id_region_or, 
            $id_region_imp, 
            $presupuesto, 
            $rut_empresa, 
            $id_estado, 
            $fecha_actual, 
            $rut_evaluador_defecto,
            $rut_responsable_defecto
        );
        
        if ($stmt->execute()) {
            $nuevo_id = $conexion->insert_id;
            header("Location: trabj.php?id=" . $nuevo_id . "&mensaje=creado");
            exit();
        } else {
            echo "<h2>Error al ejecutar la inserción en la Base de Datos:</h2>";
            echo "<p>" . $stmt->error . "</p>";
            echo "<p>💡 <i>Tip: Verifica que el ID de iniciativa elegido, el RUT de la empresa y el RUT de la sesión existan en sus respectivas tablas.</i></p>";
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
} else {
    header("Location: ../paginas/pag_responsable.php");
    exit();
}
$conexion->close();
?>