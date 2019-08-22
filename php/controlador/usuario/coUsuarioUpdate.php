<?php
//carga lib
require_once("../../modelo/usuario/moUsuario.php");
//crea vars
$us_id=$_REQUEST['us_id'];
$us_nombre=$_REQUEST['us_nombre'];
$us_apellido=$_REQUEST['us_apellido'];
$us_mail=$_REQUEST['us_mail'];
$us_clave=$_REQUEST['us_clave'];
$us_estado=$_REQUEST['us_estado'];
//crea objeto
$usuario=new usuario(-1);
//actualiza user
echo $usuario->update($us_id,$us_nombre,$us_apellido,$us_mail,$us_clave,$us_estado);
?>