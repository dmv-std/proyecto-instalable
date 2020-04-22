<?php
	include ("../config.php");
	
	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){ // check connection
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$mysqli->set_charset("utf8");
	
	// Configuración
	$result = $mysqli->query("SELECT * FROM presupuestos_configuracion WHERE id = '1'") or die($mysqli->error.__LINE__);
	$config = $result->fetch_assoc();
	
	$mail_logo = $config['mail_logo'];
	$mensajeprincipal = _markups(htmlentities($config['mensajeprincipal'], ENT_NOQUOTES, 'UTF-8'));
	$mensajeintermedio = _markups(htmlentities($config['mensajeintermedio'], ENT_NOQUOTES, 'UTF-8'));
	$mensajefinal = _markups(htmlentities($config['mensajefinal'], ENT_NOQUOTES, 'UTF-8'));
	
	$asuntoemail = $config['asuntoemail'];
	$emailconf = isset($_GET['reenviarremitenteemail']) ? $_GET['reenviarremitenteemail'] : $config['emailconf'];
	$emailremitente = isset($_GET['reenviarremitente']) ? $_GET['reenviarremitente'] : $config['emailremitente'];
	
	// Prespuesto
	$query = "SELECT * FROM presupuestos WHERE id='$id'";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
	$presupuesto = $result->fetch_assoc();
	
	$detalles = explode("\n", $presupuesto['detalles']);
	if ($presupuesto['condiciones'])
		$condiciones = _markups(htmlentities($presupuesto['condiciones'], ENT_NOQUOTES, 'UTF-8'));
	else
		$condiciones = "";
	
	$cliente = $presupuesto['nombre'] . " " . $presupuesto['apellido'];
	$nombre = $presupuesto['nombre'];
	$apellido = $presupuesto['apellido'];
	$correo = $presupuesto['correo'];
	$telf = $presupuesto['telefono'];
	
	$mensajeprincipal		= str_replace("+cliente+", $cliente, $mensajeprincipal);
	$mensajeintermedio		= str_replace("+cliente+", $cliente, $mensajeintermedio);
	$mensajefinal			= str_replace("+cliente+", $cliente, $mensajefinal);
	
	$mensajeprincipal		= str_replace("+nombre+", $nombre, $mensajeprincipal);
	$mensajeintermedio		= str_replace("+nombre+", $nombre, $mensajeintermedio);
	$mensajefinal			= str_replace("+nombre+", $nombre, $mensajefinal);
	
	$mensajeprincipal		= str_replace("+apellido+", $apellido, $mensajeprincipal);
	$mensajeintermedio		= str_replace("+apellido+", $apellido, $mensajeintermedio);
	$mensajefinal			= str_replace("+apellido+", $apellido, $mensajefinal);
	
	$mensajeprincipal		= str_replace("+correo+", $correo, $mensajeprincipal);
	$mensajeintermedio		= str_replace("+correo+", $correo, $mensajeintermedio);
	$mensajefinal			= str_replace("+correo+", $correo, $mensajefinal);
	
	$mensajeprincipal		= str_replace("+telf+", $telf, $mensajeprincipal);
	$mensajeintermedio		= str_replace("+telf+", $telf, $mensajeintermedio);
	$mensajefinal			= str_replace("+telf+", $telf, $mensajefinal);
?>
<br /><br /><html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<title><?php echo $sitename?></title>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
	</head>
	<body style='margin: 0; padding: 0;'>
		<table border='0' cellpadding='0' cellspacing='0' width='100%'>
			<tr>
				<td>
					<table align='center' border='0' cellpadding='0' cellspacing='0' width='800' style='border-collapse: collapse;'>
						<tr>
							<td align='center' style='padding: 40px 0 30px 0;'>
								<img src='<?php echo $mail_logo ?>' alt='Presupuestos <?php echo $sitename ?>' style='display: block;' />
							</td>
						</tr>
						<tr>
							<td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>
								Presupuesto
							</td>
						</tr>
						<tr><td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;</td></tr>
						<tr>
							<td style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'>
								<?php echo $mensajeprincipal ?>
							</td>.
						</tr>
						<tr><td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;</td></tr>
						<tr>
							<td align='center' bgcolor='#5bc0de' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;padding: 10px 15px; border-bottom: 1px solid transparent;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: transparent;border-top-left-radius: 3px;border-radius: 3px;'>
 								DATOS DE PRESUPUESTO
 							</td>
						</tr>
						<tr>
							<td>
								<table align='center' border='0' cellpadding='0' cellspacing='0' width='800' style='border-collapse: collapse;'>
		 							<thead>
										<tr>
											<th>DETALLES</th>
										 </tr>
									</thead>
									<tbody>
										<?php foreach($detalles as $detalle): ?>
											<?php if ($detalle): ?>
												<tr>
													<td><?php echo _markups(htmlentities($detalle, ENT_NOQUOTES, 'UTF-8')) ?></td>
												</tr>
											<?php endif ?>
										<?php endforeach ?>
									</tbody>
								</table>
							</td>
						</tr>
						<tr><td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;</td></tr>
						<tr><td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;</td></tr>
						<?php if($condiciones): ?>
							<tr>
								<td width='267' style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'>
									<?php echo $condiciones ?>
								</td>
							</tr>
						<tr><td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;</td></tr>
						<?php endif ?>
						<tr>
							<td width='267' style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'>
								<br><strong>Vendedor:</strong> <?php echo $usuario ?><br/><br/>
								<?php echo $mensajeintermedio ?>
							</td>
						</tr>
						<tr><td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;</td></tr>
						<tr>
							<td style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'>
								<?php echo $mensajefinal ?>
							</td>.
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>