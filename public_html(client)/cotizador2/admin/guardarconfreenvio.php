<?php include ("../../config.php");

	if ( isset($_GET['correos'])
		|| isset($_GET['remitentenombre'])
		|| isset($_GET['remitenteemail']) )
	{
		
		$remitentenombre = $_GET['remitentenombre'];
		$remitenteemail = $_GET['remitenteemail'];
		$correos = $_GET['correos'];

		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");


		$results = $mysqli->query("UPDATE cot2_configuraciones
			SET reenvio_remitente_nombre = '$remitentenombre',
				reenvio_remitente_email = '$remitenteemail',
				reenvio_datos_json = '$correos'
			WHERE id = 1");

		if($results) {

			echo "Los datos fueron actualizados de manera exitosa!";

		}else{
			echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
		
	} else header("Location: configuracion");


?>