<?php
require_once("global.php");

// Revisar que exista el modulo de pgsql
if (!extension_loaded('pgsql'))
{
  dl('pgsql.so');
}

session_start();

global $VGstrConexion;

// Conectar a la base de datos
$conn = pg_connect($VGstrConexion);
if (!$conn)
{
    echo '<center><div class="alert alert-danger alert-dismissable" style="padding:0;padding-top:0px;margin:0;border-radius:0;"><h3>Ocurri&oacute; un error al intentar conectarse a la base de datos</h3></div></center>';
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

if ($username=="admin") 
{
    if($password=="admin") 
    {
        $_SESSION["idUsuario"] = 1;
        $_SESSION["nombreUsuario"] = "DEMO";
        $_SESSION["apellidoUsuario"] = "";
        header("Location: php/vista/inicio");
    }
    else
        echo '<center><div class="alert alert-danger alert-dismissable" style="padding:0;padding-top:0px;margin:0;border-radius:0;"><h3>Usuario o Clave incorrectos</h3></div></center>';
}
//else session_destroy();//Esto en Log Out

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
    <link href="vendors//bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors//font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors//nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://colorlib.com/polygon/gentelella/css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build//css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="login" method="post">
              <h1>Log in</h1>
              <div>
                <input type="text" id="username" name='username' class="form-control" placeholder="Usuario" required="" value="<?php echo $username;?>" />
              </div>
              <div>
                  <input type="password" id="password" name='password' class="form-control" placeholder="Clave" required="" value="<?php echo $password;?>" />
              </div>              
              <div>                
                <button type="submit" class="btn btn-success">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Entrar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                <!--<button class="btn btn-default">&iquest;Olvid&oacute; su Clave?</button>-->                
              </div>
              <div class="clearfix"></div>

              <div class="separator">
                <!--<p class="change_link">No tiene Cuenta?
                  <a href="#signup" class="to_register" style="color:#4fbfa8;"> Crear una Cuenta </a>
                </p>-->

                <div class="clearfix"></div>
                <div>
                  <h1><i class="fa fa-bar-chart"></i> Métricas en Objetos de Análisis</h1>
                  <p>©2016 Todos los Derechos Reservados <a href="#">Oliver Sevilla</a></p>
                  <input type="text" id="tipo" name='tipo' class="login-control m-b-10" style="visibility:hidden;width:0px;height:0;"  value="ingreso">
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>