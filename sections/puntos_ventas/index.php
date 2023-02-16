<?php
include("../../db.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("DELETE FROM puntoventa_bodega WHERE id_puntoventa=:id");
    $sentencia->bindParam(":id", $txtID);

    $sentencia->execute();

    $sentencia = $conexion->prepare("DELETE FROM puntos_ventas WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);

    $sentencia->execute();


    $mensaje="Registro eliminado";
    $icono="success";
    header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
}

$sql = $conexion->prepare("SELECT pv.id, 
pv.id_usuario, u.nombre_completo as usuario_nombre, 
pv.id_departamento, d.departamento as depto_nombre,  
pv.id_municipio, m.municipio as muni_nombre,
pv.nombre 
FROM zgas.puntos_ventas pv
INNER JOIN usuarios u on u.id = pv.id_usuario
INNER JOIN departamentos d on d.id = pv.id_departamento
INNER JOIN municipios m on m.id = pv.id_municipio;"); 
$sql->execute();
$lista_puntosventas = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include_once '../../templates/header.php'; ?>

<br>
<h3> Puntos de Venta (PV)</h3>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar puntos de venta</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre del PV</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Operario</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_puntosventas as $registro) { ?>

                        <tr class="">
                            <td><?php echo $registro['id']; ?></td>
                            <td>
                                <?php echo ucwords($registro['nombre']); ?>
                            </td>
                            <td>
                                <?php echo ucwords($registro['depto_nombre']); ?>
                            </td>
                            <td>
                                <?php echo ucwords($registro['muni_nombre']); ?>
                            </td>
                            <td>
                                <?php echo ucwords($registro['usuario_nombre']); ?>
                            </td>
                            <td>
                                <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Detalles</a>
                                <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../../templates/footer.php'; ?>