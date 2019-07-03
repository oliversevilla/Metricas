<?php
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/metadata/moMetadata.php");

//Instanciar objetos
$oa = new oa($_REQUEST['oa']);
$meta = new metadata($_REQUEST['oa'],'');
$arrMeta = $meta->arregloMetadata;
$arrMetricas = array();
//variables para enviar a Front End
$tblMeta="";
$totPesoVal=0;
$totPesoPorcentaje=0;
$totCompletitud=0;
$totConsistencia=0;
$totCoherencia=0;

for($i=0;$i<count($arrMeta);$i++){
    //Consultar campos descriptivos según id's de tablas respectivas
    if($oa->oa_std=='DC') $cat = new catalogo(1,$arrMeta[$i]->me_id); //DC
    else $cat = new catalogo(2,$arrMeta[$i]->me_id); //LOM
    
    $valCoherencia = $arrMeta[$i]->me_coherencia;
    
    if(floatval($arrMeta[$i]->me_completitud)==0 || floatval($arrMeta[$i]->me_consistencia)==0 || floatval($arrMeta[$i]->me_coherencia)==0){
        $background="#FFC2B3";
        // Descomentar aqui si se quiere Cohe=0 cuando Comple y Consi son 0
        //$valCoherencia = 0;//si completitud o consistencia son 0 -> coherencia tmb.
    }
    else $background="#FFF";
    /*$arrMetricas = array(
        "oaNombre" => $oa->oa_titulo,
        "meNombre" => $cat->ca_desc,
        "meEtiqueta" => $arrMeta[$i]->me_etiqueta,
        "meContenido" => $arrMeta[$i]->me_content,
        "meCompletitud" => $arrMeta[$i]->me_completitud,
        "meConsistencia" => $arrMeta[$i]->me_consistencia,
        "meCoherencia" => $arrMeta[$i]->me_coherencia
    );*/
    $totPesoVal = $totPesoVal + $cat->ca_peso;  //number_format($número, 2)
    $totPesoPorcentaje = $totPesoPorcentaje + ($cat->ca_peso * 100);
    $totCompletitud = $totCompletitud + $arrMeta[$i]->me_completitud;
    $totConsistencia = $totConsistencia + $arrMeta[$i]->me_consistencia;
    $totCoherencia = $totCoherencia + $valCoherencia;
    
    $tblMeta = $tblMeta."<tr style='background:".$background.";'><td>".($i+1)."</td><td>".$cat->ca_desc."</td><td>".$arrMeta[$i]->me_etiqueta."</td><td>".$arrMeta[$i]->me_content."</td><td style='text-align:right;'>".$cat->ca_peso."</td><td style='text-align:right;'>".number_format(($cat->ca_peso * 100),2)."%</td><td style='text-align:right;'>".$arrMeta[$i]->me_completitud."</td><td style='text-align:right;'>".$arrMeta[$i]->me_consistencia."</td><td style='text-align:right;'>".$valCoherencia."</td><td>".$oa->oa_std."</td></tr>";
}
//echo json_encode($arrMetricas);
//Totales
$totCompletitud = round($totCompletitud,2);
$totConsistencia = round(($totConsistencia + $arrMeta[$i]->me_consistencia) / count($arrMeta),2);
$totCoherencia = round(($totCoherencia + $arrMeta[$i]->me_coherencia) / count($arrMeta),2);
$tblMeta = $tblMeta."<tr style='background:#B6E0FF;'><td>-</td><td>-</td><td>-</td><td><strong>TOTAL M&Eacute;TRICA</strong></td><td style='text-align:right;'>".number_format($totPesoVal,2)."</td><td style='text-align:right;'>".number_format($totPesoPorcentaje,2)."%</td><td id='totComp' style='text-align:right;'>".number_format($totCompletitud,2)."</td><td id='totCons' style='text-align:right;'>".number_format($totConsistencia,2)."</td><td id='totCohe' style='text-align:right;'>".number_format($totCoherencia,2)."</td><td>".$oa->oa_std."</td></tr>";
echo $tblMeta;
?>
