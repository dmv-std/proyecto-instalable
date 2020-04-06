<?php
	include ("../config.php");
	if(isset($_GET['id'])){
		if($_GET['id']==0){
			$rubro = 0;
		}else{
			$rubro = $_GET['id'];
		}
	}else{
		$rubro = 0;
	}
	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$mysqli->set_charset("utf8");
	if($rubro==0){		
		$query = "SELECT * FROM cot2_subrubros";
	}else{
		$query = "SELECT * FROM cot2_subrubros WHERE idrubro = '$rubro'";
	}
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$arreglo=[];
	while($row = $result->fetch_assoc()) {
		$arreglo[$row['id']] = $row['descripcion'];
	}
	echo json_encode($arreglo);
	mysqli_close($mysqli);
?>
