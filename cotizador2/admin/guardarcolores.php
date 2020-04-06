<?php
    include ("../../config.php");
	if(isset($_GET['cantidades100'])&&isset($_GET['cantidades200'])&&isset($_GET['cantidades500'])&&isset($_GET['cantidades1000'])&&isset($_GET['cantidades5000'])&&isset($_GET['cantidades10000'])){
		$preciounitario = $_GET['preciounitario'];
		$cantidades100 = $_GET['cantidades100'];
		$cantidades200 = $_GET['cantidades200'];
		$cantidades500 = $_GET['cantidades500'];
		$cantidades1000 = $_GET['cantidades1000'];
		$cantidades5000 = $_GET['cantidades5000'];
		$cantidades10000 = $_GET['cantidades10000'];

		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$results = $mysqli->query("UPDATE cot2_colores SET preciounitario = '$preciounitario', cantidades100 = '$cantidades100', cantidades200 = '$cantidades200', cantidades500 = '$cantidades500', cantidades1000 = '$cantidades1000', cantidades5000 = '$cantidades5000', cantidades10000 = '$cantidades10000' WHERE id = '1'");
		if($results){
			$msg = "Las Configuraciones fueron actualizadas de manera exitosa!";
			echo $msg;
		}else{
			echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
	}
?>