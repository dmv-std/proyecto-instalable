<?php if (file_exists("../../config.php"))
        include ("../../config.php");
    else { header("location: ../../instalador"); exit(); }
?>
<?php

$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if($mysqli->connect_errno > 0){ // check connection
    die('Unable to connect to database [' . $db->connect_error . ']');
}
$mysqli->set_charset("utf8");

$fecha = date('Y-m-d');
$query = "SELECT * FROM cot2_tareas WHERE fecha = '$fecha'";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

echo "Inicio de ejecucion del CRON (" . date('d/m/Y, H:i:s') . ").<br>";

while($row = $result->fetch_assoc()) {
	
    echo "Se ha conseguido una nueva tarea para ejecutar el dia de hoy. Tarea con ID: ".$row['id']." funcion: ".$row['funcion']."<br>";
	
	if($row['funcion']=='reenviarCotizacion' || $row['funcion']=='notificarConcretado') {

		$query2 = "SELECT * FROM cot2_configuraciones WHERE id = '1'";
		$result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
		$row2 = $result2->fetch_assoc();
		
		$remitentenombre = $row2['reenvio_remitente_nombre'];
		$remitenteemail = $row2['reenvio_remitente_email'];
		
		if ( $row['funcion']=='reenviarCotizacion' ) {
			$row2['reenvio_datos_json'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row2['reenvio_datos_json']),ENT_NOQUOTES, 'UTF-8');
			$correos = json_decode($row2['reenvio_datos_json']);
		} else if ( $row['funcion']=='notificarConcretado' ) {
			$row2['correos_concretado_json'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row2['correos_concretado_json']),ENT_NOQUOTES, 'UTF-8');
			$correos = json_decode($row2['correos_concretado_json']);
		}
		
		$query2 = "SELECT cot2_cotizacion.*
			FROM cot2_cotizacion
			INNER JOIN cot2_tareas ON cot2_cotizacion.id = cot2_tareas.id_cotizacion
			WHERE cot2_tareas.id = '".$row['id']."'";
		$result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
		$cotizacion = $result2->fetch_assoc();
		
		$mysqli2 = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli2->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli2->set_charset("utf8");
		$query2 = "SELECT id FROM sist_usuarios WHERE user = '".$cotizacion['usuario']."'";
		$result2 = $mysqli2->query($query2) or die($mysqli2->error.__LINE__);
		$user = $result2->fetch_assoc();
		mysqli_close($mysqli2);
		
		if ($cotizacion['estatus'] == 'COTIZADO' || $row['funcion']=='notificarConcretado') {
			
			$row['arg'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row['arg']),ENT_NOQUOTES, 'UTF-8');
			$argumentos = json_decode($row['arg']);
			
			if(isset($argumentos[0]->indice) && isset($remitentenombre) && isset($remitenteemail)) {
				
				$i = $argumentos[0]->indice;
				
				if ( !isset($correos[$i]->asunto) || !isset($correos[$i]->correo) )
					die( "Existio un problema al ejecutar la tarea referida, posee argumentos no validos<br>" );
				
				$asunto = html_entity_decode($correos[$i]->asunto, ENT_QUOTES, "UTF-8");
				
				$mensaje = "Estimado ".$cotizacion['nombre'].",<br>";
				$mensaje = $mensaje.preg_replace("/###/","<br>", $correos[$i]->correo);
				$mensaje = $mensaje."<br> Saludos cordiales.";
				
				reenviarCorreoAuto($cotizacion['id'], $cotizacion['email'], $asunto, $mensaje, $remitentenombre, $remitenteemail, $user['id'], $cotizacion['usuario']);
				
				js_console_log([
					"id" => $cotizacion['id'],
					"email" => $cotizacion['email'],
					"asunto" => $asunto,
					"mensaje" => $mensaje,
					"remitente" => $remitentenombre,
					"remitenteemail" => $remitenteemail,
					"user_id" => $user['id']
				]);
				
		    } else die( "Existio un problema al ejecutar la tarea referida, posee argumentos no validos<br>" );
			
		} else js_console_log(["default" => "Tarea con id " . $cotizacion['id'] . " no necesita ser ejecutada porque presenta el estatus: CONCRETADO"]);
		
	} else die( "Ocurrió un problema al ejecutar la tarea referida, posee función no válida<br>" );
	
    $mysqli->query("DELETE FROM cot2_tareas WHERE id =".$row['id']);
}

echo "Cron finalizado.<br>";

