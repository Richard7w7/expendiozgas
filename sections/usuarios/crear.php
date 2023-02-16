<?php
include("../../db.php");
$sentencia=$conexion->prepare("SELECT * FROM `roles`");
$sentencia->execute();
$list_roles=$sentencia->fetchAll(PDO::FETCH_ASSOC);

if($_POST){

  $nombreCompleto=(isset($_POST["nombreCompleto"])?$_POST["nombreCompleto"]:"");
  $correo=(isset($_POST["correo"])?$_POST["correo"]:"");
  $contra=(isset($_POST["contra"])?$_POST["contra"]:"");
  $telefono=(isset($_POST["telefono"])?$_POST["telefono"]:"");
  $foto=(isset($_FILES["foto"]['name'])?$_FILES["foto"]['name']:"");
  $idrol=(isset($_POST["idrol"])?$_POST["idrol"]:"");
  if(!empty($nombreCompleto)&&!empty($correo)&&!empty($contra)&&!empty($telefono)&&!empty($idrol)){

    $nombreCompleto = strtolower($nombreCompleto);
    $correo = strtolower($correo);

    $existeUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE correo=:correo AND telefono=:telefono");
    $existeUsuario->bindParam(":correo", $correo);
    $existeUsuario->bindParam(":telefono", $telefono);
    $existeUsuario->execute();
    $existeRegistro = $existeUsuario->fetch(PDO::FETCH_LAZY);
    if($existeRegistro!= null){
    $mensaje="Ingrese otro correo y telefono";
    $icono="error";
    header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
    }

    $existeUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE correo=:correo");
    $existeUsuario->bindParam(":correo", $correo);
    $existeUsuario->execute();
    $existeRegistro = $existeUsuario->fetch(PDO::FETCH_LAZY);
    if($existeRegistro!= null){
    $mensaje="Ingrese otro correo";
    $icono="error";
    header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
    }

    $existeUsuario = $conexion->prepare("SELECT * FROM usuarios WHERE telefono=:telefono");
    $existeUsuario->bindParam(":telefono", $telefono);
    $existeUsuario->execute();
    $existeRegistro = $existeUsuario->fetch(PDO::FETCH_LAZY);
    if($existeRegistro!= null){
    $mensaje="Ingrese otro telefono";
    $icono="error";
    header("Location:crear.php?mensaje=".$mensaje."&icono=".$icono);
    }


    
    $contraHash = password_hash($contra, PASSWORD_BCRYPT);

    $sql=$conexion->prepare("INSERT INTO usuarios (id_rol,nombre_completo,correo,contra,telefono,foto)
    VALUES (:idRol,:nombrecompleto,:correo,:contra,:telefono,:foto)");
    $sql->bindParam(":idRol",$idrol);
    $sql->bindParam(":nombrecompleto",$nombreCompleto);
    $sql->bindParam(":correo",$correo);
    $sql->bindParam(":contra",$contraHash);
    $sql->bindParam(":telefono",$telefono);

    $fecha_=new DateTime();
    $nombreArchivo_foto=($foto!='')?$fecha_->getTimestamp()."_".$_FILES["foto"]['name']:"";
    $tmp_foto=$_FILES["foto"]['tmp_name'];
    if($tmp_foto!=''){
      move_uploaded_file($tmp_foto,"fotos/".$nombreArchivo_foto);
    }
    $sql->bindParam(":foto",$nombreArchivo_foto);
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
        Datos del usuario
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

        <div class="mb-3">
          <label for="nombreCompleto" class="form-label">Nombre completo</label>
          <input required type="text"class="form-control" name="nombreCompleto" id="nombreCompleto" placeholder="Escriba su nombre completo">
        </div>

        <div class="mb-3">
          <label for="correo" class="form-label">Correo electronico</label>
          <input required type="email"class="form-control" name="correo" id="correo" placeholder="usuario@ejemplo.com">
        </div>

        <div class="mb-3">
          <label for="contra" class="form-label">Contraseña</label>
          <input required type="password"class="form-control" name="contra" id="contra" placeholder="">
        </div>

        <div class="mb-3">
          <label for="telefono" class="form-label">Telefono</label>
          <input required type="text"class="form-control" name="telefono" id="telefono" placeholder="+502 12345678">
        </div>

        <div class="mb-3">
          <label for="foto" class="form-label">Fotografía</label>
          <input type="file"class="form-control" name="foto" id="foto" placeholder="Fotografía">
        </div>

        <div class="mb-3">
            <label for="idrol" class="form-label">Rol</label>
            <select class="form-select form-select-sm" name="idrol" id="idrol">
            <option value="" selected disabled>Seleccione un rol</option>
            <?php foreach ($list_roles as $registro) { ?>    
              <option value="<?php echo $registro['id']?>">
              <?php echo $registro['rol']?>
            </option>
            <?php } ?>
            </select>
        </div>

      

        <button type="submit" class="btn btn-primary">Registrar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>