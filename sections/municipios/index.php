<?php
include("../../db.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM puntos_ventas WHERE id_municipio=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($registro_recuperado)){
        $mensaje="Imposible eliminar: Municipio tiene relaciones con puntos de ventas";
        $icono="error";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
    }else{
        $sentencia = $conexion->prepare("DELETE FROM municipios WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
    
        $sentencia->execute();
    
        $mensaje="Registro eliminado";
        $icono="success";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
    }
}

$sql = $conexion->prepare("SELECT m.id, m.id_departamento, d.departamento as nombre_departamento, m.municipio 
FROM municipios m
INNER JOIN departamentos d on d.id = m.id_departamento
ORDER BY d.departamento ASC;");
$sql->execute();
$lista_municipios = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include_once '../../templates/header.php'; ?>

<br>
<h3> Municipios </h3>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar municipios</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_municipios as $registro) { ?>

                        <tr class="">
                            <td><?php echo $registro['id']; ?></td>
                            <td>
                                <?php echo ucwords($registro['nombre_departamento']); ?>
                            </td>
                            <td>
                                <?php echo ucwords($registro['municipio']); ?>
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