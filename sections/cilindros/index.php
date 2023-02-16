<?php
include("../../db.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM puntoventa_bodega WHERE id_cilindros=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($registro_recuperado)){
        $mensaje="Imposible eliminar: cilindro tiene relaciones con puntos de ventas y bodegas";
        $icono="error";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
    }else{
        $sentencia = $conexion->prepare("DELETE FROM cilindros WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $mensaje="Registro eliminado";
        $icono="success";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
    }
}

$sql = $conexion->prepare("SELECT * FROM cilindros;");
$sql->execute();
$lista_cilindros = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include_once '../../templates/header.php'; ?>

<br>
<h3> Cilindros </h3>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar cilindros</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">capacidad de libras</th>
                        <th scope="col">descripción</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_cilindros as $registro) { ?>

                        <tr class="">
                            <td><?php echo $registro['id']; ?></td>
                            <td>
                                <?php echo $registro['capacidad_libras']; ?>
                            </td>
                            <td>
                                <?php echo $registro['descripcion']; ?>
                            </td>
                            <td>
                                <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../../templates/footer.php'; ?>