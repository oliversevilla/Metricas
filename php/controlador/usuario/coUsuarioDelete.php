<?php
require_once("../../modelo/usuario/moUsuario.php");
//require_once("../../../../global.php");

//echo "en coservicioInsert.php";

session_start();

$us_id=$_REQUEST['us_id'];

$usuario=new usuario(-1);

$elimino=$usuario->delete($us_id);
if(!$elimino)
    echo 'Hubo un error al intentar eliminar el Usuario.';
else
    echo 1;
?>