<?php
//carga de librerias
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");
//creacion de objetos
$oa = new oa(0);
$meta = new meta(0,'');
//definicion de variables locales
$oa_url = $_REQUEST['oa_url'];
$oa_id = $oa->getId($oa_url);
//validaciones
if (!$meta->delete($oa_id)){
    echo 0;
}
else {
    if(!$oa->delete($oa_url)){
        echo 0;
    }
    else echo 1;
}
?>