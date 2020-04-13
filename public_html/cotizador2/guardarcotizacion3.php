<?php
    include ("../config.php");
	function limpiar_caracteres_especiales($s) {
		$s = str_replace("á","&#225;",$s);
		$s = str_replace("é","&#233;",$s);
		$s = str_replace("í","&#237;",$s);
		$s = str_replace("ó","&#243;",$s);
		$s = str_replace("ú","&#250;",$s);
		$s = str_replace("ñ","&#241;",$s);
		return $s;
	}
	if(isset($_GET['idcot'])){
		$idcot = $_GET['idcot'];
		$email = $_GET['email'];
		$descuentototal = $_GET['descuentototal'];
		$observacion2 = utf8_decode($_GET['observaciones']);
		$totaltotal = $_GET['total'];
		$mensajeemail = utf8_decode($_GET['mensajeemail']);
		$mysqli2 = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli2->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
		$mysqli2->set_charset("utf8");
		$query2 = "SELECT * FROM cot2_configuraciones WHERE id = '1'";
		$result2 = $mysqli2->query($query2) or die($mysqli2->error.__LINE__);
		$row2 = $result2->fetch_assoc();
		$emailconf = $row2['emailconf'];
		$mensajeprincipal1 = $row2['mensajeprincipal'];
		$mensajefinal = $row2['mensajefinal'];
		$mensajecotizador2 = $row2['mensajecotizador'];
		$mail_logo = $row2['mail_logo'];
		$descuento = $row2['descuento'];
		$activardescuento = $row2['activardescuento'];
		$mensajecotizador = str_replace ( "+descuento+" , $descuento , $mensajecotizador2);
		$mensajeprincipal = str_replace ( "+cliente+" , $nombre." ".$apellidos , $mensajeprincipal1);
		$tituloemail = $row2['asuntoemail'];
		if($observacion2!=""){
			$observaciones = "<tr><td width='267' style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'>".$observacion2."</td></tr>";
		}else{
			$observaciones = "";
		}
		$iva = $row2['iva'];
		mysqli_close($mysqli2);
		$detalletabla="";
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		$query = "SELECT * FROM cot2_cotizaciondet WHERE idcot='$idcot' ORDER BY id ASC";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		while($row = $result->fetch_assoc()) {
			$detalletabla .= "<tr style='text-align:center;'>";
			$detalletabla .= "<td>".$row['cantidad']."</td>";
			$detalletabla .= "<td>".$row['codigo']."</td>";
			$detalletabla .= "<td>".$row['descripcion']."</td>";
			$detalletabla .= "<td>".$row['colores']."</td>";
			$detalletabla .= "<td>$ ".number_format($row['precio'], 2, ',', '.')."</td>";
			$detalletabla .= "<td>$ ".number_format($row['total'], 2, ',', '.')."</td>";
			$detalletabla .= "</tr>";
		}
		mysqli_close($mysqli);
		
		$_GET['id'] = $idcot;
		$_GET['mode']='storein';
		include 'admin/generar-pdf.php';
		$file = "$pdf_path/$filename"."_$idcot.pdf";

		session_start();
		$usuario = empty($_SESSION['userPersona']) ? '-' : $_SESSION['userPersona'];
		$email = $_GET['email']; // ¡¡CUIDADO!!: generar-pdf.php estaba sobreescribiendo el valor de $email
		include ("email.php");
		attachment_mail($email, $tituloemail, limpiar_caracteres_especiales($mensaje), $emailconf, "Cotizacion Pisos Goma Eva", $file);
		
		echo "Cotizacion realizada con exito!";
		print_r([
			"id_cotizacion" => $idcot,
			"detalle_tabla" => $detalletabla,
			"attachment_mail_data" => [
				"email" => $email,
				"tituloemail" => $tituloemail,
				//"mensaje" => limpiar_caracteres_especiales($mensaje),
				"emailconf" => $emailconf,
			],
		]);
	}
	
	function attachment_mail($to, $subject, $body, $from, $fromName, $file) {
		
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; //boundary 

		$headers = "From: $fromName <".$from.">"; //header for sender info
		$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; //headers for attachment

		$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
		"Content-Transfer-Encoding: 7bit\n\n" . $body . "\n\n"; //multipart boundary 

		//preparing attachment
		if(!empty($file) > 0){
			if(is_file($file)){
				$message .= "--{$mime_boundary}\n";
				$fp =    @fopen($file,"rb");
				$data =  @fread($fp,filesize($file));

				@fclose($fp);
				$data = chunk_split(base64_encode($data));
				$message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" . 
				"Content-Description: ".basename($file)."\n" .
				"Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" . 
				"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
			}
		}
		$message .= "--{$mime_boundary}--";
		$returnpath = "-f" . $from;

		$sent = @mail($to, $subject, $message, $headers, $returnpath); 

		if ( file_exists($file) )
			unlink( $file );
		
		return $sent;
	}
?>