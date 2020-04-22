<?php

if ( isset($_GET['license']) ) {
	include("config.php");
	
	preg_match ("/https?:\/\/([^\/]+?)\//", $_SERVER['HTTP_REFERER'], $matches);
	if (!isset($matches[1]))
		die("ERROR: Problema de reconocimiento de Url.");
	
	$sitio = $matches[1];
	$serial = $_GET['license'];
	
	// Check license first
	$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($conn->connect_errno > 0){
		die('Unable to connect to database [' . $conn->connect_error . ']');
	}
	$conn->set_charset("utf8");
	$result = $conn->query("SELECT * FROM licencias WHERE serial='$serial' AND sitio LIKE '%$sitio%'") or die($conn->error.__LINE__);
	if ($result->num_rows == 0);
		die("La licencia suministrada no es válida");
	else {
		$row = $result->fetch_assoc();
		if (date('Y-m-d')>$row['fecha_expiracion'])
			die("Su licencia ha expirado.");
	}

	// Reading desired parameter urls
	$parameters = "empresa|direccion|telefonos|web|email|logo";
	$parameters .= "|filename|id|cliente|correo|telefono|detalles|condiciones|fecha";
	$parameters .= "|usuario_nombre|usuario|usuario_correo|usuario_telefono";

	foreach(explode("|", $parameters) as $global) {
		$GLOBALS[$global] = isset($_GET[$global]) ? $_GET[$global] : '';
	}

	if ($detalles) {
		$detalles = special_markups($detalles);
		$detalles = markups($detalles);
		$detalles = explode("<br>", $detalles);
	} else $detalles = [];
	if ($condiciones)
		$condiciones = markups($condiciones);

	ob_start();
	include "template.php";
	$html = ob_get_clean();

	include "$basepath/assets/pdf/generar-pdf.php";
}

function markups($s){
	$s = preg_replace( "/\*\*\*(.+?)\*\*\*/",	"<strong><em>$1</em></strong>",	$s);
	$s = preg_replace( "/\*\*(.+?)\*\*/",		"<strong>$1</strong>",			$s);
	$s = preg_replace( "/\*(.+?)\*/",			"<em>$1</em>",					$s);
	$s = preg_replace( "/_(.+?)_/",				"<u>$1</u>",					$s);
	$s = preg_replace( "/~(.+?)~/",				"<s>$1</s>",					$s);
	$s = preg_replace( "[\n|\r|\n\r]",			"<br>",							$s);
	return $s;
}
function special_markups($s){
	$s = preg_replace( "/\*\*\*(.+?)\|(.+?)\*\*\*/",	"<strong><em>$1</em></strong>|<strong><em>$2</em></strong>",	$s);
	$s = preg_replace( "/\*\*(.+?)\|(.+?)\*\*/",		"<strong>$1</strong>|<strong>$2</strong>",						$s);
	$s = preg_replace( "/\*(.+?)\|(.+?)\*/",			"<em>$1</em>|<em>$2</em>",										$s);
	$s = preg_replace( "/_(.+?)\|(.+?)_/",				"<u>$1</u>|<u>$2</u>",											$s);
	$s = preg_replace( "/~(.+?)\|(.+?)~/",				"<s>$1</s>|<s>$2</s>",											$s);
	return $s;
}
?>