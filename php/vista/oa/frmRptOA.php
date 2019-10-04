<?php
//carga de libs
require_once("../../../global.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/meta/moMeta.php");
require_once("../../modelo/repo/moRepo.php");
//inicia sesion
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

//instancio objetos
if(isset($_REQUEST['re_id'])) $re_id=$_REQUEST['re_id'];
else $re_id=0;
//para tabla
$oa=new oa(0);
$oa->getAllRepo($re_id);
$arrOA = $oa->arregloOARepo;
//para combo
$repo=new repo(0);
$arrRepo=$repo->arregloRepo;

function getTotals($oa_id){
    //Instanciar objetos
    $oa2 = new oa($oa_id);
    $meta = new meta($oa_id,'');
    $arrMeta = $meta->arregloMeta;
    $arrMetricas = array();
    //variables para enviar a Front End
    $tblMeta="";
    $totPesoVal=0;
    $totPesoPorcentaje=0;
    $totCompletitud=0;
    $totConsistencia=0;
    $totCoherencia=0;
    $valConsistencia=0;
    $valCoherenciaPiQi=0;
    //Proceso bucle para validaciones
    //Consultar campos descriptivos según id's de tablas respectivas
    for($i=0;$i<count($arrMeta);$i++){    
        if($oa2->oa_std=='DC') $cat = new catalogo(1,$arrMeta[$i]->me_id); //DC
        else $cat = new catalogo(2,$arrMeta[$i]->me_id); //LOM

        /*if(floatval($arrMeta[$i]->me_completitud)==0 || floatval($arrMeta[$i]->me_consistencia)==0 || floatval($arrMeta[$i]->me_coherencia)==0){
            $background="#FFC2B3";
        }
        else $background="#FFF";*/

        $totPesoVal = $totPesoVal + $cat->ca_peso;
        $totPesoPorcentaje = $totPesoPorcentaje + ($cat->ca_peso * 100);
        $totCompletitud = $totCompletitud + $arrMeta[$i]->me_completitud;

        if($arrMeta[$i]->me_consistencia<0) $valConsistenciaTmp='';
        else {
            $totConsistencia = $totConsistencia + $arrMeta[$i]->me_consistencia;
            $valConsistenciaTmp=$arrMeta[$i]->me_consistencia;
            $valConsistencia++;
        }

        if($arrMeta[$i]->me_coherencia>=0)  {
            $totCoherencia = $totCoherencia + $arrMeta[$i]->me_coherencia;
            $valCoherencia = $arrMeta[$i]->me_coherencia;
            $valCoherenciaPiQi++;
        }
        else $valCoherencia = '';

        //$tblMeta = $tblMeta."<tr style='background:".$background.";'><td>".($i+1)."</td><td>".$cat->ca_desc."</td><td>".$arrMeta[$i]->me_etiqueta."</td><td>".$arrMeta[$i]->me_content."</td><td style='text-align:right;'>".$cat->ca_peso."</td><td style='text-align:right;'>".number_format(($cat->ca_peso * 100),2)."%</td><td style='text-align:right;'>".$arrMeta[$i]->me_completitud."</td><td style='text-align:right;'>".$valConsistenciaTmp."</td><td style='text-align:right;'>".$valCoherencia."</td><td>".$oa2->oa_std."</td></tr>";

    }

    //Totales
    $totCompletitud = round($totCompletitud,2);
    $totConsistencia = round(($totConsistencia + intval($valConsistenciaTmp)) / $valConsistencia,2);$totConsistencia=number_format($totConsistencia,2);
    $totCoherencia = round(($totCoherencia + intval($valCoherencia)) / $valCoherenciaPiQi,2);

    if($totCoherencia>1) $totCoherencia=1.00;

    if($oa2->oa_std=='DC') $totConsistencia='No Aplica';
    //$tblMeta = $tblMeta."<tr style='background:#B6E0FF;'><td>-</td><td>-</td><td>-</td><td><strong>TOTAL M&Eacute;TRICA</strong></td><td style='text-align:right;'>".number_format($totPesoVal,2)."</td><td style='text-align:right;'>".number_format($totPesoPorcentaje,2)."%</td><td id='totComp' style='text-align:right;'>".number_format($totCompletitud,2)."</td><td id='totCons' style='text-align:right;'>".$totConsistencia."</td><td id='totCohe' style='text-align:right;'>".number_format($totCoherencia,2)."</td><td>".$oa2->oa_std."</td></tr>";
    
    $porcComp=number_format($totCompletitud,2)*100 . "%";
    $porcCons=number_format($totConsistencia,2)*100 . "%";
    $porcCohe=number_format($totCoherencia,2)*100 . "%";
    if($porcComp==100) $bgComp='bg-green';if($porcComp>=50 && $porcComp<100) $bgComp='bg-blue';if($porcComp<50) $bgComp='bg-red';
    if($porcCons==100) $bgCons='bg-green';if($porcCons>=50 && $porcCons<100) $bgCons='bg-blue';if($porcCons<50) $bgCons='bg-red';
    if($porcCohe==100) $bgCohe='bg-green';if($porcCohe>=50 && $porcCohe<100) $bgCohe='bg-blue';if($porcCohe<50) $bgCohe='bg-red';
    if($oa2->oa_std=='DC') {$bgCons='bg-white';$porcCons='NA';$bgAlternoCons='background:#c9c9c9 !important;';}
    
    /*
    $tblMeta = $tblMeta."<small class='label pull-left ".$bgComp."' style='font-size:11px;min-width:40px !important;'>".$porcComp."</small>&nbsp;&nbsp;<span style='position:relative;top:-5px;font-size:9px;'>COMPLETITUD</span><br />";
    $tblMeta = $tblMeta."<small class='label pull-left ".$bgCons."' style='".$bgAlternoCons."font-size:11px;min-width:40px !important;'>".$porcCons."</small>&nbsp;&nbsp;<span style='position:relative;top:-5px;font-size:9px;'>CONSISTENCIA</span><br />";
    $tblMeta = $tblMeta."<small class='label pull-left ".$bgCohe."' style='font-size:11px;min-width:40px !important;'>".$porcCohe."</small>&nbsp;&nbsp;<span style='position:relative;top:-5px;font-size:9px;'>COHERENCIA</span>";    
    */
    
    $tblMeta = $tblMeta.$porcComp." Completitud, ".$porcCons." Consistencia, ".$porcCohe." Coherencia";
    
    return $tblMeta;
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

    <title>Análisis de Métricas</title>
    
    
    <link href="../../../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../../vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../../../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    
    <!-- Datatables -->
    <link href="../../../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../../../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    
    <link href="../../../build/css/custom.min.css" rel="stylesheet">
    <link href="../../../css/estilo.css" rel="stylesheet">
    
    <script language="javascript" type="text/javascript" src="../../../js/jspdf.min.js"></script>    
    <script language="javascript" type="text/javascript" src="../../../js/meta/meta.js"></script>
    <script language="javascript" type="text/javascript" src="../../../js/global.js"></script>
    <script language="javascript" type="text/javascript" src="../../../js/inicio.js"></script>
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
                      <li><a href="../meta/frmBddMeta">Desde un archivo</a></li>
                      <li><a href="../meta/frmNewMeta">Desde una URL</a></li>                      
                    </ul>
                  </li>
                  <li><a href="../oa/frmListOA"><i class="fa fa-book"></i> Repositorios Analizados</a></li>
                  <li class="current-page"><a href="../oa/frmRptOA"><i class="fa fa-file-o"></i> Reporte</a></li>
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
                <h3>Reporte de OAs por Repositorio <small></small> </h3>
              </div>              
            </div>

            <div class="clearfix"></div>
            
            <div class="row">
              <!--<div class="col-md-6 col-xs-12">-->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        
                        
                        <div class="form-group col-md-4 col-sm-4 col-xs-4">
                            <label style="font-size: 14px;">Repositorio</label><br />
                            <small style="font-size: 11px;">Selecione un repositorio para imprimir los objetos analizados</small>
                            <br /><br />
                            <select id="re_id" name="re_id" onchange="tag.changeRepo()" class="form-control">
                                <option value='0'>Todos</option>
                                <?php
                                    for($i=0;$i<count($arrRepo);$i++){
                                        //seleccionar el re_id consultado
                                        if($_REQUEST['re_id']==$arrRepo[$i]->re_id) $selected='selected';
                                        else $selected='';
                                        $miDomain0=$arrRepo[$i]->re_dominio;
                                        $arrDomain0=explode(".", $miDomain0);
                                        if(count($arrDomain0)>2)
                                            echo "<option ".$selected." value='".$arrRepo[$i]->re_id."'>".strtoupper($arrDomain0[1])."</option>";
                                        else
                                            echo "<option ".$selected." value='".$arrRepo[$i]->re_id."'>".strtoupper($arrDomain0[0])."</option>";
                                    }
                                ?>
                            </select>                            
                        </div>
                        
                        
                        <ul class="nav navbar-right panel_toolbox">
                          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                          </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table id="datatable" class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                  <th width="2%">#</th>
                                  <th width="17%">Repositorio</th>
                                  <!--<th width="17%">URL</th>-->
                                  <th width="43%">Título</th>
                                  <th width="20%">Keywords</th>
                                  <th width="3%">Estándar</th>
                                  <!--<th width="5%">Fecha</th>-->
                                  <th width="20%">Resultados</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--<tr><th>#</th><th>REPOSITORIO</th><th>TITULO</th><th>KEYWORDS</th><th>ESTANDAR</th><th>RESULTADOS</th></tr>-->
                                <?php
                                for($i=0;$i<count($arrOA);$i++){
                                    $j=$i+1;
                                    echo '<tr>';
                                        echo '<td scope="row">'.$j.'</td>';
                                        $miDomain=$arrOA[$i]->re_dominio;
                                        $arrDomain=explode(".", $miDomain);
                                        if(count($arrDomain)>2)
                                            echo '<td>'.cortaTexto(strtoupper($arrDomain[1]),17).'</td>';
                                        else
                                            echo '<td>'.cortaTexto(strtoupper($arrDomain[0]),17).'</td>';
                                        //echo '<td>'.$arrOA[$i]->re_dominio.'</td>';
                                        if(trim($arrOA[$i]->oa_titulo)=='') $miTit="-";
                                        else $miTit=$arrOA[$i]->oa_titulo;
                                        echo '<td title="'.$miTit.'">'.cortaTexto($miTit,55).'</td>';
                                        echo '<td>'.cortaTexto($arrOA[$i]->oa_url,55).'</td>';
                                        echo '<td>'.$arrOA[$i]->oa_std.'</td>';
                                        //echo '<td>'.date('Y-m-d H:i:s', strtotime($arrOA[$i]->oa_fec)).'</td>';
                                        echo '<td>'.getTotals($arrOA[$i]->oa_id).'</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        
                        <div id="rptMetricas" class="col-xs-12 form-group has-feedback" style="width:300px;height:80px;position:relative;left:-10px;">
                            <button class='btn-sm btn btn-danger' onclick="tag.rptRepo(<?php echo $_REQUEST['re_id'];?>,'pdf');">Descargar Reporte</button>
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
    <!--<script src="../../../vendors/select2/dist/js/select2.full.min.js"></script>-->
    <script src="../../../vendors/fastclick/lib/fastclick.js"></script>
    <script src="../../../vendors/nprogress/nprogress.js"></script>
    <script src="../../../vendors/iCheck/icheck.min.js"></script>
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
    
    <script src="../../../vendors/iCheck/icheck2.min.js"></script>
    <script src="../../../build/js/custom.min.js"></script>
    
    <script>
      $(document).ready(function() { 
        //constructor para tablas
        $('#datatable').dataTable({"lengthMenu": [[-1, 10, 25, 50], ["Todos", 10, 25, 50]]});
        
      });
      
    </script>
    <!-- /Datatables -->
    
    <script>
        //cargar imagen "cargando" al inciar
        $(window).load(function(){
            $('#cargando').fadeOut();
        });
    </script>
  </body>
</html>
