<?php
include("../../db.php");
if(isset( $_GET['txtID'] )){
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";

    $sql=$conexion->prepare("SELECT * FROM departamentos WHERE id=:id");
    $sql->bindParam(":id",$txtID);
    $sql->execute();
    $registro=$sql->fetch(PDO::FETCH_LAZY);

    $txtDID =$registro["id"];
    $departamento=$registro["departamento"];
}

if($_POST){
    $departamento=(isset($_POST["departamento"])?$_POST["departamento"]:"");
    $txtDID=(isset($_POST["txtDID"])?$_POST["txtDID"]:"");
    $departamento = ucwords($departamento);
    $sql =$conexion->prepare("UPDATE departamentos set departamento=:departamento WHERE id=:id;");
    $sql->bindParam(":departamento",$departamento);
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
      header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
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
      <input type="hidden" value="<?php echo $txtDID;?>" class="form-control" readonly name="txtDID" id="txtDID" placeholder="ID">

        <div class="mb-3">
          <label for="departamento" class="form-label">Nombre del departamento</label>
          <input required value="<?php echo (ucwords($departamento)); ?>" type="text"class="form-control" name="departamento" id="departamento" placeholder="Escriba nombre del departamento">
        </div>

        
        <button type="submit" class="btn btn-primary">Editar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>
