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
	$subrubro = isset($_GET['idsub']) ? $_GET['idsub'] : 0;
	if($rubro==0){	
		$where = "SELECT * FROM cot2_productos";
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$query = $where;
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		while($row = $result->fetch_assoc()) {
			$arreglo[$row['id']] = $row['descripcion']." ". $row['medida']." Espesor ". $row['espesor'];
		}
		echo json_encode($arreglo);
		mysqli_close($mysqli);
	}else{
		if ($subrubro==0){
			$where = "SELECT * FROM cot2_productos WHERE rubro = '$rubro'";
		}else{
			$where = "SELECT * FROM cot2_productos WHERE rubro = '$rubro' AND subrubro = '$subrubro'";
		}
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$query = $where;
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		while($row = $result->fetch_assoc()) {
			$arreglo[$row['id']] = $row['descripcion']." ". $row['medida'];
		}
		echo json_encode($arreglo);
		mysqli_close($mysqli);		
		
	}
?>