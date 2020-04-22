<?php
	include ("../config.php");
	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = "SELECT * FROM cot2_rubros";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$arreglo = [];
	while($row = $result->fetch_assoc()) {
		$arreglo[$row['id']] = $row['descripcion'];
	}
	echo json_encode($arreglo);
	mysqli_close($mysqli);
?>
