<?php

if (isset($_GET['mode']) && ($_GET['mode']=='storein' || $_GET['mode']=='delete')) {
	header('Content-type: application/javascript; charset=utf-8');
	$callback = isset($_GET['callback']) ? $_GET['callback'] : "";
}

if ( isset($_GET['license']) ) {
	include("../config.php");
	include("$basepath/assets/functions/validar-licencia.php");
	
	$validar = validarLicencia( $_GET['license'] );
	if ($validar['error'])
		die($validar['error']);

	// Reading desired parameter urls
	$parameters = "empresa|direccion|telefonos|web|email|logo";
	$parameters .= "|filename|id|cliente|correo|telefono|detalles|condiciones|fecha";
	$parameters .= "|usuario_nombre|usuario|usuario_correo|usuario_telefono";

	foreach(explode("|", $parameters) as $global) {
		$GLOBALS[$global] = isset($_GET[$global]) ? $_GET[$global] : '';
	}

	if ($detalles)
		$detalles = explode("<br>", $detalles);
	else $detalles = [];

	ob_start();
	include "template.php";
	$html = ob_get_clean();

	if (!isset($_GET['mode']) || isset($_GET['mode']) && $_GET['mode']!='delete')
		include "$basepath/assets/pdf/generar-pdf.php";
	else if (isset($_GET['mode']) && $_GET['mode']=='delete' && file_exists("$basepath/assets/pdf/$filename"."_$id.pdf"))
		unlink("$basepath/assets/pdf/$filename"."_$id.pdf");
}

if (isset($_GET['mode']) && ($_GET['mode']=='storein' || $_GET['mode']=='delete')) {
	echo "typeof ".$callback."==='function' && ".$callback."(".json_encode(["SUCCESS" => true]).");";
}

?>