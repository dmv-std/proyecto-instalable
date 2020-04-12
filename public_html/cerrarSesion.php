<?php
session_start();
error_reporting(0);

$varSesion = $_SESSION['nombrePersona'];

if($varSesion == null || $varSesion==''){
	echo "Favor de iniciar sesion para acceder a esta pagina. <a href=/program/pagos/admin.php>Haz click aqui</a>";
	die();
}

session_destroy(); 
header ("Location: login");
?>