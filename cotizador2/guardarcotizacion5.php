<?php
	include ("../config.php");
	$total = $_GET['total'];
	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = "SELECT * FROM cot2_configuraciones WHERE id = 1";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$row = $result->fetch_assoc();
	$descuento=$row['descuento'];
	$activardescuento=$row['activardescuento'];
	if($activardescuento==1){
		$descuentoporcentaje = $descuento/100;
		$descuentototal = $total*$descuentoporcentaje;
		$totaltotal = $total - $descuentototal;
	}else{
		$totaltotal = $total;
	}
	mysqli_close($mysqli);

	//echo '<h3 class="text-center">DESCUENTO: $ '.$descuentototal.'</h3><br /><h2 class="text-center">TOTAL: $ '.$totaltotal.'</h2>';
	$miArray = array("descuentototal"=>"$descuentototal", "total"=>"$totaltotal", "descuento"=>"$descuento");
	echo json_encode($miArray);
?>