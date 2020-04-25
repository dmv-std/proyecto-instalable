<?php
function validarLicencia($serial) {
	include ($_SERVER['DOCUMENT_ROOT'] . "/config.php");
	
	$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "not-valid";
	preg_match ("/https?:\/\/([^\/]+?)\//", $referer, $matches);
	
	if (!isset($matches[1]))
		return [ "error" => "El dominio de origen no pudo ser ubicado" ];

	$sitio = isset($matches[1]) ? $matches[1] : '<not-valid>';

	// Check license first
	$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($conn->connect_errno > 0)
		return [
			"error"		=> 'Unable to connect to database [' . $conn->connect_error . ']',
			"actived"	=> false,
			"expire"	=> '',
			"expired"	=> false,
		];
	$conn->set_charset("utf8");

	$result = $conn->query("SELECT * FROM licencias WHERE serial='$serial' AND sitio LIKE '%$sitio%'") or die($conn->error.__LINE__);

	if ($result->num_rows == 0) {
		return [
			"error"		=> "La licencia suministrada no es válida",
			"actived"	=> false,
			"expire"	=> '',
			"expired"	=> false,
		];
	} else {
		$row = $result->fetch_assoc();
		if (date('Y-m-d')>$row['fecha_expiracion'])
			return [
				"error"		=> "Su licencia ha expirado",
				"actived"	=> $row['activa'] ? true : false,
				"expire"	=> date('d/m/Y', strtotime($row['fecha_expiracion'])),
				"expired"	=> true,
				"debug"		=> date('Y-m-d').">".$row['fecha_expiracion'],
			];
		else if (!$row['activa'])
			return [
				"error"		=> "Su licencia es válida pero se encuentra inactiva",
				"actived"	=> $row['activa'] ? true : false,
				"expire"	=> date('d/m/Y', strtotime($row['fecha_expiracion'])),
				"expired"	=> false,
				"debug"		=> date('Y-m-d').">".$row['fecha_expiracion'],
			];
		else
			return [
				"error"		=> "",
				"actived"	=> $row['activa'] ? true : false,
				"expire"	=> date('d/m/Y', strtotime($row['fecha_expiracion'])),
				"expired"	=> false,
				"debug"		=> date('Y-m-d').">".$row['fecha_expiracion'],
			];
	}
}
?>