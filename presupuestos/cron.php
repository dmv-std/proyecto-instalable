<?php if (file_exists("../config.php"))
        include ("../config.php");
    else { header("location: ../instalador"); exit(); }
?>
<?php

$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if($mysqli->connect_errno > 0){ // check connection
    die('Unable to connect to database [' . $db->connect_error . ']');
}
$mysqli->set_charset("utf8");

$fecha = date('Y-m-d');
$query = "SELECT * FROM presupuestos_tareas WHERE fecha = '$fecha'";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

echo "Inicio de ejecucion del CRON (" . date('d/m/Y, H:i:s') . ").<br>";

while($row = $result->fetch_assoc()) {
	
    echo "Se ha conseguido una nueva tarea para ejecutar el dia de hoy. Tarea con ID: ".$row['id']." funcion: ".$row['funcion']."<br>";
	
	if($row['funcion']=='reenviarCotizacion' || $row['funcion']=='notificarConcretado') {

		$query2 = "SELECT * FROM presupuestos_configuracion WHERE id = '1'";
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
		
		$query2 = "SELECT presupuestos.*
			FROM presupuestos
			INNER JOIN presupuestos_tareas ON presupuestos.id = presupuestos_tareas.id_presupuesto
			WHERE presupuestos_tareas.id = '".$row['id']."'";
		$result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
		$presupuesto = $result2->fetch_assoc();
		
		if ($presupuesto['estado'] == 'COTIZADO' || $row['funcion']=='notificarConcretado') {
			
			$row['arg'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row['arg']),ENT_NOQUOTES, 'UTF-8');
			$argumentos = json_decode($row['arg']);
			
			if(isset($argumentos[0]->indice) && isset($remitentenombre) && isset($remitenteemail)) {
				
				$i = $argumentos[0]->indice;
				
				if ( !isset($correos[$i]->asunto) || !isset($correos[$i]->correo) )
					die( "Existio un problema al ejecutar la tarea referida, posee argumentos no validos<br>" );
				
				$asunto = html_entity_decode($correos[$i]->asunto, ENT_QUOTES, "UTF-8");
				
				$mensaje = "Estimado ".$presupuesto['nombre'].",<br>";
				$mensaje = $mensaje.preg_replace("/###/","<br>", $correos[$i]->correo);
				$mensaje = $mensaje."<br> Saludos cordiales.";
				
				reenviarCorreoAuto($presupuesto['id'], $presupuesto['correo'], $asunto, $mensaje, $remitentenombre, $remitenteemail, $presupuesto['id_usuario'], "Dummy text");
				
				js_console_log([
					"id" => $presupuesto['id'],
					"email" => $presupuesto['correo'],
					"asunto" => $asunto,
					"mensaje" => $mensaje,
					"remitente" => $remitentenombre,
					"remitenteemail" => $remitenteemail,
					"user_id" => $presupuesto['id_usuario']
				]);
				
		    } else die( "Existio un problema al ejecutar la tarea referida, posee argumentos no validos<br>" );
			
		} else js_console_log(["default" => "Tarea con id " . $presupuesto['id'] . " no necesita ser ejecutada porque presenta el estatus: CONCRETADO"]);
		
	} else die( "Ocurrió un problema al ejecutar la tarea referida, posee función no válida<br>" );
	
    $mysqli->query("DELETE FROM presupuestos_tareas WHERE id =".$row['id']);
}

// Configuración
$result = $mysqli->query("SELECT * FROM presupuestos_configuracion WHERE id = '1'") or die($mysqli->error.__LINE__);
$row = $result->fetch_assoc();
$dias_borrado = $row['dias_borrado'];

// Borrar archivos cuya antigüedad sea igual (o mayor) a la cantidad de dias de borrado
$result = $mysqli->query("SELECT * FROM presupuestos_archivos") or die($mysqli->error.__LINE__);
while($row = $result->fetch_assoc()) {
	
	$id = $row['id'];
	$file = $row['file'];
	$dias = getDaysBetween(date("Y-m-d", strtotime($row['fecha'])), date("Y-m-d"));
	
	if ($dias >= $dias_borrado) {
		
		$basepath = $_SERVER['DOCUMENT_ROOT'] . "/presupuestos/archivos";
		if ( file_exists("$basepath/$file") )
			unlink( "$basepath/$file" );
		
		$result2 = $mysqli->query("DELETE FROM presupuestos_archivos WHERE id = $id");
		if ($result2){
			echo "Archivo <strong>$file</strong> alcanzó o superó los <em>$dias_borrado dias</em> de antigüedad y ha sido eliminado...<br />";
		}
	}
}

echo "Cron finalizado.<br>";

mysqli_close($mysqli);





function reenviarCorreoAuto($id, $email, $asuntoemail, $mensaje, $remitente, $remitenteemail, $id_user, $usuario)
{
	include ("../config.php");

	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
	$mysqli->set_charset("utf8");
			
	$observacion = "La cotización ha sido reenviada de manera AUTOMÁTICA el día " . date('d/m/Y') . " a las " . date('h:i A') . " al siguiente email: ".$email." con el siguiente mensaje: ".(isset($mensajeemail) ? $mensajeemail : "");
    $fecha = date('Y-m-d H:i:s');
    $tipo = "REENVIO DE PRESUPUESTO";

    $results = $mysqli->query("INSERT INTO presupuestos_observaciones (id_presupuesto, observacion, fecha, tipo, id_user) values('$id', '$observacion', '$fecha', '$tipo', '$id_user')");
    if($results)
    {
		ob_start();
		include ("email.php");
		$mensaje .= ob_get_clean();

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

function _markups($s) {
	$markups = [
		//		regular expression		replacement
		array(	"/\*\*\*(.+?)\*\*\*/",	"<strong><em>$1</em></strong>"	),
		array(	"/\*\*(.+?)\*\*/",		"<strong>$1</strong>"			),
		array(	"/\*(.+?)\*/",			"<em>$1</em>"					),
		array(	"/_(.+?)_/",			"<u>$1</u>"						),
		array(	"/~(.+?)~/",			"<s>$1</s>"						),
		array(	"/\n/",					"<br />"						),
			array(	"/\!\[(.*?)\]\((https?:\/\/.+?\.(?:jpe?g|png|gif))\)/",		'<img alt="$1" src="$2" style="max-width:100%" />'		),
			array(	"/\!\((https?:\/\/.+?\.(?:jpe?g|png|gif))\)/",				'<img src="$1" style="max-width:100%" />'				),
	];
	
	foreach ($markups as $markup) {
		$s = preg_replace($markup[0], $markup[1], $s);
	}
	return $s;
}

function getDaysBetween($earlier, $later) {
	// https://www.codexworld.com/how-to/get-number-of-days-between-two-dates-php/
	// https://stackoverflow.com/questions/2040560/finding-the-number-of-days-between-two-dates
	$date1 = date_create($earlier);
	$date2 = date_create($later);
	$diff = date_diff($date1,$date2);
	return $diff->format("%a");
}
?>