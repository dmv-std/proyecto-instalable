<?php

// Generar documento a partir de la id del presupuesto
if ( isset($_GET['id']) ) {
	
	$id = $_GET['id'];
	include ("../config.php");
	
	// Base de datos: Sistema
    $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
    if($mysqli->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$mysqli->set_charset("utf8");
	
	// Configuración
	$result = $mysqli->query("SELECT * FROM presupuestos_configuracion WHERE id = '1'") or die($mysqli->error.__LINE__);
	$row = $result->fetch_assoc();
	$empresa = $row['empresa'];
	$direccion = $row['direccion'];
	$telefonos = $row['telefonos'];
	$web = $row['web'];
	$email = $row['email'];

	$presupuestos_pdf_logo = explode("/", $presupuestos_pdf_logo);
	array_pop($presupuestos_pdf_logo);
	$presupuestos_pdf_logo = implode("/", $presupuestos_pdf_logo);
	$logo = $row['logo'] ? $basehttp.$presupuestos_pdf_logo."/".$row['logo'] : "";

	$filename = $row['titulo-pdf'];
	$filename = explode("|", $filename);
	$cases = array_pop($filename);
	$filename = implode("|", $filename);
	
	// Consultar datos de presupuesto
	$query = "SELECT presupuestos.*, sist_usuarios.user, sist_usuarios.nombre AS 'usuario_nombre', 
					sist_usuarios.apellido AS 'usuario_apellido', sist_usuarios.telefono AS 'usuario_telefono', sist_usuarios.email AS 'usuario_correo'
				FROM presupuestos
				INNER JOIN sist_usuarios ON id_usuario = sist_usuarios.id
				WHERE presupuestos.id = '$id'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$row = $result->fetch_assoc();
	
	$fecha = date( "d/m/Y", strtotime($row['fecha']) );
	$cliente = $row['nombre'] . " " . $row['apellido'];
	$correo = $row['correo'];
	$telefono = $row['telefono'];
	$detalles = $row['detalles'];
	$detalles = special_markups($detalles);
	$detalles = markups($detalles);
	//$detalles = explode("<br>", $detalles);
	$condiciones = markups($row['condiciones']);
	
	$usuario_nombre = $row['user'];
	$usuario = $row['user'] . " " . $row['usuario_apellido'];
	$usuario_correo = $row['usuario_correo'];
	$usuario_telefono = $row['usuario_telefono'];
	
	if ($cases == "u") {
		$nombre = strtoupper($row['nombre']);
		$apellido = strtoupper($row['apellido']);
	} else if ($cases == "c") {
		$nombre = ucfirst($row['nombre']);
		$apellido = ucfirst($row['apellido']);
	} else {
		$nombre = strtolower($row['nombre']);
		$apellido = strtolower($row['apellido']);
	}
	$fecha_pdf = date( "d-m-Y", strtotime($row['fecha']));
	$filename = str_replace("+nombre+",		$nombre,	$filename);
	$filename = str_replace("+apellido+",	$apellido,	$filename);
	$filename = str_replace("+fecha+",		$fecha_pdf,	$filename);
	
	// Cerrando la conexión con la base de datos
	mysqli_close($mysqli);

	/*ob_start();
	include "template.php";
	$html = ob_get_clean();

	include "$pdf_path/generar-pdf.php";*/

	if (!isset($_GET['getdata'])) {
		$params = "license=$license_key"
			. "&empresa=$empresa&direccion=$direccion&telefonos=$telefonos&web=$web&email=$email&logo=$logo"
			. "&filename=$filename&id=$id&cliente=$cliente&correo=$correo&telefono=$telefono&detalles=$detalles&condiciones=$condiciones&fecha=$fecha"
			. "&usuario_nombre=$usuario_nombre&usuario=$usuario&usuario_correo=$usuario_correo&usuario_telefono=$usuario_telefono"
			. (isset($_GET['mode']) ? "&mode=".$_GET['mode'] : "");
		header('location:'."$license_server/presupuestos/pdf?$params");
	} else echo json_encode([
		'license'			=> $license_key,
		'empresa'			=> $empresa,
		'direccion'			=> $direccion,
		'telefonos'			=> $telefonos,
		'web'				=> $web,
		'email'				=> $email,
		'logo'				=> $logo,
		'filename'			=> $filename,
		'id'				=> $id,
		'cliente'			=> $cliente,
		'correo'			=> $correo,
		'telefono'			=> $telefono,
		'detalles'			=> $detalles,
		'condiciones'		=> $condiciones,
		'fecha'				=> $fecha,
		'usuario_nombre'	=> $usuario_nombre,
		'usuario'			=> $usuario,
		'usuario_correo'	=> $usuario_correo,
		'usuario_telefono'	=> $usuario_telefono,
	]);
}

// Generar vista previa del documento, a partir de los datos recogidos desde la página de crear nuevo presupuesto (nuevo.php)
else if ( isset($_GET['usuario']) && isset($_GET['nombre']) && isset($_GET['apellido']) && isset($_GET['telefono'])
	&& isset($_GET['correo']) && isset($_GET['detalles']) && isset($_GET['condiciones']) )
{
	$id = $_GET['usuario'];
	include ("../config.php");
	
	// Base de datos: Sistema
    $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
    if($mysqli->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$mysqli->set_charset("utf8");
	
	// Consultar configuración de sanciones
	$result = $mysqli->query("SELECT * FROM presupuestos_configuracion WHERE id = '1'") or die($mysqli->error.__LINE__);
	$row = $result->fetch_assoc();
	$empresa = $row['empresa'];
	$direccion = $row['direccion'];
	$telefonos = $row['telefonos'];
	$web = $row['web'];
	$email = $row['email'];
	
	$presupuestos_pdf_logo = explode("/", $presupuestos_pdf_logo);
	array_pop($presupuestos_pdf_logo);
	$presupuestos_pdf_logo = implode("/", $presupuestos_pdf_logo);
	$logo = $row['logo'] ? $basehttp.$presupuestos_pdf_logo."/".$row['logo'] : "";
	
	// Consultar datos de usuario
	$query = "SELECT * FROM sist_usuarios WHERE id = '$id'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$row = $result->fetch_assoc();
	
	$usuario_nombre = $row['user'];
	$usuario = $row['user'] . " " . $row['apellido'];
	$usuario_correo = $row['email'];
	$usuario_telefono = $row['telefono'];
	
	$id = "-";
	$fecha = date( "d/m/Y" );
	$cliente = $_GET['nombre'] . " " . $_GET['apellido'];
	$correo = $_GET['correo'];
	$telefono = $_GET['telefono'];
	$detalles = $_GET['detalles'];
	$detalles = special_markups($detalles);
	$detalles = markups($detalles);
	//$detalles = explode("<br>", $detalles);
	$condiciones = markups($_GET['condiciones']);
	
	$filename = "presupuesto-vista-previa";
	
	// Cerrando la conexión con la base de datos
	mysqli_close($mysqli);

	/*ob_start();
	include "template.php";
	$html = ob_get_clean();

	include "$pdf_path/generar-pdf.php";*/

	$params = "license=$license_key"
		. "&empresa=$empresa&direccion=$direccion&telefonos=$telefonos&web=$web&email=$email&logo=$logo"
		. "&filename=$filename&id=$id&cliente=$cliente&correo=$correo&telefono=$telefono&detalles=$detalles&condiciones=$condiciones&fecha=$fecha"
		. "&usuario_nombre=$usuario_nombre&usuario=$usuario&usuario_correo=$usuario_correo&usuario_telefono=$usuario_telefono";
	header('location:'."$license_server/presupuestos/pdf?$params");
}

// Generar vista previa del documento, desde la página de configuración de datos de la empresa
else if ( isset($_GET['empresa']) && isset($_GET['direccion']) && isset($_GET['telefonos']) && isset($_GET['web']) && isset($_GET['email']) && isset($_GET['logo']) )
{
	include ("../config.php");

	$empresa = $_GET['empresa'];
	$direccion = $_GET['direccion'];
	$telefonos = $_GET['telefonos'];
	$web = $_GET['web'];
	$email = $_GET['email'];
	$logo = $_GET['logo'];
	
	$id = "-";
	/*$fecha = "";
	$cliente = "";
	$correo = "";
	$telefono = "";
	$detalles = [];
	$condiciones = "";
	
	$usuario_nombre = "";
	$usuario = "";
	$usuario_correo = "";
	$usuario_telefono = "";*/
	
	$filename = "presupuesto-vista-previa";

	/*ob_start();
	include "template.php";
	$html = ob_get_clean();

	include "$pdf_path/generar-pdf.php";*/
	
	$params = "license=$license_key"
		. "&empresa=$empresa&direccion=$direccion&telefonos=$telefonos&web=$web&email=$email&logo=$logo"
		. "&filename=$filename&id=$id";
	header('location:'."$license_server/presupuestos/pdf?$params");

} else header("location: $basehttp");

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