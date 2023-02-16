<?php
session_start();
include("./db.php");
if ($_POST) {
    $correo = (isset($_POST["correo"]) ? $_POST["correo"] : "");
    $telefono = (isset($_POST["telefono"]) ? $_POST["telefono"] : "");
    $correo = strtolower($correo);
    $contrasenia = (isset($_POST["contrasenia"]) ? $_POST["contrasenia"] : "");

    if (!empty($correo) && !empty($telefono)) {

        $sql = $conexion->prepare("SELECT * FROM usuarios WHERE correo=:correo and telefono=:telefono");
        $sql->bindParam(":correo", $correo);
        $sql->bindParam(":telefono", $telefono);
        $sql->execute();
        $registro = $sql->fetch(PDO::FETCH_ASSOC);
        if (!empty($registro)) {
            header("Location:nuevacontra.php?id=".$registro['id']);
        } else {
            $mensaje = "Error: el usuario no existe o los datos son incorrectos";
        }
    } else {
        $mensaje = "Error: por favor llena todos los campos";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main class="container">
        <?php if (isset($_GET['mensaje'])) { ?>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "<?php echo $_GET['mensaje']; ?>",
                    text: "ingresa al sistema con tu correo y contraseña"
                });
            </script>
        <?php } ?>
        <br>
        <div class="row">
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Recuperación de contraseña
                    </div>
                    <div class="card-body">

                        <?php if (isset($mensaje)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <strong><?php echo $mensaje; ?></strong>
                            </div>
                        <?php } ?>

                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo</label>
                                <input required type="email" class="form-control" name="correo" id="correo" placeholder="Escriba su correo">
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Telefono</label>
                                <input required type="text" class="form-control" name="telefono" id="telefono" placeholder="">
                            </div>

                            <button type="submit" class="btn btn-primary">Verificar</button>
                            <a href="login.php" class="btn btn-danger">Cancelar</a>
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                    </div>
                </div>
            </div>
        </div>

    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>