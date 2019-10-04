<?php
//carga de librerias
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");
//Instanciar objetos
$oa = new oa($_REQUEST['oa_id']);
$meta = new meta($_REQUEST['oa_id'],'');
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
    if($oa->oa_std=='DC') $cat = new catalogo(1,$arrMeta[$i]->me_id); //DC
    else $cat = new catalogo(2,$arrMeta[$i]->me_id); //LOM
           
    if(floatval($arrMeta[$i]->me_completitud)==0 || floatval($arrMeta[$i]->me_consistencia)==0 || floatval($arrMeta[$i]->me_coherencia)==0){
        $background="#FFC2B3";
    }
    else $background="#FFF";
    
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
    
    $tblMeta = $tblMeta."<tr style='background:".$background.";'><td>".($i+1)."</td><td>".$cat->ca_desc."</td><td>".$arrMeta[$i]->me_etiqueta."</td><td>".$arrMeta[$i]->me_content."</td><td style='text-align:right;'>".$cat->ca_peso."</td><td style='text-align:right;'>".number_format(($cat->ca_peso * 100),2)."%</td><td style='text-align:right;'>".$arrMeta[$i]->me_completitud."</td><td style='text-align:right;'>".$valConsistenciaTmp."</td><td style='text-align:right;'>".$valCoherencia."</td><td>".$oa->oa_std."</td></tr>";
    
}

//Totales
$totCompletitud = round($totCompletitud,2);
$totConsistencia = round(($totConsistencia + intval($valConsistenciaTmp)) / $valConsistencia,2);$totConsistencia=number_format($totConsistencia,2);
$totCoherencia = round(($totCoherencia + intval($valCoherencia)) / $valCoherenciaPiQi,2);

if($totCoherencia>1) $totCoherencia=1.00;

if($oa->oa_std=='DC') $totConsistencia='No Aplica';
$tblMeta = $tblMeta."<tr style='background:#B6E0FF;'><td>-</td><td>-</td><td>-</td><td><strong>TOTAL M&Eacute;TRICA</strong></td><td style='text-align:right;'>".number_format($totPesoVal,2)."</td><td style='text-align:right;'>".number_format($totPesoPorcentaje,2)."%</td><td id='totComp' style='text-align:right;'>".number_format($totCompletitud,2)."</td><td id='totCons' style='text-align:right;'>".$totConsistencia."</td><td id='totCohe' style='text-align:right;'>".number_format($totCoherencia,2)."</td><td>".$oa->oa_std."</td></tr>";

echo $tblMeta;
?>
