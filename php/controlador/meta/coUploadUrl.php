<?php
//craga de libs
require_once("../../../metrica.php");
require_once("../../modelo/catalogo/moCatalogo.php");
require_once("../../modelo/instancia/moInstancia.php");
require_once("../../modelo/repo/moRepo.php");
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");
//Creacion de objetos
$repo=new repo(-1);
$oa = new oa(0);
$meta = new meta(0,'');
$catDc = new catalogo(1,'');//1 si es DUBLIN CORE
$arrCatDC=$catDc->arregloCatalogos;      
$catLom = new catalogo(2,'');//2 si es LOM
$arrCatLom=$catLom->arregloCatalogos;      
//Variables comunes
$url = $_REQUEST['url'];
$dominio =  dominio($url);
$tags = get_meta_tags($url);//busca y almacena la estructura Doublin Core
$xml = simplexml_load_file($url);//busca y almacena la estructura LOM
//retorna 0 o >1 si obtuvo y guardo metricas calculadas
echo analizarMetricas($repo,$oa,$meta,$arrCatDC,$arrCatLom,$url,$tags,$xml,$dominio);

?>