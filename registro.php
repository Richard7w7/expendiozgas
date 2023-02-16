<?php
include("./db.php");
$sentencia=$conexion->prepare("SELECT * FROM roles WHERE rol = 'operador'");
$sentencia->execute();
$rol=$sentencia->fetch(PDO::FETCH_ASSOC);


if($_POST){

  $nombreCompleto=(isset($_POST["nombreCompleto"])?$_POST["nombreCompleto"]:"");
  $correo=(isset($_POST["correo"])?$_POST["correo"]:"");
  $contra=(isset($_POST["contra"])?$_POST["contra"]:"");
  $telefono=(isset($_POST["telefono"])?$_POST["telefono"]:"");
  $foto=(isset($_FILES["foto"]['name'])?$_FILES["foto"]['name']:"");
  $idrol=$rol['id'];
  if(!empty($nombreCompleto)&&!empty($correo)&&!empty($contra)&&!empty($telefono)){

    $nombreCompleto = strtolower($nombreCompleto);
    $correo = strtolower($correo);
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
      move_uploaded_file($tmp_foto,"sections/usuarios/fotos/".$nombreArchivo_foto);
    }
    $sql->bindParam(":foto",$nombreArchivo_foto);
    $sql->execute();
    $modificado = $sql->rowCount();

    if($modificado!=0){
      $mensaje="Registro exitoso";
      header("Location:login.php?mensaje=".$mensaje);
    }else{
      $mensaje="Registro fallido";
      header("Location:registro.php?mensaje=".$mensaje);
    }

  }else{

  }
}

?>
<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main class="container">
  <?php if(isset($_GET['mensaje'])) {?>
    <script>
    Swal.fire({icon:"error", title:"<?php echo $_GET['mensaje'];?>",text:"Por favor comuniquese con sistemas al correo sistemas@zgas.com.gt"});
    </script>
<?php } ?>
    <br>
  <div class="card">
    <div class="card-header">
        Datos del empleado
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="on">

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
      
        <button type="submit" class="btn btn-primary">Registrarme</button>
        <a name="" id="" class="btn btn-danger" href="login.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>
  </main>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>
