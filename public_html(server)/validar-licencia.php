<?php header('Content-type: application/javascript; charset=utf-8');


$callback = isset($_GET['callback']) ? $_GET['callback'] : "";

if ( !$callback || preg_match('/\W/', $callback) || !isset($_GET['license']) ) {
	header('HTTP/1.1 400 Bad Request');
	$data = [
		"error"			=>'HTTP/1.1 400 Bad Request', "validated" => false,
		"actived"		=> false,
		"expire"		=> '',
		"expired"		=> false,
	];
}

if ( isset($_GET['license']) ) {
	
	include("config.php");
	include("$basepath/assets/functions/validar-licencia.php");
	$data = validarLicencia( $_GET['license'] );

}

echo "typeof ".$callback."==='function' && ".$callback."(".json_encode($data).");";
?>