<?php
session_start();
$url_base="https://correagro.com/saecipa/admin/";
if(!isset($_SESSION['usuario'])){
    header("Location:".$url_base."index.php");
}
?>

<!doctype html>
<html lang="es">

<head>
    <title>SAE ORF</title>
    <!-- Favicon-->
    <!--<link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />-->
    <link rel="SHORTCUT ICON" href="https://www.correagro.com/wp-content/uploads/2021/03/logo-150x150.png">
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    
    <!--Jquery-->
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>

    <!--Datatables - type="txt/css"-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
  
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    
    <!--SweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body>
  <header>
    <!-- place navbar here -->
    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link active" href="<?php echo $url_base; ?>home.php" aria-current="page">Inicio</a>
            <!--<a class="nav-item nav-link active" href="../../index.php" aria-current="page">Comprobantes <span class="visually-hidden">(current)</span></a>-->
            <a class="nav-item nav-link active" href="<?php echo $url_base; ?>novedades.php" aria-current="page">Novedades <span class="visually-hidden">(current)</span></a>
            
            <a class="nav-item nav-link" href="<?php echo $url_base;?>cerrar.php">Cerrar Sesion</a>
        </div>
        
    </nav>

  </header>
  <main class="container">
    <br/>
    <script>
        <?php if (isset($_GET['mensaje'])){ ?>
        Swal.fire({icon:"success", title:"<?php echo $_GET['mensaje'];?>"});
        <?php } ?>
    </script>