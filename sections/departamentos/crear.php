<?php
include("../../db.php");

if($_POST){

  $departamento=(isset($_POST["departamento"])?$_POST["departamento"]:"");
  if(!empty($departamento)){

    $departamento = strtolower($departamento);

    $existeDepartamento = $conexion->prepare("SELECT * FROM departamentos WHERE departamento=:departamento");
    $existeDepartamento->bindParam(":departamento", $departamento);
    $existeDepartamento->execute();
    $existeRegistro = $existeDepartamento->fetch(PDO::FETCH_LAZY);
    if($existeRegistro!= null){
    $mensaje="Departamento ya existe";
    $icono="error";
    header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
    }

    
    $sql=$conexion->prepare("INSERT INTO departamentos (departamento)
    VALUES (:departamento)");
    $sql->bindParam(":departamento",$departamento);
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
        Datos del departamento
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

        <div class="mb-3">
          <label for="departamento" class="form-label">Nombre del departamento</label>
          <input required type="text"class="form-control" name="departamento" id="departamento" placeholder="Escriba nombre del departamento">
        </div>

        
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>