<?php
include("../../db.php");
if(isset( $_GET['txtID'] )){
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";

    $sql=$conexion->prepare("SELECT * FROM usuarios WHERE id=:id");
    $sql->bindParam(":id",$txtID);
    $sql->execute();
    $registro=$sql->fetch(PDO::FETCH_LAZY);

    $txtUID =$registro["id"];
    $id_rol=$registro["id_rol"];
    $nombre_completo=$registro["nombre_completo"];
    $correo=$registro["correo"];
    $telefono=$registro["telefono"];
    $foto=$registro["foto"];
    $cv=$registro["cv"];

    $sql=$conexion->prepare("SELECT * FROM roles");
    $sql->execute();
    $list_roles=$sql->fetchAll(PDO::FETCH_ASSOC);
}

if($_POST){

    $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
    $nombreCompleto=(isset($_POST["nombreCompleto"])?$_POST["nombreCompleto"]:"");
    $nombreCompleto = strtolower($nombreCompleto);
    $correo=(isset($_POST["correo"])?$_POST["correo"]:"");
    $telefono=(isset($_POST["telefono"])?$_POST["telefono"]:"");
    $idrol=(isset($_POST["idrol"])?$_POST["idrol"]:"");
    
    $sql=$conexion->prepare("UPDATE usuarios 
    SET 
    id_rol=:idrol,
    nombre_completo=:nombrecompleto,
    correo=:correo,
    telefono=:telefono
    WHERE id=:id
    ");
    
    $sql->bindParam(":idrol",$idrol);
    $sql->bindParam(":nombrecompleto",$nombreCompleto);
    $sql->bindParam(":correo",$correo);
    $sql->bindParam(":telefono",$telefono);
    $sql->bindParam(":id",$txtID);
    $sql->execute();
    
    $foto=(isset($_FILES["foto"]['name'])?$_FILES["foto"]['name']:"");
    $fecha_=new DateTime();
    $nombreArchivo_foto=($foto!='')?$fecha_->getTimestamp()."_".$_FILES["foto"]['name']:"";
    $tmp_foto=$_FILES["foto"]['tmp_name'];

    if($tmp_foto!=''){
        move_uploaded_file($tmp_foto,"fotos/".$nombreArchivo_foto);
        $sql=$conexion->prepare("SELECT foto FROM usuarios WHERE id=:id");
        $sql->bindParam(":id",$txtID);
        $sql->execute();
        $registro_recuperado=$sql->fetch(PDO::FETCH_LAZY);
        if( isset($registro_recuperado["foto"]) && $registro_recuperado["foto"]!="" ){
            if(file_exists("fotos/".$registro_recuperado["foto"])){
                unlink("fotos/".$registro_recuperado["foto"]);
            }
        }
        $sql=$conexion->prepare("UPDATE usuarios SET foto=:foto WHERE id=:id");
        $sql->bindParam(":foto",$nombreArchivo_foto);
        $sql->bindParam(":id",$txtID);
        $sql->execute();
    }

    $mensaje="Registro actualizado";
    $icono="success";
    header("Location:index.php?mensaje=".$mensaje."&icono=".$icono);
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
      <input type="hidden" value="<?php echo $txtUID;?>" class="form-control" readonly name="txtID" id="txtID" placeholder="ID">

        <div class="mb-3">
          <label for="nombreCompleto" class="form-label">Nombre completo</label>
          <input required value="<?php echo ucwords($nombre_completo);?>" type="text"class="form-control" name="nombreCompleto" id="nombreCompleto" placeholder="Escriba su nombre completo">
        </div>

        <div class="mb-3">
          <label for="correo" class="form-label">Correo electronico</label>
          <input required value="<?php echo $correo;?>" type="email"class="form-control" name="correo" id="correo" placeholder="usuario@ejemplo.com">
        </div>

        <div class="mb-3">
          <label for="telefono" class="form-label">Telefono</label>
          <input required value="<?php echo $telefono;?>" type="text"class="form-control" name="telefono" id="telefono" placeholder="+502 12345678">
        </div>

        <div class="mb-3">
          <label for="foto" class="form-label">Fotografía</label><br>
          <img width="25%" src="fotos/<?php echo $foto;?>" class="rounded" alt="">
          <br>
          <br>
          <input type="file"class="form-control" name="foto" id="foto" placeholder="Fotografía">
        </div>

        <div class="mb-3">
            <label for="idrol" class="form-label">Rol</label>
            <select class="form-select form-select-sm" name="idrol" id="idrol">
            <option value="" selected disabled>Seleccione un rol</option>
            <?php foreach ($list_roles as $registro) { ?>    
              <option value="<?php echo $registro['id']?>" <?php echo ($id_rol== $registro['id'])?"selected":"";?>>
              <?php echo $registro['rol']?>
            </option>
            <?php } ?>
            </select>
        </div>

      

        <button type="submit" class="btn btn-primary">Editar</button>
        <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">

    </div>
</div>
<?php include_once '../../templates/footer.php'; ?>
