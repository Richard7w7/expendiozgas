<?php
include("../../db.php");
$sentencia=$conexion->prepare("SELECT * FROM departamentos");
$sentencia->execute();
$list_departamentos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
if($_POST){

  $departamento=(isset($_POST["departamento"])?$_POST["departamento"]:"");
  $municipio=(isset($_POST["municipio"])?$_POST["municipio"]:"");
  
  if(!empty($departamento)&&!empty($municipio)){

    $municipio = strtolower($municipio);

    $existeMunicipio = $conexion->prepare("SELECT * FROM municipios WHERE id_departamento=:departamento AND municipio=:municipio");
    $existeMunicipio->bindParam(":departamento", $departamento);
    $existeMunicipio->bindParam(":municipio", $municipio);
    $existeMunicipio->execute();
    $existeRegistro = $existeMunicipio->fetch(PDO::FETCH_ASSOC);
    if(!empty($existeRegistro)){
    $mensaje="Relacion departamento y municipio ya existe";
    $icono="error";
    header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
    }else{
      $sql=$conexion->prepare("INSERT INTO municipios (id_departamento,municipio)
      VALUES (:id_departamento,:municipio)");
      $sql->bindParam(":id_departamento",$departamento);
      $sql->bindParam(":municipio",$municipio);
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
        Datos del municipio
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="mb-3">
            <label for="departamento" class="form-label">Departamento</label>
            <select class="form-select form-select-sm" name="departamento" id="departamento">
            <option value="" selected disabled>Seleccione un departamento</option>
            <?php foreach ($list_departamentos as $registro) { ?>    
              <option value="<?php echo $registro['id']?>">
              <?php echo ucwords($registro['departamento'])?>
            </option>
            <?php } ?>
            </select>
        </div>

        <div class="mb-3">
          <label for="municipio" class="form-label">Nombre del municipio</label>
          <input required type="text"class="form-control" name="municipio" id="municipio" placeholder="Escriba nombre del municipio">
        </div>

        
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>