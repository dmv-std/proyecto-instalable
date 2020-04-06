<?php 
	include ("../config.php");
	if(isset($_GET['id'])){
        $idform = $_GET['id'];
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$query = "SELECT * FROM cot2_productos WHERE id = '$idform'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$row = $result->fetch_assoc();
		$cantidadminima=$row['cantidadminima'];
		echo $cantidadminima;
		mysqli_close($mysqli);
	}	
?>