<?php require_once("../config.php");
	
	if(isset($_GET['reenviarremitente'])&&isset($_GET['reenviarremitenteemail'])&&isset($_GET['reenviaremailemail'])&&isset($_GET['reenviarmensajeemail'])&&isset($_GET['filename'])&&isset($_GET['id'])
		|| isset($_GET['id'])&&isset($_GET['email'])&&isset($_GET['filename'])){
		$id = $_GET['id'];
		$filename = $_GET['filename'];
		
		//$_GET['mode']='storein';
		//include 'generar-pdf.php';
		$file_url = "$license_server/assets/pdf/$filename"."_$id.pdf";
		$file = "$pdf_path/$filename"."_$id.pdf";
		file_put_contents($file, file_get_contents($file_url));
		
		ob_start();
		include "email.php";
		$mensaje = ob_get_clean();
		$mensaje = $_GET['reenviarmensajeemail'] ? $_GET['reenviarmensajeemail'].$mensaje : $mensaje;
		
		// Obteniendo todos los archivos del presupuesto
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){ // check connection
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		$result = $mysqli->query("SELECT * FROM presupuestos_archivos WHERE id_presupuesto = '$id'") or die($mysqli->error.__LINE__);
		$files = array( $file );
		while ($row = $result->fetch_assoc()) {
			$files[] = "$basepath/$presupuestos_archivos_path/" . $row['file'];
		}
		
		$email = $_GET['reenviaremailemail'] ? $_GET['reenviaremailemail'] : $_GET['email'];
		if (attachments_mail($email, $asuntoemail, $mensaje, $emailconf, $emailremitente, $files))
			echo "Correo enviado con éxito!";
		else
			echo "Ocurrió un error inesperado tratando de enviar el correo<br>Posiblemente, el correo remitente ingresado esté inactivo. Si no es asi, intente nuevamente...";

		if ( file_exists($file) )
			unlink( $file );
	}
	
	else if (isset($_GET['emailemail'])&&isset($_GET['asuntoemail'])&&isset($_GET['mensajeemail'])
		&&isset($_GET['remitente'])&&isset($_GET['remitenteemail']))
	{
        $emailemail = $_GET['emailemail'];
		$asuntoemail = mb_encode_mimeheader($_GET['asuntoemail']);
        $mensajeemail = _markups(htmlentities($_GET['mensajeemail'], ENT_NOQUOTES, 'UTF-8'));
        $remitente = mb_encode_mimeheader($_GET['remitente']);
        $remitenteemail = $_GET['remitenteemail'];
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$cabeceras .= 'Content-Transfer-Encoding: 8bit' . "\r\n";
		$cabeceras .= 'Disposition-Notification-To: '.$remitenteemail . "\n";
		$cabeceras .= 'From: '.$remitente.' <'.$remitenteemail.'>' . "\r\n";
		$resultado = mail($emailemail, $asuntoemail, $mensajeemail, $cabeceras);
		
		if ($resultado)
			$msg = "El correo electronico fue enviado de manera exitosa!";
		else $msg = "Ocurrió un error inesperado tratando de enviar el correo<br>Posiblemente, el correo remitente ingresado esté inactivo. Si no es asi, intente nuevamente... $resultado.";
		echo $msg;
	}
	
	function attachments_mail($to, $subject, $body, $from, $fromName, $files) {
		
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; //boundary 

		$headers = "From: $fromName <".$from.">"; //header for sender info
		$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; //headers for attachment

		$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
		"Content-Transfer-Encoding: 7bit\n\n" . $body . "\n\n"; //multipart boundary 

		//preparing attachments
		foreach ($files as $file) {
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
		}
		$message .= "--{$mime_boundary}--";
		$returnpath = "-f" . $from;

		$sent = @mail($to, $subject, $message, $headers, $returnpath); 
		
		return $sent;
	}
	
	function _markups($s) {
		$markups = [
			//		regular expression											replacement
			array(	"/\*\*\*(.+?)\*\*\*/",										"<strong><em>$1</em></strong>"	),
			array(	"/\*\*(.+?)\*\*/",											"<strong>$1</strong>"			),
			array(	"/\*(.+?)\*/",												"<em>$1</em>"					),
			array(	"/_(.+?)_/",												"<u>$1</u>"						),
			array(	"/~(.+?)~/",												"<s>$1</s>"						),
			array(	"/\n/",														"<br />"						),
			array(	"/\!\[(.*?)\]\((https?:\/\/.+?\.(?:jpe?g|png|gif))\)/",		'<img alt="$1" src="$2" style="max-width:100%" />'		),
			array(	"/\!\((https?:\/\/.+?(?:\.(?:jpe?g|png|gif))?)\)/",				'<img src="$1" style="max-width:100%" />'				),
		];
		
		foreach ($markups as $markup) {
			$s = preg_replace($markup[0], $markup[1], $s);
		}
		return $s;
	}
?>