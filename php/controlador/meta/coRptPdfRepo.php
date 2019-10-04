<?php
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");

echo printRptRepo($_REQUEST['re_id'])."\r\n";

function printRptRepo($re_id){
    //Instanciar objetos    
    $oa=new oa(0);
    $oa->getAllRepo($re_id);
    $arrOA = $oa->arregloOARepo;
    $fila='';
    for($i=0;$i<count($arrOA);$i++){
        $j=$i+1;        
            $fila .= $j;
            $miDomain=$arrOA[$i]->re_dominio;
            $arrDomain=explode(".", $miDomain);
            if(count($arrDomain)>2)
                $fila .= '     '.strtoupper($arrDomain[1]);
            else
                $fila .= '     '.strtoupper($arrDomain[0]);
            
            $fila .= '     '.$arrOA[$i]->oa_titulo;//cortaTexto($arrOA[$i]->oa_titulo,60);
            $fila .= '     '.$arrOA[$i]->oa_url;
            $fila .= '     '.$arrOA[$i]->oa_std;
            $fila .= '     '.getTotals($arrOA[$i]->oa_id);
    }
    return $fila;
}

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
    }

    //Totales
    $totCompletitud = round($totCompletitud,2);
    $totConsistencia = round(($totConsistencia + intval($valConsistenciaTmp)) / $valConsistencia,2);$totConsistencia=number_format($totConsistencia,2);
    $totCoherencia = round(($totCoherencia + intval($valCoherencia)) / $valCoherenciaPiQi,2);

    if($totCoherencia>1) $totCoherencia=1.00;

    if($oa2->oa_std=='DC') $totConsistencia='No Aplica';    
    
    $porcComp=number_format($totCompletitud,2)*100 . "%";
    $porcCons=number_format($totConsistencia,2)*100 . "%";
    $porcCohe=number_format($totCoherencia,2)*100 . "%";
    /*if($porcComp==100) $bgComp='bg-green';if($porcComp>=50 && $porcComp<100) $bgComp='bg-blue';if($porcComp<50) $bgComp='bg-red';
    if($porcCons==100) $bgCons='bg-green';if($porcCons>=50 && $porcCons<100) $bgCons='bg-blue';if($porcCons<50) $bgCons='bg-red';
    if($porcCohe==100) $bgCohe='bg-green';if($porcCohe>=50 && $porcCohe<100) $bgCohe='bg-blue';if($porcCohe<50) $bgCohe='bg-red';
    if($oa2->oa_std=='DC') {$bgCons='bg-white';$porcCons='NA';$bgAlternoCons='background:#c9c9c9 !important;';}
    
    $tblMeta = $tblMeta."<small class='label pull-left ".$bgComp."' style='font-size:11px;min-width:40px !important;'>".$porcComp."</small>&nbsp;&nbsp;<span style='position:relative;top:-5px;font-size:9px;'>COMPLETITUD</span><br />";
    $tblMeta = $tblMeta."<small class='label pull-left ".$bgCons."' style='".$bgAlternoCons."font-size:11px;min-width:40px !important;'>".$porcCons."</small>&nbsp;&nbsp;<span style='position:relative;top:-5px;font-size:9px;'>CONSISTENCIA</span><br />";
    $tblMeta = $tblMeta."<small class='label pull-left ".$bgCohe."' style='font-size:11px;min-width:40px !important;'>".$porcCohe."</small>&nbsp;&nbsp;<span style='position:relative;top:-5px;font-size:9px;'>COHERENCIA</span>";    
    */
    
    $tblMeta .= $porcComp." COMPLETITUD ";
    $tblMeta .= $porcCons." CONSISTENCIA ";
    $tblMeta .= $porcCohe." COHERENCIA\r\n\r\n";
    return $tblMeta;
}
?>
