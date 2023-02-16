<?php
include("../../db.php");
$sentencia=$conexion->prepare("SELECT * FROM departamentos");
$sentencia->execute();
$list_departamentos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
if(isset( $_GET['txtID'] )){
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";

    $sql=$conexion->prepare("SELECT * FROM municipios WHERE id=:id");
    $sql->bindParam(":id",$txtID);
    $sql->execute();
    $registro=$sql->fetch(PDO::FETCH_LAZY);

    $txtDID =$registro["id"];
    $departamento=$registro["id_departamento"];
    $municipio=$registro["municipio"];
}

if($_POST){
  
  $txtDID=(isset($_POST["txtDID"])?$_POST["txtDID"]:"");
  $departamento=(isset($_POST["departamento"])?$_POST["departamento"]:"");
  $municipio=(isset($_POST["municipio"])?$_POST["municipio"]:"");
  $municipio= strtolower($municipio);
  
  $existeMunicipio = $conexion->prepare("SELECT * FROM municipios WHERE id_departamento=:departamento AND municipio=:municipio");
  $existeMunicipio->bindParam(":departamento", $departamento);
  $existeMunicipio->bindParam(":municipio", $municipio);
  $existeMunicipio->execute();
  $existeRegistro = $existeMunicipio->fetch(PDO::FETCH_ASSOC);
  if(!empty($existeRegistro)){
  $mensaje="Relacion departamento y municipio ya existe";
  $icono="error";
  header("Location:editar.php?txtID=".$txtDID."&mensaje=".$mensaje."&icono=".$icono);
  }else{
    $sql =$conexion->prepare("UPDATE municipios set id_departamento=:departamento, municipio=:municipio WHERE id=:id;");
    $sql->bindParam(":departamento",$departamento);
    $sql->bindParam(":municipio",$municipio);
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
        Datos del municipio
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="mb-3">
      <input type="hidden" value="<?php echo $txtDID;?>" class="form-control" readonly name="txtDID" id="txtDID" placeholder="ID">

      <div class="mb-3">
            <label for="departamento" class="form-label">Departamento</label>
            <select class="form-select form-select-sm" name="departamento" id="departamento">
            <option value="" selected disabled>Seleccione un departamento</option>
            <?php foreach ($list_departamentos as $registro) { ?>    
              <option value="<?php echo $registro['id']?>" <?php echo ($departamento == $registro['id'])?"selected":"";?>>
              <?php echo ucwords($registro['departamento'])?>
            </option>
            <?php } ?>
            </select>
        </div>

        <div class="mb-3">
          <label for="municipio" class="form-label">Nombre del municipio</label>
          <input required value="<?php echo (ucwords($municipio)); ?>" type="text"class="form-control" name="municipio" id="municipio" placeholder="Escriba nombre del municipio">
        </div>

        
        <button type="submit" class="btn btn-primary">Editar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>
