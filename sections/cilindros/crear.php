<?php
include("../../db.php");

if($_POST){

  $capacidadLibras=(isset($_POST["capacidadLibras"])?$_POST["capacidadLibras"]:"");
  $descripcion=(isset($_POST["descripcion"])?$_POST["descripcion"]:"");
  if(!empty($capacidadLibras)&&!empty($descripcion)){

    $existeCilindro = $conexion->prepare("SELECT * FROM cilindros WHERE capacidad_libras=:capacidad_libras");
    $existeCilindro->bindParam(":capacidad_libras", $capacidadLibras);
    $existeCilindro->execute();
    $existeRegistro = $existeCilindro->fetch(PDO::FETCH_ASSOC);
    if($existeRegistro != null){
    $mensaje="cilindro ya existe";
    $icono="error";
    header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
    }

    
    $sql=$conexion->prepare("INSERT INTO cilindros (capacidad_libras,descripcion)
    VALUES (:capacidad_libras,:descripcion)");
    $sql->bindParam(":capacidad_libras",$capacidadLibras);
    $sql->bindParam(":descripcion",$descripcion);
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

  }else{

  }
}
?>
<?php include_once '../../templates/header.php'; ?>

  <br>
  <div class="card">
    <div class="card-header">
        Datos del cilindro
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

        <div class="mb-3">
          <label for="capacidadLibras" class="form-label">capacidad de libras</label>
          <input required type="text"class="form-control" name="capacidadLibras" id="capacidadLibras" placeholder="25">
        </div>

        <div class="mb-3">
          <label for="descripcion" class="form-label">descripcion</label>
          <textarea required name="descripcion" id="descripcion" cols="30" rows="3" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>