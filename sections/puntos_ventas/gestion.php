<?php
include("../../db.php");

$txtID = (isset($_GET['pv'])) ? $_GET['pv'] : "";
if (isset($_GET['txtID'])) {
$txtDID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("DELETE FROM puntoventa_bodega WHERE id=:id");
    $sentencia->bindParam(":id", $txtDID);
    $sentencia->execute();

    $mensaje="Registro eliminado";
    $icono="success";
    header("Location:gestion.php?pv=".$txtID."&mensaje=".$mensaje."&icono=".$icono);
}

$sql = $conexion->prepare("SELECT 
pvb.id,
pvb.id_puntoventa, pv.nombre as pv_nombre,
pvb.id_cilindros, c.capacidad_libras as cili_libras,
pvb.cantidad,
pvb.fecha_abastecimiento,
pvb.nota
FROM zgas.puntoventa_bodega pvb
INNER JOIN puntos_ventas pv on pv.id=pvb.id_puntoventa
INNER JOIN cilindros c on c.id=pvb.id_cilindros
WHERE pvb.id_puntoventa=$txtID;"); 
$sql->execute();
$lista_puntosventas = $sql->fetchAll(PDO::FETCH_ASSOC);

$sqlTotal = $conexion->prepare("SELECT 
SUM(cantidad) as total
FROM zgas.puntoventa_bodega pvb
WHERE pvb.id_puntoventa=$txtID;"); 
$sqlTotal->execute();
$totalCilindros = $sqlTotal->fetch(PDO::FETCH_ASSOC);

$sqlIdpv = $conexion->prepare("SELECT 
SUM(cantidad) as total
FROM zgas.puntoventa_bodega pvb
WHERE pvb.id_puntoventa=$txtID;"); 
$sqlTotal->execute();
$totalCilindros = $sqlTotal->fetch(PDO::FETCH_ASSOC);




?>
<?php include_once '../../templates/header.php'; ?>

<br>
<h3>Bodega del punto de venta</h3>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="gestioncrear.php?pv=<?php echo $_GET['pv']; ?>" role="button">Agregar nuevo registro al inventario</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre del PV</th>
                        <th scope="col">Capacidad de libras del cilindro</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Fecha de abastecimiento</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_puntosventas as $registro) { ?>
                        <tr class="">
                            <td><?php echo $registro['id']; ?></td>
                            <td>
                                <?php echo ucwords($registro['pv_nombre']); ?>
                            </td>
                            <td>
                                <?php echo ucwords($registro['cili_libras']); ?>
                            </td>
                            <td>
                                <?php echo ucwords($registro['cantidad']); ?>
                            </td>
                            <td>
                                <?php echo ucwords($registro['fecha_abastecimiento']); ?>
                            </td>
                            <td>
                                <a class="btn btn-info" href="gestiondetalles.php?pv=<?php echo $registro['id_puntoventa']; ?>&txtID=<?php echo $registro['id']; ?>" role="button">Detalles</a>
                                <a class="btn btn-danger" href="javascript:borrarRegistro(<?php echo $registro['id_puntoventa']; ?>,<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <hr>
            <div class="alert alert-primary" role="alert">
            <h4>Total de cilindros: <?php echo ($totalCilindros['total']); ?></h4>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../templates/footer.php'; ?>