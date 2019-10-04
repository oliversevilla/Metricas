<?php
//carga lib
require_once("../../../global.php");
//incia sesion
session_start();
//acceso a globales
global $VGtamanoPagina;
//si no inicio sesion redirecciona a pagina de login
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../../login");
    exit;
}
//vars locales
$em_id=1;////$empresa->em_id;
$em_nombre="UTPL";////$empresa->em_nombre;
$em_tipo_doc="";////$empresa->em_tipo_doc;
$em_nro_doc="";////$empresa->em_nro_doc;

?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Análisis de Métricas</title>
    
    
    <link href="../../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">        
    <link href="../../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="../../../vendors/select2/dist/css/select2.min.css" rel="stylesheet">    
    <link href="../../../build/css/custom.min.css" rel="stylesheet">
    <link href="../../../css/estilo.css" rel="stylesheet">
    <!-- JqPlot -->
    <link href="../../../css/jquery.jqplot.min.css" rel="stylesheet">
    <link href="../../../css/shCoreDefault.min.css" rel="stylesheet">
    <link href="../../../css/shThemejqPlot.min.css" rel="stylesheet">
    
    <!-- Datatables -->
    <link href="../../../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
    
    <script language="javascript" type="text/javascript" src="../../../js/html2canvas.min.js"></script>
    <script language="javascript" type="text/javascript" src="../../../js/jspdf.min.js"></script>
    <script language="javascript" type="text/javascript" src="../../../js/meta/meta.js"></script>
    <!--<script language="javascript" type="text/javascript" src="../../../js/cliente/cliente.js"></script>-->
    <script language="javascript" type="text/javascript" src="../../../js/global.js"></script>
    <script language="javascript" type="text/javascript" src="../../../js/inicio.js"></script>
    <style type="text/css">
        #chart3 .jqplot-point-label {
          border: 1.5px solid #aaaaaa;
          padding: 1px 3px;
          background-color: #eeccdd;
        }
        .jqplot-xaxis-tick{
            font-weight: bold !important;
            font-size: 14px !important;
        }
    </style>
  </head>

  <body class="nav-md" style="overflow-x:hidden" onload="initMeta();">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
                <a href="../inicio" class="site_title"><i class="fa fa-bar-chart"></i> <span style="font-size: 15px;">Análisis de Métricas</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="../../../img/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Bienvenido</span>
                <h2><?php echo ($_SESSION['apellidoUsuario']);?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->
            <div class="clearfix"></div>

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">                
                <ul class="nav side-menu" style="font-size:13px;">
                  <li><a href="../inicio"><i class="fa fa-home"></i> Inicio</a></li>                  
                  <li><a><i class="fa fa-bar-chart"></i> Calcular Métricas <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="frmBddMeta">Desde un archivo</a></li>
                      <li class="current-page"><a href="">Desde una URL</a></li>                      
                    </ul>
                  </li>
                  <li><a href="../oa/frmListOA"><i class="fa fa-book"></i> Repositorios Analizados</a></li>
                  <li><a href="../oa/frmRptOA"><i class="fa fa-file-o"></i> Reporte</a></li>
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
                    <img src="../../../img/img.jpg" alt=""><?php echo ($_SESSION['nombreUsuario'].' '.$_SESSION['apellidoUsuario']);?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-green pull-right">Próximamente</span>
                        <span>Ayuda</span>
                      </a>
                    </li>
                    <li><a href="javascript:salir();"><i class="fa fa-sign-out pull-right"></i> Salir</a></li>
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
                <h3>Análisis de Métricas desde una URL<small></small> </h3>
              </div>              
            </div>

            <div class="clearfix"></div>
            
            <div class="row">
              <!--<div class="col-md-6 col-xs-12">-->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask" style="min-height:50px;">                        
                        <label class="control-label col-md-12 col-sm-12 col-xs-12" style="font-size:16px;text-align: left;">
                            1. Ingrese la URL del repositorio<br />
                            <small style="color:#AFBBC8;font-weight: normal;">La URL deberá dirigir a una web HTML (Dublin Core) o XML (LOM)</small>
                        </label>
                        <br /><br /><br />                                                    
                        <div class="col-xs-9 input-group has-feedback" style="left:10px;">
                            <div class="input-group-addon">
                            <i class="fa fa-globe"></i>
                        </div>                            
                        <!--
                            http://dspace.utpl.edu.ec/handle/123456789/5519
                            http://repositorio.uchile.cl/handle/2250/132985
                        -->                        
                        <input type="text" class="form-control pull-right" id="txtUrl" placeholder="" value=""/>                                                
                        <!--<input style="font-size:14px;font-family:verdana,tahoma;background:#DCE2E7;" class="btn-lg" size="25" type="file" name="fileCli[]" id="archivoCli" onchange="$('#subio').css('visibility','hidden');" />-->
                        </div>
                    </form>                  
                    <br />
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="font-size:16px;text-align: left;">
                        2. Subir los metadatos (HTML-XML) al Servidor<br />
                        <small style="color:#AFBBC8;font-weight: normal;">Subir el archivo URL al servidor y verificar si cumple con los estándares Dublin Core o LOM</small>
                    </label>
                    <div class="col-xs-6 form-group has-feedback">
                        <button class="btn-lg btn btn-success" onclick="tag.uploadURL($('#txtUrl').val());">Subir el Archivo</button><span id="subio" style="visibility: hidden;font-size:18px;color:#26B99A;"><i class="fa fa-check" style="color:#080;"></i> Archivos subidos</span>
                    </div>
                    <br />
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" style="font-size:16px;text-align: left;">
                        3. Realizar cálculo de métricas
                    </label>
                    <div class="col-xs-12 form-group has-feedback">
                        <button id="btnCalMetNew" class="btn-lg btn btn-success disabled" onclick="tag.get($('#oa_id').val());">Calcular Métricas</button>
                    </div>
                    <br /><br />
                    <div id="resultsMeta" style="display:none;">
                        <table id="detRotef" class="table table-striped table-bordered nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                            <thead>
                              <tr style="font-size:10px;">
                                <th>#</th>
                                <th>Metadata</th>
                                <th>Etiqueta</th>
                                <th>Contenido</th>
                                <th>Peso</th>
                                <th>%</th>
                                <th>Completitud</th>
                                <th>Consistencia</th>
                                <th>Coherencia</th>
                                <th>Estándar</th>
                              </tr>
                            </thead>
                            <tbody id="tblMeta"></tbody>
                        </table>

                        <!--<label class="control-label col-md-12 col-sm-12 col-xs-12" style="font-size:16px;text-align: left;">
                            4. Graficar métricas
                        </label>-->
                        <div class="col-xs-12 form-group has-feedback">
                            <!--<button class="btn-lg btn btn-danger" onclick="rptResu.importarCabecera()">Graficar Métricas</button>-->
                            <strong>GRAFICAS:</strong>
                        </div><br /><br />
                        <div id="chart3" class="example-chart jqplot-target" style="height: 300px; width: 500px; position: relative;"></div>
                        <br /><br />
                        <!--
                        <span style="width:50px;height: 30px;background:#73C774;color:#000;">&nbsp;&nbsp;&nbsp;&nbsp;Y=1&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span style="width:50px;height: 30px;background:#D9D97E;color:#000;">&nbsp;0.5<=Y<1&nbsp;</span>
                        <span style="width:50px;height: 30px;background:#D97E7E;color:#000;">&nbsp;&nbsp;&nbsp;Y<0.5&nbsp;&nbsp;&nbsp;</span>
                        <br /><br /><br /><br />
                        -->
                        <div id="rptMetricas" class="col-xs-12 form-group has-feedback" style="width:300px;height:80px;position:relative;left:-10px;">
                            <button class='btn-sm btn btn-danger' onclick="tag.rpt($('#oa_id').val(),'pdf');">Reporte de Análisis</button>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>            
            
            <div class="clearfix"></div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-left">
                <button class="btn-sm btn btn-dark" onclick="window.location.href='frmNewMeta';">Nuevo Análisis</button>
          </div>
          <div class="pull-right">
            &copy; <?php echo date('Y');?> Análisis de Métricas por <a href="../index" target="_blank">Oliver Sevilla</a>
          </div>
            <input id="oa_id" value="0" style="display:none;" />
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
    <div id="msjErr">
        <div id="msjTxtErr"></div>
        <center><button class="btn btn-success btn-flat" onclick="hideMsjErr();return;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ok&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></center><br />
    </div>
    
    <div id="cargando">
        <div id="imgCentrada"><img src="../../../img/loading1.gif" /></div>
        <div id="progstat"></div>
        <div id="progress"></div>
    </div>
    
    
    <script src="../../../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../../../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Datatables -->
    <script src="../../../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!--<script src="../../../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../../../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../../../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../../../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>-->
    <script src="../../../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../../../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../../../js/moment/moment.min.js"></script>
    <script src="../../../js/datepicker/daterangepicker.js"></script>
    <!--graficas jqplot-->    
    <script src="../../../js/jquery.jqplot.min.js"></script>
    <script src="../../../js/jqplot.min.js"></script>
    <script src="../../../js/shCore.min.js"></script>
    <script src="../../../js/shBrushJScript.min.js"></script>    
    <script src="../../../js/shBrushXml.min.js"></script>    
    <script src="../../../js/jqplot.highlighter.min.js"></script>    
    <script src="../../../js/jqplot.barRenderer.min.js"></script>
    <script src="../../../js/jqplot.categoryAxisRenderer.min.js"></script>
    <script src="../../../js/jqplot.pointLabels.js"></script>
    
    <script src="../../../vendors/iCheck/icheck2.min.js"></script>
    <script src="../../../build/js/custom.min.js"></script>
    
    <script>
      $(document).ready(function() { 
        
        
        
      });
      
    </script>
    <!-- /Datatables -->
    
    <script>
        //mostrar imagen de carga al inicio
        $(window).load(function(){
            $('#cargando').fadeOut();
        });
        //formatear cuadros de texto y botones
        $('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
        $('.quantity').each(function() {
          var spinner = $(this),
            input = spinner.find('input[type="number"]'),
            btnUp = spinner.find('.quantity-up'),
            btnDown = spinner.find('.quantity-down'),
            min = input.attr('min'),
            max = input.attr('max');

          btnUp.click(function() {
            var oldValue = parseFloat(input.val());
            if (oldValue >= max) {
              var newVal = oldValue;
            } else {
              var newVal = oldValue + 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
          });

          btnDown.click(function() {
            var oldValue = parseFloat(input.val());
            if (oldValue <= min) {
              var newVal = oldValue;
            } else {
              var newVal = oldValue - 1;              
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
          });

        });
    </script>
  </body>
</html>
