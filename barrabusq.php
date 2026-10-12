<?php
include 'bdconex.php'; 

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$resultado=false;

if (!empty($busqueda)) {
    $sql = "SELECT p.*, i.Nombre_Iniciativa, e.Nombre_Empresa, r1.Nombre_Region, r2.Nombre_Region AS Region_Impacto, est.Nombre_Estado
            from post_inicia p
            JOIN empresa e ON  p.rut_empresa_postu = e.Rut_Empresa
            JOIN iniciativa i ON p.id_inicia = i.Id_iniciativa
            JOIN region r1 ON p.Id_region_or = r1.Id_region
            JOIN region r2 ON p.Id_region_imp = r2.Id_region
            JOIN estado_postulacion est ON p.id_estado_postu = est.Id_Estado
            WHERE i.Nombre_Iniciativa LIKE '%$busqueda%'
            OR e.Nombre_Empresa LIKE '%$busqueda%'
            OR Codigo LIKE '%$busqueda%'
            ";
            $resultado = mysqli_query($conexion, $sql);
} 


?>
<?php if ($resultado != false): ?>

    <table border="1" style="width: 100%; border-collapse: collapse;">
        <tr style="background-color: #1d2127; color: white;">
            <th>Numero</th>
            <th>Estado</th>
            <th>Presupuesto</th>
            <th>Nombre Iniciativa</th>
            <th>Nombre Empresa</th>
            <th>Region Origen</th>
            <th>Region Impacto</th>

        </tr>
        <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
        <tr>
            <td><?php echo $fila['N_Postulacion']; ?></td>
            <td><?php echo $fila['Nombre_Estado']; ?></td>
            <td><?php echo $fila['Presupuesto']; ?></td>
            <td><?php echo $fila['Nombre_Iniciativa']; ?></td>
            <td><?php echo $fila['Nombre_Empresa']; ?></td>
            <td><?php echo $fila['Nombre_Region']; ?></td>
            <td><?php echo $fila['Region_Impacto']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>