mysqli_close($mysqli);





function reenviarCorreoAuto($idcot, $email, $asuntoemail, $mensaje, $remitente, $remitenteemail, $id_user, $usuario)
{
	include ("../../config.php");

	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
	$mysqli->set_charset("utf8");
	$query2 = "SELECT * FROM cot2_configuraciones WHERE id = '1'";
	$result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
	$row2 = $result2->fetch_assoc();
	$emailconf = $row2['emailconf'];
	$mensajeprincipal1 = $row2['mensajeprincipal'];
	$mensajefinal = $row2['mensajefinal'];
	$activardescuento = $row2['activardescuento'];
	$descuentox = $row2['descuento'];
	$mensajedescuento1 = isset($row2['mensajedescuento']) ? $row2['mensajedescuento'] : "";
	if (!isset($nombre) && !isset($apellidos)) {
		$nombre = ""; $apellidos = "";
	}
	$mensajeprincipal = str_replace ( "+cliente+" , $nombre." ".$apellidos , $mensajeprincipal1);
	mysqli_close($mysqli);
	
	$mysqli5 = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli5->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
	$mysqli5->set_charset("utf8");
	$query5 = "SELECT * FROM cot2_cotizacion WHERE id = '$idcot'";
	$result5 = $mysqli5->query($query5) or die($mysqli5->error.__LINE__);
	$row5 = $result5->fetch_assoc();
	
	$descuento = $row5['descuento'];
	if($descuento>0) {
		$mensajedescuento = str_replace ( "+descuento+" , $descuento , $mensajedescuento1);
		$descuentopromocional = "<tr width='800'><td align='center' bgcolor='#5bc0de' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;padding: 10px 15px; border-bottom: 1px solid transparent;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: transparent;border-top-left-radius: 3px;border-radius: 3px;'>".$mensajedescuento."</td></tr>";
	} else {
		$descuentopromocional = "";
	}
	mysqli_close($mysqli5);
				
	$observacion = utf8_decode("La cotización ha sido reenviada de manera AUTOMÁTICA el día " . date('d/m/Y') . " a las " . date('h:i A') . " al siguiente email: ".$email." con el siguiente mensaje: ".(isset($mensajeemail) ? $mensajeemail : ""));
    $fecha = date('Y-m-d H:i:s');
    $tipo = "REENVIO DE COTIZACION";

    $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
    if($mysqli->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
    }
    $results = $mysqli->query("INSERT INTO cot2_observaciones (idcotizacion, observacion, fecha, tipo, id_user) values('$idcot', '$observacion', '$fecha', '$tipo', '$id_user')");
    if($results)
    {
    	if (file_exists("email.php")) include ("email.php");
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=ISO-8859-1' . "\r\n";
		$cabeceras .= 'Disposition-Notification-To: '.$remitenteemail.'' . "\n";
        $cabeceras .= 'From: '.$remitente.' <'.$remitenteemail.'>' . "\r\n";
		mail($email, $asuntoemail, limpiar_caracteres_especiales($mensaje), $cabeceras);
    }
    else
    {
        echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
    }
    mysqli_close($mysqli);
}


function limpiar_caracteres_especiales($s)
{
	$s = str_replace("á","&#225;",$s);
	$s = str_replace("é","&#233;",$s);
	$s = str_replace("í","&#237;",$s);
	$s = str_replace("ó","&#243;",$s);
	$s = str_replace("ú","&#250;",$s);
	$s = str_replace("ñ","&#241;",$s);
	//para ampliar los caracteres a reemplazar agregar lineas de este tipo:
	//$s = str_replace("caracter-que-queremos-cambiar","caracter-por-el-cual-lo-vamos-a-cambiar",$s);
	return $s;
}


function js_console_log($data)
{
	if (isset($data['default']))
		echo '
	<script type="text/javascript">
	console.log("' . $data['default'] . '");
	console.log("");
	</script>';

	else echo '
	<script type="text/javascript">
	console.log("Reenvio de cotizacion con id: ' . $data['id'] . ', enviando correo con los siguientes datos:");
	console.log("Destinatario: ' . $data['email'] . '");
	console.log("Asunto: ' . $data['asunto'] . '");
	console.log("Mensaje: ' . $data['mensaje'] . '");
	console.log("Remitente: ' . $data['remitente'] . ' <' . $data['remitenteemail'] . '>");
	console.log("Id de usuario: ' . $data['user_id'] . '");
	console.log("");
	</script>';
}
?>