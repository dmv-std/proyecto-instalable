<?php include ("../config.php");

	if ( isset($_GET['max_size']) && isset($_GET['dias_borrado']) )
	{
		$max_size = $_GET['max_size'];
		$dias_borrado = $_GET['dias_borrado'];

		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		
		$results = $mysqli->query("UPDATE presupuestos_configuracion
			SET max_size = '$max_size',
				dias_borrado = '$dias_borrado'
			WHERE id = 1");

		if($results) {
			echo "La configuración se ha guardado con éxito!";
		}else{
			echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
		
	} else header("Location: configuracion");
?>