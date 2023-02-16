<?php
include("../../db.php");
$sentencia=$conexion->prepare("SELECT * FROM departamentos");
$sentencia->execute();
$list_departamentos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

$sentencia=$conexion->prepare("SELECT * FROM usuarios WHERE id_rol = 2");
$sentencia->execute();
$list_usuarios=$sentencia->fetchAll(PDO::FETCH_ASSOC);

if($_POST){

    $idDepartamento=(isset($_POST["idDepartamento"])?$_POST["idDepartamento"]:"");
    $idmunicipio=(isset($_POST["idmunicipio"])?$_POST["idmunicipio"]:"");
    $idusuario=(isset($_POST["idusuario"])?$_POST["idusuario"]:"");
    $nombrepv=(isset($_POST["nombrepv"])?$_POST["nombrepv"]:"");
    $nombrepv = strtolower($nombrepv);
    
    if(!empty($idDepartamento)&&!empty($idmunicipio)&&!empty($idusuario)&&!empty($nombrepv)){  
    $existePuntoVenta = $conexion->prepare("SELECT * FROM puntos_ventas 
    WHERE id_usuario=:usuario 
    AND id_departamento=:departamento 
    AND id_municipio=:municipio 
    AND nombre=:nombrepv");
    $existePuntoVenta->bindParam(":usuario", $idusuario);
    $existePuntoVenta->bindParam(":departamento", $idDepartamento);
    $existePuntoVenta->bindParam(":municipio", $idmunicipio);
    $existePuntoVenta->bindParam(":nombrepv", $nombrepv);
    $existePuntoVenta->execute();
    $existeRegistro = $existePuntoVenta->fetch(PDO::FETCH_ASSOC);
    if(!empty($existeRegistro)){
    $mensaje="Punto de venta ya existe";
    $icono="error";
    header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
    }else{
        $sql=$conexion->prepare("INSERT INTO puntos_ventas (id_usuario,id_departamento,id_municipio,nombre)
        VALUES (:id_usuario,:id_departamento,:id_municipio,:nombre)");
        $sql->bindParam(":id_usuario",$idusuario);
        $sql->bindParam(":id_departamento",$idDepartamento);
        $sql->bindParam(":id_municipio",$idmunicipio);
        $sql->bindParam(":nombre",$nombrepv);
        $sql->execute();
        $modificado = $sql->rowCount();
        if($modificado!=0){
        $mensaje="Registro exitoso";
        $icono="success";
        header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
        }else{
        $mensaje="Registro fallido";
        $icono="error";
        header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
        }
    }

    }else{

    }
}

?>
<?php include_once '../../templates/header.php'; ?>
<br>
<div class="card">
    <div class="card-header">
        Datos del puesto de venta
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

        <div class="mb-3">
            <label for="idDepartamento" class="form-label">Departamento</label>
            <select class="form-select form-select-sm" name="idDepartamento" id="idDepartamento" onchange="llenamunicipio();">
            <option value="" selected disabled>Seleccione un departamento</option>
            <?php foreach ($list_departamentos as $registro) { ?>    
            <option value="<?php echo $registro['id']?>">
            <?php echo ucwords($registro['departamento'])?>
            </option>
            <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="idmunicipio" class="form-label">Municipios</label>
            <select class="form-select form-select-sm" name="idmunicipio" id="idmunicipio">
            </select>
        </div>

        <div class="mb-3">
            <label for="idusuario" class="form-label">Operario</label>
            <select class="form-select form-select-sm" name="idusuario" id="idusuario">
            <option value="" selected disabled>Seleccione un operario</option>
            <?php foreach ($list_usuarios as $registro) { ?>    
            <option value="<?php echo $registro['id']?>">
            <?php echo ucwords($registro['nombre_completo'])?>
            </option>
            <?php } ?>
            </select>
        </div>

        <div class="mb-3">
        <label for="nombrepv" class="form-label">Nombre del punto de venta</label>
        <input required type="text"class="form-control" name="nombrepv" id="nombrepv" placeholder="Escriba el nombre del punto de venta">
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>
