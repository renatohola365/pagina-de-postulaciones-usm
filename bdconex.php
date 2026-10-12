<?php

$servidor   = "localhost";
$usuario    = "postulaciones";
$password   = "TuClaveSegura"; 
$base_datos = "ct_usm_postulaciones"; 

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("La conexión falló: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>