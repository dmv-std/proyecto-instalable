<?php
	session_start();
	include ("../../config.php");
	function limpiar_caracteres_especiales($s) {
		$s = str_replace("á","&#225;",$s);
		$s = str_replace("é","&#233;",$s);
		$s = str_replace("í","&#237;",$s);
		$s = str_replace("ó","&#243;",$s);
		$s = str_replace("ú","&#250;",$s);
		$s = str_replace("ñ","&#241;",$s);
		return $s;
	}
    
    if(isset($_GET['idcotizacion'])){
        $idcotizacion = $_GET['idcotizacion'];
        $emailemail = $_GET['emailemail'];
        $asuntoemail = utf8_decode($_GET['asuntoemail']);
        $mensajeemail = utf8_decode($_GET['mensajeemail']);
        $remitente = utf8_decode($_GET['remitente']);
        $remitenteemail = $_GET['remitenteemail'];
        $id_user = $_GET['iduser'];
        $observacion = "El usuario ".$_SESSION['nombre']." envio el siguien email: ".$asuntoemail." ".$mensajeemail;
        $fecha = date('Y-m-d H:i:s');
        $tipo = "CORREO ELECTRONICO";

        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $results = $mysqli->query("INSERT INTO cot2_observaciones (idcotizacion, observacion, fecha, tipo, id_user) values('$idcotizacion', '$observacion', '$fecha', '$tipo', '$id_user')");
        if($results){
           $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=ISO-8859-1' . "\r\n";
		$cabeceras .= 'Disposition-Notification-To: '.$remitenteemail.'' . "\n";
           $cabeceras .= 'From: '.$remitente.' <'.$remitenteemail.'>' . "\r\n";
           mail($emailemail, $asuntoemail, limpiar_caracteres_especiales($mensajeemail), $cabeceras);
           $msg = "El correo electronico fue enviado de manera exitosa!";
           echo $msg;
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
        
    }
?>