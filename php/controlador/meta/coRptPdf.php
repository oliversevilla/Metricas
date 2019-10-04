<?php
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");

echo printRpt($_REQUEST['oa_id'])."\r\n";

function printRpt($oa_id){
    //Instanciar objetos    
    $oa = new oa($oa_id);
    $meta = new meta($oa_id,'');
    $arrMeta = $meta->arregloMeta;    
    
    $txtMeta="";
    $txtMetricas="";
    $contenido="";
    //$contenido .="Reporte de Métricas Inconsistentes\r\n\r\n";
    $contenido .="Archivo Analizado: ".$oa->oa_titulo."\r\n\r\n";
    $contenido .="Estandar: ".$oa->oa_std."\r\n\r\n";
    $contenido .="MÉTRICAS INCONSISTENTES:";
    for($i=0;$i<count($arrMeta);$i++){
        //Consultar campos descriptivos según id's de tablas respectivas
        if($oa->oa_std=='DC') $cat = new catalogo(1,$arrMeta[$i]->me_id); //DC
        else $cat = new catalogo(2,$arrMeta[$i]->me_id); //LOM
        $bandera = 0;
        if(trim($cat->ca_desc)!= $txtMeta && (floatval($arrMeta[$i]->me_completitud)==0 || floatval($arrMeta[$i]->me_consistencia)==0) || floatval($arrMeta[$i]->me_coherencia)==0){
            $txtMetricas .="\r\n\r\nMetadata: ".htmlentities(trim($cat->ca_desc))."\r\n";
            if(floatval($arrMeta[$i]->me_completitud)==0){
                $txtMetricas .="Metrica Completitud: 0 (inexistente)\r\n";
            }
            if(floatval($arrMeta[$i]->me_consistencia)==0){
                $txtMetricas .="Metrica Consistencia: 0 (inexistente)\r\n";
            }
            if(floatval($arrMeta[$i]->me_coherencia)==0 || $bandera == 1){
                $txtMetricas .="Metrica Coherencia: 0 (inexistente)\r\n";
            }
        }
        else $txtMeta = trim($cat->ca_desc);
    }
    if(trim($txtMetricas)=='') $txtMetricas="No existen Metricas inconsistentes\r\n";
    return $contenido.$txtMetricas;
}
?>
