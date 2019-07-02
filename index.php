<?php

//header("Location: login");

session_start();

if (!isset($_SESSION['idUsuario'])) header("Location: login");
else header("Location: php/vista/inicio");
?>
