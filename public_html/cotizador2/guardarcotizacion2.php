<?php
    include ("../config.php");
 
	if(isset($_GET['idcot'])){
		$idcot = $_GET['idcot'];
		$idprod = $_GET['idprod'];
		$cantidad = $_GET['cantidad'];
		$codigo = $_GET['codigo'];
		$descripcion = $_GET['descripcion'];
		$colores = isset($_GET['colores']) ? $_GET['colores'] : 0;
		$precio = $_GET['preciouni'];
		$total = $_GET['total'];
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		$insert_row = $mysqli->query("INSERT INTO cot2_cotizaciondet (idcot, codigo, descripcion, cantidad, colores, precio, total) values('$idcot', '$codigo', '$descripcion', '$cantidad', '$colores', '$precio', '$total')");
		if($insert_row){
			echo $idcot;
		}else{
			print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
	}
?>