<?php
//carga libs
require_once("../../../global.php");
require_once("../../../metrica.php");
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/instancia/moInstancia.php");
require_once("../../modelo/repo/moRepo.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");
//acceso a globales
global $VGPathDocsServer; //'/var/www/html/apps/metricas/docs/'
//inicio sesion
session_start();

//Creacion de objetos
$repo=new repo(-1);
$oa = new oa(0);
$meta = new meta(0,'');
$catDc = new catalogo(1,'');//1 si es DUBLIN CORE
$arrCatDC=$catDc->arregloCatalogos;      
$catLom = new catalogo(2,'');//2 si es LOM
$arrCatLom=$catLom->arregloCatalogos;  
//vars locales
$i=0;
$arrOA = array();
$tblMeta = '';
$oaUrl='';

//Obtener el file txt
$filenameCli=$_FILES['fileCli']['name'];
$filepathCli=$_FILES['fileCli']['tmp_name'];

chmod($VGPathDocsServer.$filenameCli, 0777);//dar permisos al file
$filas=file($VGPathDocsServer.$filenameCli);//obtener el contenido del file

foreach($filas as $v){    
    $oaUrl = $oaUrl.$v;//contiene el texto del file    
    $datos=explode(";",$v);//separo en URLs con cada ';'
    
    for($p=0;$p<count($datos);$p++){
        if(trim($datos[$p])!=''){
            $oaUrl=$datos[$p];
            //Variables comunes
            $url = trim($oaUrl);
            $dominio =  dominio($url);
            $tags = get_meta_tags($url);//busca y almacena la estructura Doublin Core
            $xml = simplexml_load_file($url);//busca y almacena la estructura LOM

            $arrOA[$i] = analizarMetricas($repo,$oa,$meta,$arrCatDC,$arrCatLom,$url,$tags,$xml,$dominio);
            if($arrOA[$i]>0) $estado="OK";
            if($arrOA[$i]==-1) $estado="Sin estandar DC/LOM";
            if($arrOA[$i]==-2) $estado="OA no se pudo subir al servidor";
            if($arrOA[$i]==-3) $estado="No se pudo obtener el id del Repo";
            if($arrOA[$i]==-4) $estado="URL ya analizada";
            if($arrOA[$i]>0){
                $tblMeta = $tblMeta."<tr><td>".($i+1)."</td><td>".$url."</td><td>".$estado."</td><td><a href=\"../oa/frmDetOA?oa_id=".$arrOA[$i]."\" style=\"color:#3498DB;text-decoration:underline;cursor:pointer;\" target=\"_blank\">Ver resultados</a></td></tr>";
            }
            else{
                $tblMeta = $tblMeta."<tr><td>".($i+1)."</td><td>".$url."</td><td>".$estado."</td><td>&nbsp;&nbsp;&nbsp;</td></tr>";
            }
            $i++;
        }
    }
}
//retorna las filas de la tabla a front end (javascript)
echo $tblMeta;

?>