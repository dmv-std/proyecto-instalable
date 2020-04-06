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
    if(isset($_GET['reenviaridcotizacion'])){
        $idcot = $_GET['reenviaridcotizacion'];
        $email = $_GET['reenviaremailemail'];
        $mensajeemail = utf8_decode($_GET['reenviarmensajeemail']);
        $remitente = utf8_decode($_GET['reenviarremitente']);
        $remitenteemail = $_GET['reenviarremitenteemail'];
        $id_user = $_GET['iduser'];
        $observaciones = "";
        
		$mysqli2 = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli2->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
		$mysqli2->set_charset("utf8");
		$query2 = "SELECT * FROM cot2_configuraciones WHERE id = '1'";
		$result2 = $mysqli2->query($query2) or die($mysqli2->error.__LINE__);
		$row2 = $result2->fetch_assoc();
		$mensajeprincipal1 = $row2['mensajeprincipal'];
		$mensajefinal = $row2['mensajefinal'];
		$mensajecotizador2 = $row2['mensajecotizador'];
		$descuento = $_GET['reenviardescuentoporcentaje'];
		$activardescuento = $row2['activardescuento'];
		$asuntoemail = $row2['asuntoemail'];
		$mensajecotizador = str_replace ( "+descuento+" , $descuento , $mensajecotizador2);
		if (!isset($nombre)) $nombre = "";
		if (!isset($apellidos)) $apellidos = "";
		$mensajeprincipal = str_replace ( "+cliente+" , $nombre." ".$apellidos , $mensajeprincipal1);
		$iva = $row2['iva'];
		$mail_logo = $row2['mail_logo'];
		mysqli_close($mysqli2);
		
		$observacion = "El usuario ".$_SESSION['nombrePersona']." reenvio la cotizacion al siguiente email: ".$email." con el siguiente mensaje: ".$mensajeemail;
        $fecha = date('Y-m-d H:i:s');
        $tipo = "REENVIO DE COTIZACION";
		$descuentototal = $_GET['reenviardescuento'];
		$totaltotal = $_GET['reenviartotal'];
		$detalletabla="";
		$mysqlix = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqlix->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqlix->set_charset("utf8");
		$queryx = "SELECT * FROM cot2_cotizaciondet WHERE idcot='$idcot' ORDER BY id ASC";
		$resultx = $mysqlix->query($queryx) or die($mysqlix->error.__LINE__);
		while($rowx = $resultx->fetch_assoc()) {
			$detalletabla .= "<tr style='text-align:center;'>";
			$detalletabla .= "<td>".$rowx['cantidad']."</td>";
			$detalletabla .= "<td>".$rowx['codigo']."</td>";
			$detalletabla .= "<td>".$rowx['descripcion']."</td>";
			$detalletabla .= "<td>".$rowx['colores']."</td>";
			$detalletabla .= "<td>$ ".number_format($rowx['precio'], 2, '.', ',')."</td>";
			$detalletabla .= "<td>$ ".number_format($rowx['total'], 2, '.', ',')."</td>";
			$detalletabla .= "</tr>";
		}
		mysqli_close($mysqlix);

        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $results = $mysqli->query("INSERT INTO cot2_observaciones (idcotizacion, observacion, fecha, tipo, id_user) values('$idcot', '$observacion', '$fecha', '$tipo', '$id_user')");
        if($results){
			include ("../email.php");
			$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
			$cabeceras .= 'Content-type: text/html; charset=ISO-8859-1' . "\r\n";
			$cabeceras .= 'Disposition-Notification-To: '.$remitenteemail.'' . "\n";
            $cabeceras .= 'From: '.$remitente.' <'.$remitenteemail.'>' . "\r\n";
			mail($email, $asuntoemail, limpiar_caracteres_especiales($mensaje), $cabeceras);
			echo "Cotizacion reenviada con exito!";
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
        
    }
?>