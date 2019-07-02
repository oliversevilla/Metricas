<?php
    session_start();
    //if (!isset($_SESSION['idUsuario']) || $_SESSION["rolUsuario"]!='ADMIN') {    
    if (!isset($_SESSION['idUsuario'])) {
        header("Location: login");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Métricas en Objetos de Análisis</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">403</h1>
              <h2>Acceso Denegado</h2>
              <p>Se requiere permisos de Administrador para acceder a este recurso</p>              
            </div>
          </div>
        </div>        
        <!-- /page content -->
        <center>
            <iframe src="//giphy.com/embed/zdxx6ABFpfTos" width="480" height="269" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><br /><br />
            <button class="btn btn btn-success" onclick="history.go(-1);">Vover a la p&aacute;gina anterior</button><br /><br />
            <a href="#">Métricas en Objetos de Análisis</a>              
        </center>
      </div>
    </div>

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>
  </body>
</html>