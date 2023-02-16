<?php
include("../../db.php");
if(isset( $_GET['txtID'] )){
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";

    $sql=$conexion->prepare("SELECT * FROM cilindros WHERE id=:id");
    $sql->bindParam(":id",$txtID);
    $sql->execute();
    $registro=$sql->fetch(PDO::FETCH_LAZY);

    $txtCID =$registro["id"];
    $capacidad_libras=$registro["capacidad_libras"];
    $descripcion=$registro["descripcion"];
}

if($_POST){
    $capacidad_libras=(isset($_POST["capacidad_libras"])?$_POST["capacidad_libras"]:"");
    $descripcion=(isset($_POST["descripcion"])?$_POST["descripcion"]:"");
    $txtCID=(isset($_POST["txtCID"])?$_POST["txtCID"]:"");
    $sql =$conexion->prepare("UPDATE cilindros set capacidad_libras=:capacidad_libras, descripcion=:descripcion WHERE id=:id;");
    $sql->bindParam(":capacidad_libras",$capacidad_libras);
    $sql->bindParam(":descripcion",$descripcion);
    $sql->bindParam(":id",$txtCID);
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
        Datos del cilindro
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="mb-3">
      <input type="hidden" value="<?php echo $txtCID;?>" class="form-control" readonly name="txtCID" id="txtCID" placeholder="ID">

        <div class="mb-3">
          <label for="capacidad_libras" class="form-label">Capacidad de libras</label>
          <input required value="<?php echo ($capacidad_libras); ?>" type="text"class="form-control" name="capacidad_libras" id="capacidad_libras" placeholder="25">
        </div>

        <div class="mb-3">
          <label for="descripcion" class="form-label">descripcion</label>
          <textarea required name="descripcion" id="descripcion" cols="30" rows="3" class="form-control"><?php echo ($descripcion); ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Editar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>
