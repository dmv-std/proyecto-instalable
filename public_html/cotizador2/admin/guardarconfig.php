<?php
    include ("../../config.php");
	if(isset($_GET['emailconf'])&&isset($_GET['mensajecotizador'])&&isset($_GET['mensajeprincipal'])
		&&isset($_GET['mensajefinal'])&&isset($_GET['habilitar_impresion'])&&isset($_GET['iva'])&&isset($_GET['mail_logo'])){
		
		$emailconf = $_GET['emailconf'];
		$asuntoemail = $_GET['asuntoemail'];
		$mensajecotizador = utf8_decode($_GET['mensajecotizador']);
		$mensajeprincipal = utf8_decode($_GET['mensajeprincipal']);
		$mensajefinal = utf8_decode($_GET['mensajefinal']);
		$habilitar_impresion = $_GET['habilitar_impresion'];
		$iva = $_GET['iva'];
		$mail_logo = $_GET['mail_logo'];

		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$results = $mysqli->query("UPDATE cot2_configuraciones
			SET emailconf = '$emailconf',
				asuntoemail = '$asuntoemail',
				mensajecotizador = '$mensajecotizador',
				mensajeprincipal = '$mensajeprincipal',
				mensajefinal = '$mensajefinal',
				habilitar_impresion = '$habilitar_impresion',
				iva = '$iva',
				mail_logo = '$mail_logo'
			WHERE id = '1'");
		if($results){
			$msg = "Las Configuraciones fueron actualizadas de manera exitosa!";
			echo $msg;
		}else{
			echo 'Error : ('. $mysqli->errno .') '. $mysqli->error . "";
		}
		mysqli_close($mysqli);
	}
?>