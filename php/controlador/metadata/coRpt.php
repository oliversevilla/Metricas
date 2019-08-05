<?php

header("Content-disposition: attachment; filename=ReporteMetricas.txt");
header("Content-type: MIME");
header("Content-Transfer-Encoding: binary"); 
header('Pragma: no-cache'); 

require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");

/*$archivo = fopen('ReporteMetricas.txt','a');  //path es el mismo que coRpt.php
fputs($archivo,$contenido); 
fclose($archivo);*/

ini_set('display_errors', 1);

$file = 'ReporteMetricas.txt';//path es el mismo que coRpt.php
$text = "";

if ($fp = fopen($file,'w')) //w: borra texto anterior del file y agrega texto, a: agrega al texto anterior
{
    if (is_writable($file))
    {
        $text =  printRpt($_REQUEST['oa_id'])."\r\n";
        $text .= "Por Oliver Sevilla - UTPL 2019";
        
        if (fwrite($fp, $text) === FALSE)
        {
            die("No se pudo escribir en: $file");            
        }
        else
        {
            ////readfile("ReporteMetricas.txt");
            echo $text; //envio contenido a FE para generar el file de descarga
            fclose($fp);
        }
    }
    else
    {
        echo "El archivo no tiene permisos de escritura";
    }
}
else
{
    die("No se puede abrir el archivo: $file");    
}

function printRpt($oa_id){
    //Instanciar objetos    
    $oa = new oa($oa_id);
    $meta = new meta($oa_id,'');
    $arrMeta = $meta->arregloMeta;    
    
    $txtMeta="";
    $txtMetricas="";
    $contenido="";
    $contenido .="Reporte de Métricas Inconsistentes\r\n\r\n";
    $contenido .="Documento: ".$oa->oa_titulo."\r\n";
    $contenido .="Estandar: ".$oa->oa_std."\r\n\r\n";
    for($i=0;$i<count($arrMeta);$i++){
        //Consultar campos descriptivos según id's de tablas respectivas
        if($oa->oa_std=='DC') $cat = new catalogo(1,$arrMeta[$i]->me_id); //DC
        else $cat = new catalogo(2,$arrMeta[$i]->me_id); //LOM
        $bandera = 0;
        if(trim($cat->ca_desc)!= $txtMeta && (floatval($arrMeta[$i]->me_completitud)==0 || floatval($arrMeta[$i]->me_consistencia)==0) || floatval($arrMeta[$i]->me_coherencia)==0){
            $txtMetricas .="\r\n\r\nMetadata: ".htmlentities(trim($cat->ca_desc))."\r\n";
            if(floatval($arrMeta[$i]->me_completitud)==0){
                $txtMetricas .="Metrica Completitud: 0 (inexistente)\r\n";
                // Descomentar aqui si se quiere Cohe=0 cuando Comple y Consi son 0
                // $bandera = 1;
            }
            if(floatval($arrMeta[$i]->me_consistencia)==0){
                $txtMetricas .="Metrica Consistencia: 0 (inexistente)\r\n";
                // Descomentar aqui si se quiere Cohe=0 cuando Comple y Consi son 0
                //$bandera = 1;
            }
            if(floatval($arrMeta[$i]->me_coherencia)==0 || $bandera == 1){
                $txtMetricas .="Metrica Coherencia: 0 (inexistente)\r\n";
                //$contenido .=$cat->ca_desc." (".$cat->ca_etiqueta."): 0\r\n";
            }
        }
        else $txtMeta = trim($cat->ca_desc);
    }
    if(trim($txtMetricas)=='') $txtMetricas="No existen Metricas inconsistentes\r\n";
    return $contenido.$txtMetricas;
}
?>
