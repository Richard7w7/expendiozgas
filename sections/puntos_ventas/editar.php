<?php 
include("../../db.php");
$sentencia=$conexion->prepare("SELECT * FROM departamentos");
$sentencia->execute();
$list_departamentos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

$sentencia=$conexion->prepare("SELECT * FROM usuarios WHERE id_rol = 2");
$sentencia->execute();
$list_usuarios=$sentencia->fetchAll(PDO::FETCH_ASSOC);


if(isset( $_GET['txtID'] )){
    $txtDID=(isset($_GET['txtID']))?$_GET['txtID']:"";

    $sql=$conexion->prepare("SELECT pv.id, 
    pv.id_usuario, u.nombre_completo as usuario_nombre, 
    pv.id_departamento, d.departamento as depto_nombre,  
    pv.id_municipio, m.municipio as muni_nombre,
    pv.nombre 
    FROM zgas.puntos_ventas pv
    INNER JOIN usuarios u on u.id = pv.id_usuario
    INNER JOIN departamentos d on d.id = pv.id_departamento
    INNER JOIN municipios m on m.id = pv.id_municipio
    WHERE pv.id=:id");
    $sql->bindParam(":id",$txtDID);
    $sql->execute();
    $registro=$sql->fetch(PDO::FETCH_ASSOC);

    $txtDID =$registro["id"];
    $id_usuario=$registro["id_usuario"];
    $id_departamento=$registro["id_departamento"];
    $id_municipio=$registro["id_municipio"];
    $nombre=$registro["nombre"];

    $sentencia=$conexion->prepare("SELECT * FROM municipios where id_departamento='$id_departamento'");
$sentencia->execute();
$list_municipios=$sentencia->fetchAll(PDO::FETCH_ASSOC);
}

if($_POST){

  $txtDID=(isset($_POST["txtDID"])?$_POST["txtDID"]:"");
  $idDepartamento=(isset($_POST["idDepartamento"])?$_POST["idDepartamento"]:"");
  $idmunicipio=(isset($_POST["idmunicipio"])?$_POST["idmunicipio"]:"");
  $idusuario=(isset($_POST["idusuario"])?$_POST["idusuario"]:"");
  $nombrepv=(isset($_POST["nombrepv"])?$_POST["nombrepv"]:"");
  $nombrepv= strtolower($nombrepv);
  
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
  $mensaje="Relacion que intenta modificar ya existe";
  $icono="error";
  header("Location:editar.php?txtID=".$txtDID."&mensaje=".$mensaje."&icono=".$icono);
  }else{
    $sql =$conexion->prepare("UPDATE puntos_ventas
    SET id_usuario=:usuario,
    id_departamento=:departamento,
    id_municipio=:municipio,
    nombre=:nombre
    WHERE id=:id;");
    $sql->bindParam(":usuario",$idusuario);
    $sql->bindParam(":departamento",$idDepartamento);
    $sql->bindParam(":municipio",$idmunicipio);
    $sql->bindParam(":nombre",$nombrepv);
    $sql->bindParam(":id",$txtDID);
    $sql->execute();
    $modificado = $sql->rowCount();
    if($modificado!=0){
        $mensaje="Registro modificado";
        $icono="success";
      header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
      }else{
        $mensaje="Modificación fallida";
        $icono="error";
      header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
      }

  }
}

?>
<?php include_once '../../templates/header.php'; ?>
<br>
<div class="card">
    <div class="card-header">
        Datos del puesto de venta
        <hr>
        <a name="" id="" class="btn btn-info" href="gestion.php?pv=<?php echo $txtDID;?>" role="button">Gestionar bodega</a>

    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

      <div class="mb-3">
      <input type="hidden" value="<?php echo $txtDID;?>" class="form-control" readonly name="txtDID" id="txtDID" placeholder="ID">


        <div class="mb-3">
            <label for="idDepartamento" class="form-label">Departamento</label>
            <select class="form-select form-select-sm" name="idDepartamento" id="idDepartamento" onchange="llenamunicipio();">
            <option value="" selected disabled>Seleccione un departamento</option>
            <?php foreach ($list_departamentos as $registro) { ?>    
            <option value="<?php echo $registro['id']?>" <?php echo ($id_departamento == $registro['id'])?"selected":"";?>>
            <?php echo ucwords($registro['departamento'])?>
            </option>
            <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="idmunicipio" class="form-label">Municipios</label>
            <select class="form-select form-select-sm" name="idmunicipio" id="idmunicipio">
            <option value="" selected disabled>Seleccione un municipio</option>
            <?php foreach ($list_municipios as $registro) { ?>    
            <option value="<?php echo $registro['id']?>" <?php echo ($id_municipio == $registro['id'])?"selected":"";?>>
            <?php echo ucwords($registro['municipio'])?>
            </option>
            <?php } ?>
            
            </select>
        </div>

        <div class="mb-3">
            <label for="idusuario" class="form-label">Operario</label>
            <select class="form-select form-select-sm" name="idusuario" id="idusuario">
            <option value="" selected disabled>Seleccione un operario</option>
            <?php foreach ($list_usuarios as $registro) { ?>    
            <option value="<?php echo $registro['id']?>" <?php echo ($id_usuario == $registro['id'])?"selected":"";?>>
            <?php echo ucwords($registro['nombre_completo'])?>
            </option>
            <?php } ?>
            </select>
        </div>

        <div class="mb-3">
        <label for="nombrepv" class="form-label">Nombre del punto de venta</label>
        <input required value="<?php echo(ucwords($nombre)); ?>" type="text"class="form-control" name="nombrepv" id="nombrepv" placeholder="Escriba el nombre del punto de venta">
        </div>

        <button type="submit" class="btn btn-primary">Editar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>
