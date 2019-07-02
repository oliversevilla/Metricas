<?php
//require_once("urdaevento.php");
session_start();

//Obetener direcciones de eventos q aun no la tengan para este usuario y empresa (asi al abrir el Panel de Actividad no se demora mucho)
//$evento=new daevento(0,'');
//$evento->updateDir($_SESSION["idEmpresa"],$_SESSION["idUsuario"]);

//session_destroy();//Esto en Log Out
unset($_SESSION['idUsuario']);
Header("Location: login");
?>