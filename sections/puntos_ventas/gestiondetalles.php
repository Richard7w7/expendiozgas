<?php
include("../../db.php");
$sentencia = $conexion->prepare("SELECT * FROM cilindros");
$sentencia->execute();
$list_cilindros = $sentencia->fetchAll(PDO::FETCH_ASSOC);
$id_pv = (isset($_GET["pv"]) ? $_GET["pv"] : "");
$txtID = (isset($_GET["txtID"]) ? $_GET["txtID"] : "");

if(isset( $_GET['txtID'] )){

    $sql=$conexion->prepare("SELECT * FROM puntoventa_bodega WHERE id=:id");
    $sql->bindParam(":id",$txtID);
    $sql->execute();
    $registro=$sql->fetch(PDO::FETCH_ASSOC);

    $txtID =$registro["id"];
    $id_puntoventa=$registro["id_puntoventa"];
    $id_cilindros=$registro["id_cilindros"];
    $cantidadregistro=$registro["cantidad"];
    $fecha_abastecimiento=$registro["fecha_abastecimiento"];
    $nota=$registro["nota"];
}

if ($_POST) {
    
    $id_pv = (isset($_POST["txtID"]) ? $_POST["txtID"] : "");
    $txtIDR = (isset($_POST["txtIDR"]) ? $_POST["txtIDR"] : "");
    $idcilindro = (isset($_POST["idcilindro"]) ? $_POST["idcilindro"] : "");
    $cantidad = (isset($_POST["cantidad"]) ? $_POST["cantidad"] : "");
    $fecha_actual = date("Y-m-d");
    $nota = (isset($_POST["nota"]) ? $_POST["nota"] : "");
    $total = $cantidadregistro+$cantidad;
    if (!empty($idcilindro) && !empty($cantidad)) {
        $existeRegistro = $conexion->prepare("SELECT * FROM puntoventa_bodega 
        WHERE 
        id_puntoventa=:idpunto AND
        id_cilindros=:idcilindro;");
        $existeRegistro->bindParam(":idpunto", $id_pv);
        $existeRegistro->bindParam(":idcilindro", $idcilindro);
        $existeRegistro->execute();
        $Boolregistro = $existeRegistro->fetch(PDO::FETCH_ASSOC);
        if (!empty($Boolregistro)) {
            $mensaje = "Registro ya existe";
            $icono = "error";
            header("Location:gestiondetalles.php?pv=" . $id_pv . "txtID=".$txtID."&mensaje=" . $mensaje . "&icono=" . $icono);
        }else{
            $sql = $conexion->prepare("UPDATE puntoventa_bodega 
            SET 
            cantidad=:cantidad,
            fecha_abastecimiento=:fecha_abastecimiento,
            nota=:nota
            WHERE id=:id;");
            $sql->bindParam(":cantidad", $total);
            $sql->bindParam(":fecha_abastecimiento", $fecha_actual);
            $sql->bindParam(":nota", $nota);
            $sql->bindParam(":id", $txtIDR);
            $sql->execute();
            $modificado = $sql->rowCount();
    
            if ($modificado != 0) {
                $mensaje = "Registro exitoso";
                $icono = "success";
                header("Location:gestion.php?pv=" . $_GET['pv'] . "&mensaje=" . $mensaje . "&icono=" . $icono);
            } else {
                $mensaje = "Registro fallido";
                $icono = "error";
                header("Location:gestion.php?pv=" . $_GET['pv'] . "&mensaje=" . $mensaje . "&icono=" . $icono);
            }
        }
    } else {
    }
}
?>

<?php include_once '../../templates/header.php'; ?>

<br>
<div class="card">
    <div class="card-header">
        Datos del registro
    </div>
    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

            <div class="mb-3">
            <input type="hidden" value="<?php echo $id_pv; ?>" class="form-control" readonly name="txtDID" id="txtDID" placeholder="ID">
            <input type="hidden" value="<?php echo $txtID; ?>" class="form-control" readonly name="txtIDR" id="txtIDR" placeholder="ID">

                <div class="mb-3">
                    <label for="idcilindro" class="form-label">Tipo de Cilindro</label>
                    <select class="form-select form-select-sm" name="idcilindro" id="idcilindro" onchange="">
                        <option value="" selected disabled>Seleccione una capacidad</option>
                        <?php foreach ($list_cilindros as $registro) { ?>
                            <option value="<?php echo $registro['id'] ?>" <?php echo ($id_cilindros== $registro['id'])?"selected":"";?>>
                                <?php echo ucwords($registro['capacidad_libras']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="cantidad" class="form-label">cantidad</label>
                    <input required value="<?php echo $cantidadregistro;?>" type="number" class="form-control" name="cantidad" id="cantidad">
                </div>

                <div class="mb-3">
                    <label for="nota" class="form-label">Nota</label>
                    <textarea required name="nota" id="nota" cols="30" rows="10" class="form-control"><?php echo $nota;?></textarea>
                </div>


                <button type="submit" class="btn btn-primary">Editar</button>
                <a name="" id="" class="btn btn-danger" href="gestion.php?pv=<?php echo $_GET['pv']; ?>" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>

<?php include_once '../../templates/footer.php'; ?>