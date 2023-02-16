<?php
include("../../db.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM puntos_ventas WHERE id_usuario=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($registro_recuperado)){
        $mensaje="Imposible eliminar: usuario tiene relaciones con puntos de ventas";
        $icono="error";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
    }else{
        $sentencia = $conexion->prepare("SELECT foto FROM usuarios WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);
    
        if (isset($registro_recuperado["foto"]) && $registro_recuperado["foto"] != "") {
            if (file_exists("fotos/" . $registro_recuperado["foto"])) {
                unlink("fotos/" . $registro_recuperado["foto"]);
            }
        }
    
        $sentencia = $conexion->prepare("DELETE FROM usuarios WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
    
        $sentencia->execute();
    
        $mensaje="Registro eliminado";
        $icono="success";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
    }


}

$sql = $conexion->prepare("SELECT u.id, u.id_rol,  r.rol as rol_nombre, u.nombre_completo,u.correo,u.telefono,u.foto 
FROM zgas.usuarios u
INNER JOIN roles r on r.id = u.id_rol;");
$sql->execute();
$lista_usuarios = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include_once '../../templates/header.php'; ?>

<br>
<h3> Usuarios </h3>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar usuarios</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Fotografía</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Telefeono</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_usuarios as $registro) { ?>

                        <tr class="">
                            <td><?php echo $registro['id']; ?></td>
                            <td>
                                <?php echo ucwords($registro['nombre_completo']); ?>
                            </td>
                            <td>
                                <img width="50" src="fotos/<?php echo $registro['foto']; ?>" class="img-fluid rounded" alt="" />
                            </td>
                            <td>
                                <?php echo $registro['correo']; ?>
                            </td>
                            <td><?php echo $registro['telefono']; ?> </td>
                            <td><?php echo $registro['rol_nombre']; ?> </td>
                            <td>
                                <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Detalles</a>
                                <?php if($_SESSION['id_usuario'] == $registro['id']) { ?>
                                <?php } else { ?>
                                    <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                <?php  } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../../templates/footer.php'; ?>