<?php
require_once("../../modelo/usuario/moUsuario.php");
//require_once("../../../../global.php");

//session_start();

$us_id=$_REQUEST['us_id'];
$us_nombre=$_REQUEST['us_nombre'];
$us_apellido=$_REQUEST['us_apellido'];
$us_mail=$_REQUEST['us_mail'];
$us_clave=$_REQUEST['us_clave'];
$us_estado=$_REQUEST['us_estado'];


$usuario=new usuario(-1);

echo $usuario->update($us_id,$us_nombre,$us_apellido,$us_mail,$us_clave,$us_estado);

/*$secuencial=$objusuario->update($us_id, $us_nombre, $us_apellido, $us_mail, $us_clave);
if($secuencial==0)
    echo 'Hubo un error al intentar actualizar el Usuario. El email del Usuario dede ser unico.';
else
    echo $secuencial;*/
?>