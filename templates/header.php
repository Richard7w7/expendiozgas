<?php
session_start();
$url_base="http://localhost/expendiozgas/";
if(!isset($_SESSION['usuario'])){
header("Location:".$url_base."login.php");
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
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<nav class="navbar navbar-expand navbar-light bg-light">
    <ul class="nav navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>index.php">Inicio</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>sections/usuarios/">Usuarios</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>sections/departamentos/">Departamentos</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>sections/municipios/">Municipios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>sections/puntos_ventas/">Puntos de venta</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>sections/cilindros/">cilindros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>cerrar.php">Cerrar sesión</a>
        </li>
        
    </ul>
</nav>

<body>

<main class="container">

<?php if(isset($_GET['mensaje'])&&isset($_GET['icono'])) {?>
    
    <script>
    Swal.fire({icon:"<?php echo $_GET['icono'];?>", title:"<?php echo $_GET['mensaje'];?>"});
    </script>
<?php } ?>