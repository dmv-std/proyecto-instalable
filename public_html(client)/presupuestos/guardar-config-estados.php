<?php include ("../config.php");

	if ( isset($_GET['estados']) )
	{
		$estados = $_GET['estados'];

		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		
		$results = $mysqli->query("UPDATE presupuestos_configuracion
			SET estados = '$estados'
			WHERE id = 1");

		if($results) {
			echo "EXITO";
		}else{
			echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
		
	} else header("Location: configuracion");
?>