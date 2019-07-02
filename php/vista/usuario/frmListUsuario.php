<?php
require_once("../../modelo/usuario/moUsuario.php");
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../../../global.php");

session_start();

global $VGtamanoPagina;
/*
$_SESSION['idUsuario']=1;//TEMPORAL hasta q se loguee, 1 es el id del ADMIN
$_SESSION['coacUsuario']='ANDALUCI';//TEMPORAL
*/
//--------------------------------------------Usuario no autorizado
//if (!isset($_SESSION['idUsuario']) || $_SESSION["rolUsuario"]!='ADMIN') {    
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../../login");
    exit;
}
if ($_SESSION["rolUsuario"]!='ADMIN') {    
    header("Location: ../../../403");
    exit;
}


$usuario=new usuario(-1);
//$usuario->getAllPaginadoCoac($VGtamanoPagina, 0, $_SESSION['coacUsuario']);
$usuario->getAllCoac($_SESSION['coacUsuario']);
$arrUsuario=$usuario->arregloUsuario;

/*$categoria=new catalogo(1,'');
$arrCategoria=$categoria->arregloCatalogos;

$ciudad=new catalogo(2,'');
$arrCiudad=$ciudad->arregloCatalogos;*/
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Microempresarios &bull; eCommerce</title>

    <!-- Bootstrap -->
    <link href="../../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../../../vendors/nprogress/nprogress.css" rel="stylesheet">
    
    <!-- Custom styling plus plugins -->
    <link href="../../../build/css/custom.min.css" rel="stylesheet">
    
    <!-- Datatables -->
    <link href="../../../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    
    <link href="../../../css/estilo.css" rel="stylesheet">
    
    <script language="javascript" type="text/javascript" src="../../../js/global.js"></script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
                <a href="../inicio" class="site_title"><i class="fa fa-paw"></i> <span>eCommerce</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="../../../../img/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Bienvenido,</span>
                <h2><?php echo $_SESSION['nombreUsuario'].' '.$_SESSION['apellidoUsuario'];?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->
            <div class="clearfix"></div>

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">                
                <ul class="nav side-menu">
                  <li><a href="../inicio"><i class="fa fa-home"></i> Inicio</a></li>
                  <li><a href="../empresa/frmWizardNewEmpresa"><i class="fa fa-magic"></i> Asistente</a></li>
                  <li><a><i class="fa fa-suitcase"></i> Microempresas <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="../empresa/frmListEmpresa">Lista de Microempresas</a></li>
                      <li><a href="../empresa/frmNewEmpresa">Nueva Microempresa</a></li>
                    </ul>
                  </li>
                  <!--<li><a><i class="fa fa-edit"></i> Productos <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="javascript:;">Lista de Productos</a></li>
                      <li><a href="javascript:;">Nuevo Producto</a></li>
                    </ul>
                  </li>-->
                  <li class="active"><a><i class="fa fa-user"></i> Usuarios <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="current-page"><a href="frmListUsuario">Lista de Usuarios</a></li>
                      <li><a href="frmNewUsuario">Nuevo Usuario</a></li>
                    </ul>
                  </li>
                  <li><a href="../suscriptor/frmListSuscriptor"><i class="fa fa-envelope"></i> Suscriptores</a></li>
                  <!--<li><a><i class="fa fa-bar-chart-o"></i> Estad&iacute;sticas <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="javascript:;">Visitantes</a></li>
                      <li><a href="javascript:;">Top Microempresas</a></li>
                    </ul>
                  </li>-->
                  <!--<li class="disabled"><a><i class="fa fa-file-text-o"></i>Reportes <span class="fa fa-chevron-down"></span><span class="label label-success pull-right">Pronto</span></a>
                    <ul class="nav child_menu">
                      <li class="disabled"><a>Productos <span class="label label-success pull-right">Pronto</span></a></li>
                      <li class="disabled"><a>Microempresas <span class="label label-success pull-right">Pronto</span></a></li>
                    </ul>
                  </li>-->
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="../../../../img/img.jpg" alt=""><?php echo $_SESSION['nombreUsuario'].' '.$_SESSION['apellidoUsuario'];?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="frmProfileUsuarioAdmin"> Perfil</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-green pull-right">Pronto</span>
                        <span>Preferencias</span>
                      </a>
                    </li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-green pull-right">Pronto</span>
                        <span>Ayuda</span>
                      </a>
                    </li>
                    <li><a href="javascript:salir();"><i class="fa fa-sign-out pull-right"></i> Salir</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">2</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                          <span class="image"><img src="../../../img/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>Juan Pérez</span>
                          <span class="time">hace 2 min</span>
                        </span>
                        <span class="message">
                          Pronto... aqu&iacute; los mensajes y pedidos de los usuarios a los microempresarios...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="../../../img/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>Andres Reyes</span>
                          <span class="time">hace 15 min</span>
                        </span>
                        <span class="message">
                          Pronto... aqu&iacute; los mensajes y pedidos de los usuarios a los microempresarios...
                        </span>
                      </a>
                    </li>
                    
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>Ver todos los Mensajes</strong> <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3> Lista de Usuarios <small></small> </h3>
              </div>              
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <!--<div class="col-md-9" id="list-user">
                <div class="x_panel">                  
                  <div class="x_content">
                      <p>Seleccione el Usuario para Editar</p>
                      <ul class="list-unstyled msg_list">
                            <?php
                                if(count($arrUsuario)>0)
                                {
                                    for($i=0;$i<count($arrUsuario);$i++)
                                    {
                                        if($arrUsuario[$i]->us_estado=='ACTIV') $opacity='1;';
                                        else $opacity='0.22;';
                            ?>
                                        <li>
                                          <a href="frmEditUsuarioAdmin?us_id=<?php echo $arrUsuario[$i]->us_id;?>">
                                            <span class="image">
                                                <img style="width:64px;height:64px;opacity:<?php echo $opacity;?>" src="../../../../img/user3.jpg" alt="img" />                                                
                                            </span>
                                            <span>
                                                <span style="font-size: 11px;"><strong>Nombres: </strong><?php echo $arrUsuario[$i]->us_apellido.' '.$arrUsuario[$i]->us_nombre;?></span>
                                              <span class="time"><?php echo date('d/m/Y H:i',strtotime($arrUsuario[$i]->us_fec_ini));?></span>
                                            </span>
                                            <span class="message">
                                                <strong>Usuario: </strong><?php echo $arrUsuario[$i]->us_mail;?>
                                                <br /><strong>Clave: </strong><?php echo $arrUsuario[$i]->us_sin_encriptar;?>
                                                <br /><strong>Id: </strong><?php echo $arrUsuario[$i]->us_id;?>                                                
                                            </span>
                                          </a>
                                        </li>
                                    <?php
                                    }
                                }    
                              ?>                                
                      </ul>
                  </div>
                </div>
              </div>-->
              
                
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <!--<p class="text-muted font-13 m-b-30">
                      Seleccione el Usuario con <span class="text-info">doble click</span> para Editar.
                    </p>-->
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-hover" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Nombre</th>
                          <!--<th>Descripción</th>
                          <th>Dirección</th>-->
                          <th>Apellido</th>
                          <th>Usuario</th>
                          <th>Clave</th>
                          <th>Fecha Alta</th>
                          <th>Estado</th>
                          <th>...</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if(count($arrUsuario)>0)
                        {
                            for($i=0;$i<count($arrUsuario);$i++)
                            {
                                $estado=new catalogo(3,$arrUsuario[$i]->us_estado);
                                /*if($arrUsuario[$i]->us_estado=='ACTIV') $opacity='1;';
                                else $opacity='0.22;';*/
                                
                                //echo '<tr style="cursor:pointer;" ondblclick="window.open(\'frmEditUsuarioAdmin?us_id='.$arrUsuario[$i]->us_id.'\',\'_self\')">';
                                echo '<tr>';
                                    echo '<td>'.$arrUsuario[$i]->us_id.'</td>';
                                    echo '<td>'.$arrUsuario[$i]->us_nombre.'</td>';
                                    echo '<td>'.$arrUsuario[$i]->us_apellido.'</td>';
                                    echo '<td>'.$arrUsuario[$i]->us_mail.'</td>';
                                    echo '<td>'.$arrUsuario[$i]->us_sin_encriptar.'</td>';                                    
                                    echo '<td>'.date("d/m/Y",  strtotime($arrUsuario[$i]->us_fec_ini)).'</td>';
                                    echo '<td>'.$estado->ca_descripcion.'</td>';
                                    echo '<td><button class="btn-sm btn-success" onclick="window.open(\'frmEditUsuarioAdmin?us_id='.$arrUsuario[$i]->us_id.'\',\'_self\')">Editar</button></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            &copy; <?php echo date('Y');?> eCommerce Admin by <a href="http://www.geotelematica.com" target="_blank">GEOTELEMATICA</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <div id="overlay"></div>        
    <div id="mensaje">
        <div id="msjTxt"></div>
        <div id="imgClose" onclick="_('mensaje').style.visibility='hidden';_('overlay').style.visibility='hidden';">
            <img id="imgOpaca" style="width:12px;height:12px;" /> <!--src="img/eliminar_b.png" /-->
        </div>
    </div>
    <div id="formulario"></div>
    
    <div id="cargando">
        <div id="imgCentrada"><img src="../../../img/loading1.gif" /></div>
        <div id="progstat"></div>
        <div id="progress"></div>
    </div>
    
    <!-- jQuery -->
    <script src="../../../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../../../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../../vendors/nprogress/nprogress.js"></script>
    
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../../../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../../../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../../../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../../../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../../../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    <script src="../../../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../../../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../../../vendors/pdfmake/build/vfs_fonts.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="../../../build/js/custom.min.js"></script>
    
    <!-- Datatables -->
    <script>
      $(document).ready(function() {
        $('#datatable-responsive').DataTable();
      });
    </script>
    <!-- /Datatables -->
    
    <script>
        $(window).load(function(){
            $('#cargando').fadeOut();
        });
    </script>
  </body>
</html>