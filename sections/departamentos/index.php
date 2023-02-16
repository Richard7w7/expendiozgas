<?php
include("../../db.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM municipios WHERE id_departamento=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($registro_recuperado)){
        $mensaje="Imposible eliminar: Departamento tiene relaciones con municipios";
        $icono="error";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
    }else{
        $sentencia = $conexion->prepare("DELETE FROM departamentos WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
    
        $sentencia->execute();
    
        $mensaje="Registro eliminado";
        $icono="success";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
    }
}

$sql = $conexion->prepare("SELECT * FROM departamentos ORDER BY departamento ASC;");
$sql->execute();
$lista_departamentos = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include_once '../../templates/header.php'; ?>

<br>
<h3> Departamentos </h3>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar departamentos</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_departamentos as $registro) { ?>

                        <tr class="">
                            <td><?php echo $registro['id']; ?></td>
                            <td>
                                <?php echo ucwords($registro['departamento']); ?>
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