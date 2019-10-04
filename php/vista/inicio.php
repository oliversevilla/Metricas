<?php
//inicio sesion
session_start();
//si no inicio sesion redirecciona a pagina de login
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../login");
    exit;
}

//vars locales
$totTransaccion=450;////$transaccion->getTotal($_SESSION['idEmpresa'],'RTF');

$totCliente=8;////$cliente->getTotal();

$totalUsuario=50;
        
setlocale(LC_ALL,"es_ES");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Análisis de Métricas &bull; <?php echo $_SESSION['nombreUsuario'].' '.$_SESSION['apellidoUsuario'];?></title>

    <!-- Bootstrap -->
    <link href="../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../../vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- Custom Theme Style -->
    <link href="../../build/css/custom.min.css?v=2" rel="stylesheet">
    <link href="../../css/estilo.css" rel="stylesheet">
    
    <script language="javascript" type="text/javascript" src="../../js/OpenLayers.js"></script>
    <script language="javascript" type="text/javascript" src="../../js/empresa/empresa.js"></script>
    <script language="javascript" type="text/javascript" src="../../js/empresa/empresaFoto.js"></script>
    <script language="javascript" type="text/javascript" src="../../js/usuario/usuario.js"></script>
    <script language="javascript" type="text/javascript" src="../../js/global.js"></script>
    <script language="javascript" type="text/javascript" src="../../js/inicio.js"></script>
    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
                <a href="inicio" class="site_title"><i class="fa fa-bar-chart"></i> <span style="font-size: 15px;">Análisis de Métricas</span></a>
            </div>

            <div class="clearfix"></div>
            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="../../img/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Bienvenido</span>
                <h2><?php echo $_SESSION['apellidoUsuario'];?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->
            <div class="clearfix"></div>
            
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">                
                <ul class="nav side-menu" style="font-size:13px;">
                  <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
                  <li><a><i class="fa fa-bar-chart"></i> Calcular Métricas <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="meta/frmBddMeta">Desde un archivo</a></li>
                      <li><a href="meta/frmNewMeta">Desde una URL</a></li>                      
                    </ul>
                  </li>
                  <li><a href="oa/frmListOA"><i class="fa fa-book"></i> Repositorios Analizados</a></li>
                  <li><a href="oa/frmRptOA"><i class="fa fa-file-o"></i> Reporte</a></li>
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
                    <img src="../../img/img.jpg" alt=""><?php echo $_SESSION['nombreUsuario'].' '.$_SESSION['apellidoUsuario'];?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-green pull-right">Próximamente</span>
                        <span>Ayuda</span>
                      </a>
                    </li>
                    <li><a href="javascript:salirIndex();"><i class="fa fa-sign-out pull-right"></i> Salir</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-suitcase"></i> OAs evaluados</span>
              <div class="count"><?php echo number_format($totTransaccion);?></div>
              <span class="count_bottom">Actualizado al <i class="green"><?php echo date('d-m-Y');?></i></span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Repositorios</span>
              <div class="count"><?php echo number_format($totCliente);?></div>
              <span class="count_bottom">Actualizado al <i class="green"><?php echo date('d-m-Y');?></i></span>
            </div>                       
          </div>
          <!-- /top tiles -->

          

          <div class="row">


            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Bienvenido</h2>                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <h4><?php echo $_SESSION['nombreUsuario'].' '.$_SESSION['apellidoUsuario'];?><br /><br />
                    <?php echo date('d-m-Y');?><br /><br />
                    <?php echo date('H:m');?>
                  </h4>
                </div>
              </div>
            </div>

            
            
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Pronóstico del tiempo en Quito</h2>                      
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="row">
                    <div class="col-sm-12">
                        <div class="temperature"><b><?php echo utf8_encode(strftime("%A %d de %B del %Y")).', '.date('g:i: A');?></b>                            
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="weather-icon">
                        <canvas height="84" width="84" id="partly-cloudy-day"></canvas>
                      </div>
                    </div>
                    <div class="col-sm-8">
                      <div class="weather-text">
                        <h2>Quito <br><i>Día Parcialmente Nublado</i></h2>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="weather-text pull-right">
                      <h3 class="degrees">23</h3>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <div class="row weather-days">
                    <div class="col-sm-2">
                      <div class="daily-weather">
                        <h2 class="day">Lun</h2>
                        <h3 class="degrees">22</h3>
                        <canvas id="clear-day" width="32" height="32"></canvas>
                        <h5>8 <i>km/h</i></h5>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="daily-weather">
                        <h2 class="day">Mar</h2>
                        <h3 class="degrees">12</h3>
                        <canvas height="32" width="32" id="rain"></canvas>
                        <h5>12 <i>km/h</i></h5>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="daily-weather">
                        <h2 class="day">Mié</h2>
                        <h3 class="degrees">9</h3>
                        <canvas height="32" width="32" id="snow"></canvas>
                        <h5>14 <i>km/h</i></h5>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="daily-weather">
                        <h2 class="day">Jue</h2>
                        <h3 class="degrees">11</h3>
                        <canvas height="32" width="32" id="sleet"></canvas>
                        <h5>12 <i>km/h</i></h5>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="daily-weather">
                        <h2 class="day">Vie</h2>
                        <h3 class="degrees">20</h3>
                        <canvas height="32" width="32" id="wind"></canvas>
                        <h5>21 <i>km/h</i></h5>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="daily-weather">
                        <h2 class="day">Sab</h2>
                        <h3 class="degrees">19</h3>
                        <canvas height="32" width="32" id="cloudy"></canvas>
                        <h5>10 <i>km/h</i></h5>
                      </div>
                    </div>
                    <div class="clearfix"></div>
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
            &copy; <?php echo date('Y');?> Análisis de Métricas por <a href="index" target="_blank">Oliver Sevilla</a>
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
        <div id="imgCentrada"><img src="../../img/loading1.gif" /></div>
        <div id="progstat"></div>
        <div id="progress"></div>
    </div>
    
    <!-- jQuery -->
    <script src="../../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../../vendors/nprogress/nprogress.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="../../vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- Chart.js -->
    <script src="../../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- iCheck -->
    <script src="../../vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="../../vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="../../vendors/Flot/jquery.flot.js"></script>
    <script src="../../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../../vendors/Flot/jquery.flot.time.js"></script>
    <script src="../../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../../vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="../../vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="../../vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="../../vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="../../vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="../../vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="../../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="../../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../../js/moment/moment.min.js"></script>
    <script src="../../js/datepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../../build/js/custom.min.js"></script>

    <!-- Flot -->
    <script>
      $(document).ready(function() {
        
        
      });
    </script>
    <!-- /Flot -->

    <!-- JQVMap -->
    
    <!-- /JQVMap -->

    <!-- Skycons -->
    <script>
      $(document).ready(function() {
        var icons = new Skycons({
            "color": "#73879C"
          }),
          list = [
            "clear-day", "clear-night", "partly-cloudy-day",
            "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
            "fog"
          ],
          i;

        for (i = list.length; i--;)
          icons.set(list[i], list[i]);

        icons.play();
      });
    </script>
    <!-- /Skycons -->

    
    <script>
        //Seteo y formateo de campos de texto, botones y otros elementos de interface
      $(document).ready(function(){
        var options = {
          legend: false,
          responsive: false
        };

        new Chart(document.getElementById("canvas1"), {
          type: 'doughnut',
          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
          data: {
            labels: [
              "Symbian",
              "Blackberry",
              "Otros",
              "IOS",
              "Android"
            ],
            datasets: [{
              data: [15, 20, 30, 10, 30],
              backgroundColor: [
                "#BDC3C7",
                "#9B59B6",
                "#E74C3C",
                "#26B99A",
                "#3498DB"
              ],
              hoverBackgroundColor: [
                "#CFD4D8",
                "#B370CF",
                "#E95E4F",
                "#36CAAB",
                "#49A9EA"
              ]
            }]
          },
          options: options
        });
      });
    </script>
    <!-- /Doughnut Chart -->
    
    <!-- bootstrap-daterangepicker -->
    
    <!-- /bootstrap-daterangepicker -->

    <!-- gauge.js -->
    <script>
      var opts = {
          lines: 12,
          angle: 0,
          lineWidth: 0.4,
          pointer: {
              length: 0.75,
              strokeWidth: 0.042,
              color: '#1D212A'
          },
          limitMax: 'false',
          colorStart: '#1ABC9C',
          colorStop: '#1ABC9C',
          strokeColor: '#F0F3F3',
          generateGradient: true
      };
      var target = document.getElementById('foo'),
          gauge = new Gauge(target).setOptions(opts);

      gauge.maxValue = <?php echo $totalUsuario;?>;
      gauge.animationSpeed = 32;
      gauge.set(<?php echo $totalUsuario - ($totalUsuario * (40/100));?>);
      gauge.setTextField(document.getElementById("gauge-text"));
      
      $(window).load(function(){
            $('#cargando').fadeOut();
        });
    </script>
    <!-- /gauge.js -->
    
    <script>
      
      
      $(window).load(function(){
            $('#cargando').fadeOut();
        });
    </script>
    <!-- /gauge.js -->
  </body>
</html>
