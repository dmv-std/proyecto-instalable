<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/config.php");

if ( count($_GET) > 0 && isset($_GET['id']) && is_numeric($_GET['id']) ) {
	
	$id = $_GET['id'];
	
    $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
    if($mysqli->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$mysqli->set_charset("utf8");
	
	// Configuración
	$query = "SELECT * FROM cot2_configuraciones WHERE id = '1'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$row = $result->fetch_assoc();
	
	$empresa = $row['empresa'];
	$direccion = preg_replace("/<br>/", "\n", $row['direccion']);
	$telefonos = $row['telefonos'];
	$web = $row['web'];
	$email = $row['email'];

	$cotizador2_pdf_logo = explode("/", $cotizador2_pdf_logo);
	array_pop($cotizador2_pdf_logo);
	$cotizador2_pdf_logo = implode("/", $cotizador2_pdf_logo);
	$logo = $row['logo'] ? $basehttp.$cotizador2_pdf_logo."/".$row['logo'] : "";
	
	$iva = $row['iva'];
	
	$filename = $row['titulo-pdf'];
	$filename = explode("|", $filename);
	$cases = array_pop($filename);
	$filename = implode("|", $filename);

	// Cotizacion
	$query = "SELECT * FROM cot2_cotizacion WHERE cot2_cotizacion.id = '$id'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$row = $result->fetch_assoc();
	
	$nombre = $row['nombre'];
	$apellidos = $row['apellidos'];
	$telefono = $row['telefono'];
	$email1 = $row['email'];
	$fecha = date( "d/m/Y", strtotime($row['fecha']) ); // 2020-03-06 16:46:09
	$fecha_pdf = date( "d-m-Y", strtotime($row['fecha']));
	$descuentoporcentaje = $row['descuentoporcentaje'];
	$descuento = $row['descuento'];
	$total = $row['total'];
	$usuario = $row['usuario'];
	$estatus = $row['estatus'];
	
	// Detalles de cotización
	$query = "SELECT * FROM cot2_cotizaciondet WHERE idcot = '$id'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$detalles = [];
	while($row = $result->fetch_assoc()) {
		$detalles[] = $row; // id, idcot, codigo, descripcion, cantidad, colores, precio, total
	}
	
	// Datos de Usuario
	if ( $usuario != 'CLIENTE' ) {
		$query = "SELECT * FROM sist_usuarios WHERE user = '$usuario'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
		// Al menos un usuario en la tabla sistema.'usuarios' tiene en su campo 'user' un nombre que coincide con cotizador.'cotizacion'.'capturado'
		if ( $row = $result->fetch_assoc() ) {
			$usuario_nombre = $row['user'] . " " . $row['apellido'];
			$usuario_correo = $row['email'];
			$usuario_telefono = $row['telefono'];
		}
	} else {
		$usuario_nombre = "";
		$usuario_correo = "";
		$usuario_telefono = "";
	}
	
	// Nombre de archivo
	if ($cases == "u") {
		$_nombre = strtoupper($nombre);
		$_apellidos = strtoupper($apellidos);
	} else if ($cases == "c") {
		$_nombre = ucfirst($nombre);
		$_apellidos = ucfirst($apellidos);
	} else {
		$_nombre = strtolower($nombre);
		$_apellidos = strtolower($apellidos);
	}
	$filename = str_replace("+nombre+",		$_nombre,		$filename);
	$filename = str_replace("+apellido+",	$_apellidos,	$filename);
	$filename = str_replace("+fecha+",		$fecha_pdf,		$filename);
	
	// Cerrando la conexión con la base de datos
	mysqli_close($mysqli);

	ob_start();
	include "documento.php";
	$html = ob_get_clean();

	include "$pdf_path/generar-pdf.php";
	
} else header("location: $basehttp");
?>