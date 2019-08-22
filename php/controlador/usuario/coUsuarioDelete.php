<?php
//carga lib
require_once("../../modelo/usuario/moUsuario.php");
//inicia sesion
session_start();
//var locales
$us_id=$_REQUEST['us_id'];
//crea objeto
$usuario=new usuario(-1);
//elimina user
$elimino=$usuario->delete($us_id);
if(!$elimino)
    echo 'Hubo un error al intentar eliminar el Usuario.';
else
    echo 1;
?>