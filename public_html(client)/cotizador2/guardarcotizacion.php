<?php
    include ("../config.php");
    if(isset($_GET['nombre'])){
		$nombre = $_GET['nombre'];
		$usuario = $_GET['usuario'];
		$apellidos = $_GET['apellidos'];
		$email = $_GET['email'];
		$telefono = $_GET['telefono'];
		$descuentototal = $_GET['descuentototal'];
		$descuentoporcentaje = $_GET['descuento'];
		$total = $_GET['total'];
		$fecha = date('Y-m-d H:i:s');
		$estatus = "COTIZADO";
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		$insert_row = $mysqli->query("INSERT INTO cot2_cotizacion (nombre, apellidos, email, telefono, descuentoporcentaje, descuento, total, fecha, usuario, estatus) values('$nombre', '$apellidos', '$email', '$telefono', '$descuentoporcentaje','$descuentototal', '$total', '$fecha', '$usuario', '$estatus')");
		if($insert_row){
			echo $mysqli->insert_id;
		}else{
			print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
    }
?>