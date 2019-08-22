<?php
//caraga lib
require_once("../../modelo/usuario/moUsuario.php");
//ini sesion
session_start();
//var locales
$us_nombre=$_REQUEST['us_nombre'];
$us_apellido=$_REQUEST['us_apellido'];
$us_mail=$_REQUEST['us_mail'];
$us_clave=$_REQUEST['us_clave'];
$us_coac=$_SESSION['coacUsuario'];
//inserta user
$usuario=new usuario(-1);
echo $usuario->insert($us_nombre,$us_apellido,$us_mail,$us_clave,$us_coac);//0 error, >0 exito

?>