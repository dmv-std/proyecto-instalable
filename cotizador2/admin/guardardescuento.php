<?php
    include ("../../config.php");
	if(isset($_GET['descuento'])&&isset($_GET['activardescuento'])){
		$descuento = $_GET['descuento'];
		$activardescuento = $_GET['activardescuento'];
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$results = $mysqli->query("UPDATE cot2_configuraciones SET descuento = '$descuento', activardescuento = '$activardescuento' WHERE id = '1'");
		if($results){
			echo "Los detalles del descuento fueron actualizados de manera exitosa!";
		}else{
			echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
	}
?>