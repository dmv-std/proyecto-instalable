<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/config.php");

if ( count($_GET) > 0 && isset($_GET['id']) && is_numeric($_GET['id']) ) {
	
	$id = $_GET['id'];
	$filename = "Presupuesto_Pisos_Goma_Eva_-_Eva_Magic_SA";
	
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
	$logo = $row['logo'] ? $row['logo'] : "blank.jpg";
	
	$iva = $row['iva'];
	
	// Cotizacion
	$query = "SELECT * FROM cot2_cotizacion WHERE cot2_cotizacion.id = '$id'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$row = $result->fetch_assoc();
	
	$nombre = $row['nombre'];
	$apellidos = $row['apellidos'];
	$telefono = $row['telefono'];
	$email1 = $row['email'];
	$fecha = date( "d/m/Y", strtotime($row['fecha']) ); // 2020-03-06 16:46:09
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
	
	// Cerrando la conexión con la base de datos
	mysqli_close($mysqli);

	ob_start();
	include "documento.php";
	$html = ob_get_clean();

	include "$basepath/html2pdf/generar-pdf.php";
	
} else header("location: $basehttp");
?>