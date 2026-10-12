<?php
include '../bdconex.php';


$n_postu    = $_POST['n_postulacion']; 
$rut        = $_POST['rut_int'];       
$nombre     = $_POST['nombre_int'];  
$email      = $_POST['email_int'];    
$depto      = $_POST['depto_int'];     
$tel        = $_POST['tel_int'];       
$rol        = $_POST['rol_int'];      
$sede       = $_POST['sede_int'];     

try {
   
    $sql = "INSERT INTO int_eq 
            (N_Postulacion, Rut_int, Nombre_int, Email_int, Departamento_int, Telefono_int, Rol_int, sede_int) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    
    $stmt->bind_param("isssssii", 
        $n_postu, $rut, $nombre, $email, $depto, $tel, $rol, $sede
    );
    
    if($stmt->execute()){
        
        header("Location: trabj.php?id=" . $n_postu);
        exit();
    }
} catch (Exception $e) {
    die("Error detallado: " . $e->getMessage()); 
}
?>