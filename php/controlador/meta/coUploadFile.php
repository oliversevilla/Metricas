<?php
//carga de librerias
require_once("../../../global.php");
//acceso a globales
global $VGPathDocsServer; //'/var/www/html/apps/metricas/docs/'
//incio de sesion
session_start();
//var locales
$oaUrl='';
//Obtener el file txt
$filenameCli=$_FILES['fileCli']['name'];
$filepathCli=$_FILES['fileCli']['tmp_name'];
//subir el file txt al servidor
if(!move_uploaded_file($filepathCli, $VGPathDocsServer.$filenameCli)) echo $result=0;
else echo $result=1;
?>