<?php
session_start();
require 'bdconex.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$rut = trim($_POST['rut'] ?? '');
$pass = $_POST['pass'] ?? '';

$consulta = 'SELECT nombre, rut, contrasena, cargo FROM web_usuarios WHERE rut = ?';
$stmt = $conexion->prepare($consulta);
$stmt->bind_param('s', $rut);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();
$stmt->close();

if (!$fila || $pass !== $fila['contrasena']) {
    header('Location: index.php?error=credenciales');
    exit();
}

$destinos = [
    'Administrador CT_USM' => 'paginas/pag_admin.php',
    'Evaluador CT_USM' => 'paginas/pag_evaluador.php',
    'Responsable Academico' => 'paginas/pag_responsable.php',
];

if (!isset($destinos[$fila['cargo']])) {
    header('Location: index.php?error=credenciales');
    exit();
}

session_regenerate_id(true);
$_SESSION['nombre'] = $fila['nombre'];
$_SESSION['cargo'] = $fila['cargo'];
$_SESSION['autenticado'] = true;
$_SESSION['rut'] = $fila['rut'];

header('Location: ' . $destinos[$fila['cargo']]);
exit();
