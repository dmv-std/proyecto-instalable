<?php
	
	if(isset($_GET['id']) && $_GET['id'] != 0 && isset($_GET['nombre']) && isset($_GET['apellido'])
		&& isset($_GET['telf']) && isset($_GET['correo']) && isset($_GET['detalles']) && isset($_GET['importe']) && isset($_GET['estado']))
	{
		$id = $_GET['id'];
		
		$nombre = $_GET['nombre'];
		$apellido = $_GET['apellido'];
		$telefono = $_GET['telf'];
		$correo = $_GET['correo'];
		$detalles = $_GET['detalles'];
		$condiciones = $_GET['condiciones'];
		$importe = $_GET['importe'];
		$estado = $_GET['estado'];
		
		$fecha = date('Y-m-d');
		
		include ("../config.php");
		
		// Base de datos: sistema
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		$results = $mysqli->query("INSERT INTO presupuestos
			(id_usuario, nombre, apellido, telefono, correo, detalles, condiciones, importe, estado, fecha)
			values('$id', '$nombre', '$apellido', '$telefono', '$correo', '$detalles', '$condiciones', '$importe', '$estado', '$fecha')");
		if($results){
			echo $mysqli->insert_id;
		}else{
			print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
		
	}
?>