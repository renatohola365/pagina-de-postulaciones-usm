<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$rol = $_SESSION['cargo'] ?? '';
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$applicationRoot = realpath(__DIR__);
$basePath = '';

if ($documentRoot !== false && $applicationRoot !== false) {
    $documentRoot = rtrim($documentRoot, DIRECTORY_SEPARATOR);
    if ($applicationRoot !== $documentRoot && strpos($applicationRoot, $documentRoot . DIRECTORY_SEPARATOR) === 0) {
        $basePath = '/' . str_replace(DIRECTORY_SEPARATOR, '/', substr($applicationRoot, strlen($documentRoot) + 1));
    }
}
$basePath = htmlspecialchars($basePath, ENT_QUOTES, 'UTF-8');

?>

<style>body{background-color: #6a7473ff;;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        min-height: 100vh;}</style>


<nav style="background: #1d2127ff; padding: 15px; margin-bottom: 20px;">
    <a href="<?= $basePath ?>/index.php" style="color: white; margin-right: 20px; text-decoration: none; font-weight: bold;">Log out</a>
    <?php if (empty($rol) || (stripos($rol, 'admin') === false && stripos($rol, 'administrador') === false)): ?>
        <a href="<?= $basePath ?>/principal.php" style="color: white; margin-right: 20px; text-decoration: none;">Pagina Principal</a>
    <?php endif; ?>
    <a href="<?= $basePath ?>/view.php" style="color: white;margin-right: 20px; text-decoration: none;">Vistas</a>
    <a href="<?= $basePath ?>/crudpostu/creacion.php" style="color: white;margin-right: 20px; text-decoration: none;">Crear</a>

    <?php if ($rol == 'Responsable Academico'): ?>
        <a href="<?= $basePath ?>/paginas/pag_responsable.php" style="color: white; text-decoration: none;">Pagina Responsable</a>
    <?php endif; ?> 
    <?php if ($rol == 'Evaluador CT_USM'): ?>
        <a href="<?= $basePath ?>/paginas/pag_evaluador.php" style="color: white; text-decoration: none;">Pagina Evaluador</a>
    <?php endif; ?> 
    <?php if ($rol == 'Administrador CT_USM'): ?>
        <a href="<?= $basePath ?>/paginas/pag_admin.php" style="color: white; text-decoration: none;">Pagina Administrador</a>
    <?php endif; ?> 
</nav>