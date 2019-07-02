<?php
require_once("../../modelo/usuario/moUsuario.php");
//require_once("../../../../global.php");

//echo "en coservicioInsert.php";

session_start();

$us_nombre=$_REQUEST['us_nombre'];
$us_apellido=$_REQUEST['us_apellido'];
$us_mail=$_REQUEST['us_mail'];
$us_clave=$_REQUEST['us_clave'];
$us_coac=$_SESSION['coacUsuario'];
//$us_clave_activacion=$_REQUEST['us_clave_activacion'];
//$us_estado=$_REQUEST['us_estado'];

$usuario=new usuario(-1);

//echo $usuario->insert($us_nombre,$us_apellido,$us_mail,$us_clave);
echo $usuario->insert($us_nombre,$us_apellido,$us_mail,$us_clave,$us_coac);//0 error, >0 exito
/*if($secuencial==0)
    echo 'Hubo un error al intentar guardar el Usuario. El email del Usuario dede ser unico.';
else
    echo $secuencial;*/
?>