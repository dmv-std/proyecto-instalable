<?php
if (isset($_GET['id'])) {
	include ("../config.php");

	$idpresupuesto = $_GET['id'];

	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$mysqli->set_charset("utf8");


	$query2 = "SELECT * FROM presupuestos_configuracion WHERE id = '1'";
	$result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
	$row2 = $result2->fetch_assoc();
	$row2['reenvio_datos_json'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row2['reenvio_datos_json']),ENT_NOQUOTES, 'UTF-8');
	$correos = json_decode($row2['reenvio_datos_json']);

	$fecha_reenvios = array();
	$error_db = "";

	foreach ($correos as $key => $correo) {

		if ($correo->habilitado) {

			$fecha = date('Y-m-d', strtotime('+'.$correo->dias.' days'));

			$arg = '[{"indice": "' . $key . '"}]';

			$results = $mysqli->query("INSERT INTO presupuestos_tareas (fecha, funcion, arg, id_presupuesto, id_accion)
				values('$fecha', 'reenviarCotizacion', '$arg', '$idpresupuesto', '0')");

			if($results) {
				//echo "INSERT INTO tareas (fecha, funcion, arg, id_cotizacion, id_accion) values('$fecha', 'reenviarCotizacion', '$arg', '$idpresupuesto', '0')\r\n";
				$fecha_reenvios[] = date("D (d/M/Y)", strtotime($fecha));
			} else {
				echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
				$error_db = "Una o más consultas no pudieron ser realizadas por un error de conexión con la base de datos!";
			}
		}
	}

	if ( count($fecha_reenvios)>0 )
		$observacion = "Se ha programado un REENVIO para el presupuesto ($idpresupuesto) para los siguientes días: " . implode(", ", $fecha_reenvios) . ".";
	else
		$observacion = "";
	
	if ($error_db)
		$observacion = $observacion . ($observacion ? " " : "") . $error_db;
	
	$fecha = date('Y-m-d H:i:s');
	
	if ($observacion)
		$results_obs = $mysqli->query("INSERT INTO presupuestos_observaciones (id_presupuesto, observacion, fecha, tipo)
			values('$idpresupuesto', '$observacion', '$fecha', 'PROGRAMAR REENVIO')");

	mysqli_close($mysqli);
}

?>