<?php echo $mensajeemail ?>
<br /><br /><html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
		<title>Cotizacion - <?php echo $sitename ?></title>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
	</head>
	<body style='margin: 0; padding: 0;'>
		<table border='0' cellpadding='0' cellspacing='0' width='100%'>
			<tr>
				<td>
					<table align='center' border='0' cellpadding='0' cellspacing='0' width='800' style='border-collapse: collapse;'>
						<tr>
							<td align='center' style='padding: 40px 0 30px 0;'>
								<img src='<?php echo $mail_logo ?>' alt='Cotizacion <?php echo $sitename?>' style='display: block;' />
							</td>
						</tr>
						<tr>
							<td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>
								Cotización
							</td>
						</tr>
						<tr>
							<td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;
							</td>
						</tr>
						<tr>
							<td style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'>
								<?php echo $mensajeprincipal ?>
							</td>.
						</tr>
						<tr>
							<td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;
							</td>
						</tr>
						<tr>
							<td align='center' bgcolor='#5bc0de' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;padding: 10px 15px; border-bottom: 1px solid transparent;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: transparent;border-top-left-radius: 3px;border-radius: 3px;'>
 								DATOS DE COTIZACION
 							</td>
						</tr>
						<tr>
							<td>
								<table align='center' border='0' cellpadding='0' cellspacing='0' width='800' style='border-collapse: collapse;'>
		 							<thead>
										<tr>
											<th>CANTIDAD</th>
											<th>CODIGO</th>
											<th>DESCRIPCION</th>
											<th>COLORES</th>
											<th>PRECIO UNITARIO</th>
											<th>TOTAL</th>
										 </tr>
									</thead>
									<tbody><?php echo $detalletabla ?></tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;
							</td>
						</tr>";
						<?php if($activardescuento==1) { ?>
							<tr>
								<td width='267' style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 14px;'>Descuento Promocional: $ <?php echo number_format($descuentototal, 2, ',', '.') ?><td>
							</tr>
						<?php } ?>
						<tr>
							<td width='267' style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'>TOTAL: $ <?php echo number_format($totaltotal, 2, ',', '.') ?><td>
						</tr>
						<tr>
							<td width='267' style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'>TOTAL + IVA: $ <?php echo number_format($totaltotal*(1 + $iva*0.01), 2, ',', '.') ?><td>
						</tr>
						<tr>
							<td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;
							</td>
						</tr>
						<?php echo $observaciones ?>
						<tr>
							<td width='267' style='padding-top:5px; padding-bottom:5px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 16px;'><?php echo $mensajecotizador ?></td>
						</tr>
						<tr>
							<td align='center' style='font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 40px;'>&nbsp;
							</td>
						</tr>
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