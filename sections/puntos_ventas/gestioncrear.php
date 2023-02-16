<?php
include("../../db.php");
$sentencia = $conexion->prepare("SELECT * FROM cilindros");
$sentencia->execute();
$list_cilindros = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {

    $id_pv = (isset($_POST["txtDID"]) ? $_POST["txtDID"] : "");
    $idcilindro = (isset($_POST["idcilindro"]) ? $_POST["idcilindro"] : "");
    $cantidad = (isset($_POST["cantidad"]) ? $_POST["cantidad"] : "");
    $fecha_actual = date("Y-m-d");
    $nota = (isset($_POST["nota"]) ? $_POST["nota"] : "");
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
            header("Location:gestioncrear.php?pv=" . $id_pv . "&mensaje=" . $mensaje . "&icono=" . $icono);
        }else{
            $sql = $conexion->prepare("INSERT INTO puntoventa_bodega (id_puntoventa,id_cilindros,cantidad,fecha_abastecimiento,nota)
            VALUES (:idpv,:idcilindro,:cantidad,:fecha_abastecimiento,:nota)");
            $sql->bindParam(":idpv", $id_pv);
            $sql->bindParam(":idcilindro", $idcilindro);
            $sql->bindParam(":cantidad", $cantidad);
            $sql->bindParam(":fecha_abastecimiento", $fecha_actual);
            $sql->bindParam(":nota", $nota);
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
                <input type="hidden" value="<?php echo $_GET['pv']; ?>" class="form-control" readonly name="txtDID" id="txtDID" placeholder="ID">

                <div class="mb-3">
                    <label for="idcilindro" class="form-label">Tipo de Cilindro</label>
                    <select class="form-select form-select-sm" name="idcilindro" id="idcilindro" onchange="">
                        <option value="" selected disabled>Seleccione una capacidad</option>
                        <?php foreach ($list_cilindros as $registro) { ?>
                            <option value="<?php echo $registro['id'] ?>">
                                <?php echo ucwords($registro['capacidad_libras']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="cantidad" class="form-label">cantidad</label>
                    <input required type="number" class="form-control" name="cantidad" id="cantidad">
                </div>

                <div class="mb-3">
                    <label for="nota" class="form-label">Nota</label>
                    <textarea required name="nota" id="nota" cols="30" rows="10" class="form-control"></textarea>
                </div>


                <button type="submit" class="btn btn-primary">Registrar</button>
                <a name="" id="" class="btn btn-danger" href="gestion.php?pv=<?php echo $_GET['pv']; ?>" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted">
    </div>
</div>

<?php include_once '../../templates/footer.php'; ?>