<?php if (file_exists("../../config.php"))
        include ("../../config.php");
    else { header("location: ../../instalador"); exit(); }
?>
<?php session_start();
   if(empty($_SESSION['userPersona'])){
      header("location: $basehttp");
   } else {
    if(isset($_GET['id'])){
        $idcotizacion = $_GET['id'];
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $mysqli->set_charset("utf8");
		
		$query = "SELECT * FROM cot2_configuraciones WHERE id = '1'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$row = $result->fetch_assoc();
		$habilitar_impresion = $row['habilitar_impresion'];
		$iva = $row['iva'];
		
        $query = "SELECT * FROM cot2_cotizacion WHERE id = '$idcotizacion'";
        $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
        $row = $result->fetch_assoc();
        $usuario = $row['usuario'];
        $nombre = $row['nombre'];
        $apellidos = $row['apellidos'];
        $email = $row['email'];
        $telefono = $row['telefono'];
        $fecha = $row['fecha'];
		$descuentoporcentaje = $row['descuentoporcentaje'];
        $descuento = $row['descuento'];
		$total = $row['total'];
        mysqli_close($mysqli);
		
		// Datos para el PDF
        $pdf_url = "$basehttp/cotizador2/admin/generar-pdf.php?id=$idcotizacion&mode=saveas";
        $pdf_url = "cot-$idcotizacion/pdf/exportar";
		
		// Datos para el WhatsApp
		if ( strlen($telefono) < 7 ) {
			$wsp_enabled = false;
		} else {
			$wsp_enabled = true;
			$wsp_telf = '54' . preg_replace(
				'/^0+/i',
				'',
				preg_replace('/[^\d\r\n]/i', '', $telefono)
			);
			$wsp_mensaje = urldecode("Saludos desde Evamagic!");
			$wsp_url = "https://api.whatsapp.com/send?phone=$wsp_telf&text=$wsp_mensaje";
		}
    }
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Cotizador <?php echo $sitename ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- Open Sans font from Google CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <!-- Pixel Admin's stylesheets -->
    <link href="assets/stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/themes.min.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="assets/javascripts/ie.min.js"></script>
    <![endif]-->
</head>
<body class="theme-frost no-main-menu">
<script>var init = [];</script>
<div id="main-wrapper">
    <?php include("header.php");?>
    <div id="content-wrapper">
        <div class="page-header">
            <div class="row">
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Contacto: <?php echo $nombre; ?></h1>
            </div>
        </div> <!-- / .page-header -->
        <?php 
            if(isset($_GET['msg'])){
                $mensaje = $_GET['msg'];
                echo "<div class='alert alert-success'>".$mensaje."</div>";
            }
        ?>
		<div id="modalestatus" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel">Cambiar estatus de cotizacion</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" id="jq-validation-form">
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Cotizacion de</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="nombreestatus" name="nombreestatus" placeholder="">
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
						<button type="button" id="btn-estatus-confirmado" class="btn btn-success">CONCRETADO</button>
					</div>
				</div>
			</div>
		</div>
        <div id="modalreenviar" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">REENVIAR COTIZACION</h4>
                    </div>
                    <div class="modal-body">
                       <form class="form-horizontal" id="jq-validation-form">
							                                <div class="form-group">
                            <label class="col-sm-2 control-label">Remitente:</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="reenviarremitente" value="<?php echo $_SESSION['nombrePersona']; ?>" name="reenviarremitente">
								</div>
								<label class="col-sm-1 control-label">Email:</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="reenviarremitenteemail" value="<?php echo $_SESSION['emailPersona']; ?>" name="reenviarremitenteemail">
								</div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-4">
									<input type="text" class="form-control" id="reenviarnombre" name="reenviarnombre" value="<?php echo $nombre; ?>">
									<input type="hidden" class="form-control" id="reenviaridcotizacion" name="reenviaridcotizacion" value="<?php echo $idcotizacion; ?>">
								</div>
                                <label for="jq-validation-required" class="col-sm-1 control-label">Apellidos</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="reenviarapellidos" name="reenviarapellidos" value="<?php echo $apellidos; ?>">                                            
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Correo Electronico</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="reenviaremailemail" name="reenviaremailemail" value="<?php echo $email; ?>">
                                </div>
                                <label for="jq-validation-required" class="col-sm-1 control-label">Telefono</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="reenviartelefono" name="reenviartelefono" value="<?php echo $telefono; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Detalles</label>
                                <div class="col-sm-9">
								<table id="tablacotizacion" class="table">
									<thead>
										<tr>
											<th style="display:none;">ID</th>
											<th>CANTIDAD</th>
											<th>CODIGO</th>
											<th>DESCRIPCION</th>
											<?php if ($habilitar_impresion): ?><th>COLORES</th><?php endif ?>
											<th>PRECIO UNITARIO</th>
											<th>TOTAL</th>
										 </tr>
									</thead>
									<tbody>
                                    <?php
                                        $mysqlix = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
										if($mysqlix->connect_errno > 0){
											die('Unable to connect to database [' . $db->connect_error . ']');
										}
										$mysqlix->set_charset("utf8");
										$queryx = "SELECT * FROM cot2_cotizaciondet WHERE idcot = '$idcotizacion'";
										$resultx = $mysqlix->query($queryx) or die($mysqlix->error.__LINE__);
										while($rowx = $resultx->fetch_assoc()) {
											echo"<tr>";
											echo "<td>".$rowx['cantidad']."</td>";
											echo "<td>".$rowx['codigo']."</td>";
											echo "<td>".$rowx['descripcion']."</td>";
											if ($habilitar_impresion)
												echo "<td>".$rowx['colores']."</td>";
											echo "<td>".$rowx['precio']."</td>";
											echo "<td>".$rowx['total']."</td>";
											echo "</tr>";
										}
										mysqli_close($mysqlix);
                                    ?>
											
										</tbody>
									</table>
                                </div>
                            </div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-2 control-label">Descuento %:</label>
                                <div class="col-sm-1">
									<input type="text" class="form-control" id="reenviardescuentoporcentaje" name="reenviardescuentoporcentaje" value="<?php echo $descuentoporcentaje; ?>">
								</div>   
								<label for="jq-validation-required" class="col-sm-2 control-label">Descuento $:</label>
								<div class="col-sm-2">
									<input type="text" class="form-control" id="reenviardescuento" name="reenviardescuento" value="<?php echo $descuento; ?>">
								</div>
								<label for="jq-validation-required" class="col-sm-1 control-label">Total:</label>
                                <div class="col-sm-3 col-sm-3-offset-3">
                                    <input type="text" class="form-control" id="reenviartotal" name="reenviartotal" value="<?php echo $total; ?>">
								</div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Mensaje:</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="reenviarmensajeemail" rows="6" name="reenviarmensajeemail"></textarea>
                                    </div>
                                </div>
                            <br />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="guardarreenviar" data-dismiss="modal">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
		<div id="modalform2" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">ENVIAR CORREO ELECTRONICO</h4>
                    </div>
                    <div class="modal-body">
                       <form class="form-horizontal" id="jq-validation-form">
                            <div id="detallecontacto" class="form-group">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Remitente:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="remitente" value="<?php echo $_SESSION['nombrePersona']; ?>" name="remitente">
                                    </div>
                                    <label class="col-sm-1 control-label">Email:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="remitenteemail" value="<?php echo $_SESSION['emailPersona']; ?>" name="remitenteemail">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Destinatario:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="emailemail" name="emailemail">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Asunto:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="asuntoemail" name="asuntoemail">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Mensaje:</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="mensajeemail" rows="6" name="mensajeemail"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="guardarenviaremail" data-dismiss="modal">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="modalform" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">AGREGAR OBSERVACION</h4>
                    </div>
                    <div class="modal-body">
                       <form class="form-horizontal" id="jq-validation-form">
                            <div id="detallecontacto" class="form-group">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Tipo:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="modtipoob" id="modtipoob">
                                            <option value="OBSERVACION">OBSERVACION</option>
                                            <option value="OBSERVACION">CONTACTO TELEFONICO</option>
                                            <option value="OBSERVACION">CORREO ELECTRONICO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Observacion:</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="modobservacion" rows="6" name="modobservacion"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger" id="guardarobservaciones" data-dismiss="modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
<?php if (isset($wsp_telf) && isset($wsp_mensaje)): ?>
		<div id="modalwhatsapp" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
			<div class="modal-dialog modal-lg" style="width:50%">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel">Mensajeria WhatsApp</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label for="wsp_number" class="col-sm-3 control-label">Numero:</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="wsp_number" name="wsp_number" placeholder="<?php echo $wsp_telf ?>" value="<?php echo $wsp_telf ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="wsp_text" class="col-sm-3 control-label">Mensaje:</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="wsp_text" name="wsp_text" placeholder="<?php echo $wsp_mensaje ?>">
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
						<button type="button" id="btn-sendwhatsapp" class="btn btn-success">ENVIAR WHATSAPP</button>
					</div>
				</div>
			</div>
		</div>
<?php endif ?>
        <div class="row">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <span class="panel-title">Datos de la Cotizacion</span>
                        </div>

                        <br /><br />
                        <form class="form-horizontal" id="jq-validation-form">
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-4">
									<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
									<input type="hidden" class="form-control" id="idcotizacion" name="idcotizacion" value="<?php echo $idcotizacion; ?>">
								</div>
                                <label for="jq-validation-required" class="col-sm-1 control-label">Apellidos</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>">                                            
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Correo Electronico</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                                </div>
                                <label for="jq-validation-required" class="col-sm-1 control-label">Telefono</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Detalles</label>
                                <div class="col-sm-9">
								<table id="tablacotizacion" class="table">
									<thead>
										<tr>
											<th style="display:none;">ID</th>
											<th>CANTIDAD</th>
											<th>CODIGO</th>
											<th>DESCRIPCION</th>
											<?php if ($habilitar_impresion): ?><th>COLORES</th><?php endif ?>
											<th>PRECIO UNITARIO</th>
											<th>TOTAL</th>
										 </tr>
									</thead>
									<tbody>
                                    <?php
                                        $mysqlix = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
										if($mysqlix->connect_errno > 0){
											die('Unable to connect to database [' . $db->connect_error . ']');
										}
										$mysqlix->set_charset("utf8");
										$queryx = "SELECT * FROM cot2_cotizaciondet WHERE idcot = '$idcotizacion'";
										$resultx = $mysqlix->query($queryx) or die($mysqlix->error.__LINE__);
										while($rowx = $resultx->fetch_assoc()) {
											echo"<tr>";
											echo "<td>".$rowx['cantidad']."</td>";
											echo "<td>".$rowx['codigo']."</td>";
											echo "<td>".$rowx['descripcion']."</td>";
											if ($habilitar_impresion)
												echo "<td>".$rowx['colores']."</td>";
											echo "<td>".number_format($rowx['precio'], 2, ',', '.')."</td>";
											echo "<td>".number_format($rowx['total'], 2, ',', '.')." + IVA</td>";
											echo "</tr>";
										}
										mysqli_close($mysqlix);
                                    ?>
											
										</tbody>
									</table>
                                </div>
                            </div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-2 control-label">Descuento %:</label>
                                <div class="col-sm-1">
									<input type="text" class="form-control" id="descuentoporcentaje" name="descuentoporcentaje" value="<?php echo $descuentoporcentaje; ?>">
								</div>
								<label for="jq-validation-required" class="col-sm-1 control-label">Descuento $</label>
								<div class="col-sm-2">
                                    <input type="text" class="form-control" id="descuento" name="descuento" value="<?php echo number_format($descuento, 2, '.', ','); ?>">
								</div>
								<label for="jq-validation-required" class="col-sm-1 control-label">Total:</label>
                                <div class="col-sm-4 col-sm-3-offset-3">
                                    <input type="text" class="form-control" id="total" name="total" value="<?php echo number_format($total, 2, '.', ','); ?>">
								</div>
                            </div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label col-sm-offset-4">Total + IVA:</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="total" name="total" value="<?php echo number_format($total*(1 + $iva*0.01), 2, '.', ','); ?>">
								</div>
							</div>
                            <br />
                        </form>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <span class="panel-title">Acciones</span>
                        </div>
                        <div class="panel-body tab-content-padding">
                            <div class="form-group">
								<button class="btn btn-lg btn-info col-lg-offset-1 col-md-12 col-lg-4 col-xs-12" id="btn-estatus" >CAMBIAR ESTATUS</button>
                                <button class="btn btn-lg btn-info col-lg-offset-2 col-md-12 col-lg-4 col-xs-12" id="btn-agregarobservacion" >AGREGAR OBSERVACION</button>
                            </div>
							<div class="form-group">
                                <button class="btn btn-lg btn-info col-lg-offset-1 col-md-12 col-lg-4 col-xs-12" id="btn-reenviar" >REENVIAR COTIZACION</button>
                                <button class="btn btn-lg btn-info col-lg-offset-2 col-md-12 col-lg-4 col-xs-12" id="btn-enviaremail" >ENVIAR E-MAIL</button>
                            </div>
							<div class="form-group">
                                <button class="btn btn-lg btn-info col-lg-offset-1 col-md-12 col-lg-4 col-xs-12" id="btn-enviaremailoutlook" >EMAIL POR OUTLOOK</button>
                                <a class="btn btn-lg btn-danger col-lg-offset-2 col-md-12 col-lg-4 col-xs-12" id="btn-descargarpdf" href="<?php echo $pdf_url ?>" target="_blank" ><i class="fa fa-file"></i> DESCARGAR PDF</a>
                            </div>
							<div class="form-group">
								<?php if ( $wsp_enabled ): ?>
									<button class="btn btn-lg btn-success col-lg-offset-1 col-md-12 col-lg-4 col-xs-12" id="btn-whatsapp" ><i class="fa fa-comment"></i> MENSAJERIA WHATSAPP</button>
								<?php else: ?>
									<div class="col-lg-offset-1 col-md-12 col-lg-4 col-xs-12" data-toggle="tooltip" data-placement="bottom" title="El cliente no introdujo un numero valido" style="padding:0;cursor:not-allowed;">
										<button class="btn btn-lg btn-success" style="width:100%" disabled ><i class="fa fa-comment"></i> MENSAJERIA WHATSAPP</button>
									</div>
								<?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-12">
                        <div class="panel panel-info widget-support-tickets">
                            <div class="panel-heading">
                                <span class="panel-title">Historial</span> 
                            </div> <!-- / .panel-heading -->
                            <div class="panel-body tab-content-padding">
                                <!-- Panel padding, without vertical padding -->
                                <div class="panel-padding no-padding-vr">
                                    <?php
                                        $mysqli5 = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
                                        /* check connection */
                                        if($mysqli5->connect_errno > 0){
                                            die('Unable to connect to database [' . $db->connect_error . ']');
                                        }
						               $mysqli5->set_charset("utf8");
                                       $query5 = "SELECT * FROM cot2_observaciones WHERE idcotizacion = '$idcotizacion'";
                                       $result5 = $mysqli5->query($query5) or die($mysqli5->error.__LINE__);
                                       while($row5 = $result5->fetch_assoc()) { 
                                            $fechaob = $row5['fecha'];
                                            $observacionob = $row5['observacion'];
                                            $tipoob = $row5['tipo'];                                          
                                        ?>
                                            <div class="ticket">
                                                <span class="label label-success ticket-label"><?php echo $tipoob; ?></span>
                                                <span>[<?php echo $fechaob; ?>]</span>
                                                <span class="ticket-info"><?php echo $observacionob; ?></span>
                                            </div>
                                        <?php }
                                       mysqli_close($mysqli5);

                                    ?>
                                </div>
                            </div> <!-- / .panel-body -->
                        </div> <!-- / .panel -->
                    </div>
                </div>
            </div>
        </div> <!-- / #content-wrapper -->
        <div id="main-menu-bg"></div>
    </div> <!-- / #main-wrapper -->

    <!-- Get jQuery from Google CDN -->
    <!--[if !IE]> -->
    <?php //<script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js">'+"<"+"/script>"); </script> ?>
    <script type="text/javascript"> window.jQuery || document.write('<script src="assets/js/jquery.min.js">'+"<"+"/script>"); </script>
    <!-- <![endif]-->
    <!--[if lte IE 9]>
    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">'+"<"+"/script>"); </script>
    <![endif]-->


    <!-- Pixel Admin's javascripts -->
    <script src="assets/javascripts/bootstrap.min.js"></script>
    <script src="assets/javascripts/pixel-admin.min.js"></script>

      
    <script type="text/javascript">
        init.push(function () {
            // Javascript code here
        })
        
        window.PixelAdmin.start(init);
        $("#btn-agregarobservacion").on( "click", function() {
            $("#modalform").modal("show");
        });
        
        $("#guardarobservaciones").on( "click", function() {
            var params = { 
                idcotizacion : $("#idcotizacion").val(),
                tipo : $("#modtipoob").val(),
                observacion : $("#modobservacion").val()
            }
            $.ajax({
                url : 'guardarobservaciones.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "cotizacion.php?id="+params.idcotizacion+"&msg="+respuesta; 
                    var url = "cot-"+params.idcotizacion+"-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });           
        });
		$("#btn-enviaremail").on( "click", function() {

            var emailaenviar = $("#email").val();
            $("#emailemail").val(emailaenviar);
            $("#modalform2").modal("show");
        });
		$("#btn-reenviar").on( "click", function() {

            var emailaenviar = $("#email").val();
            $("#emailemailreenviar").val(emailaenviar);
            $("#modalreenviar").modal("show");
        });

        $("#btn-enviaremailoutlook").on( "click", function() {
            var emailaenviar2 = $("#email").val();
            var observacion2 = "El usuario <?php echo $_SESSION['nombrePersona']; ?> envio email via Outlook.";
            var params = { 
                idcotizacion : $("#idcotizacion").val(),
                tipo : "CORREO ELECTRONICO",
                observacion : observacion2
            }
            $.ajax({
                url : 'guardarobservaciones.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    window.location = 'mailto:' + emailaenviar2 + '?subject=Cotizacion Eva Magic';
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
            
        });
		$("#guardarreenviar").on("click", function(){
		var reenviarmensajeemail2 = $("#reenviarmensajeemail").val();
		reenviarmensajeemail2 = reenviarmensajeemail2.replace(/\r?\n/g, "<br>");
            var params2 = { 
			reenviarremitenteemail : $("#reenviarremitenteemail").val(),
			reenviarremitente : $("#reenviarremitente").val(),
			reenviarnombre : $("#reenviarnombre").val(),
			reenviarapellidos : $("#reenviarapellidos").val(),
			reenviaremailemail : $("#reenviaremailemail").val(),
			reenviaridcotizacion : $("#reenviaridcotizacion").val(),
			reenviardescuento : $("#reenviardescuento").val(),
			reenviardescuentoporcentaje : $("#reenviardescuentoporcentaje").val(),
			reenviartotal : $("#reenviartotal").val(),
			reenviarmensajeemail : reenviarmensajeemail2,
            iduser : <?php echo $_SESSION['id'] ?>
				
            }
            var revision = "true";
            for (i in params2) {
                if(params2[i]==""){
                    revision = "false";
                }            
            }
            if(revision=="true"){
                $.ajax({
                    url : 'guardaremail2.php',
                    data : params2,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "cotizacion.php?id="+params2.reenviaridcotizacion+"&msg="+respuesta; 
                        var url = "cot-"+params2.reenviaridcotizacion+"-"+respuesta; 
                        $(location).attr('href',url);
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }else{
                alert('Complete todos los datos');
            }
        });
		$("#guardarenviaremail").on("click", function(){
		var mensajeemail2 = $("#mensajeemail").val();
		mensajeemail2 = mensajeemail2.replace(/\r?\n/g, "<br>");
		var params2 = { 
                idcotizacion : $("#idcotizacion").val(),
                emailemail : $("#emailemail").val(),
                asuntoemail : $("#asuntoemail").val(),
                mensajeemail : mensajeemail2,
                remitenteemail : $("#remitenteemail").val(),
                remitente : $("#remitente").val(),
                iduser : <?php echo $_SESSION['id'] ?>
		}
            var revision = "true";
            for (i in params2) {
                if(params2[i]==""){
                    revision = "false";
                }            
            }
            if(revision=="true"){
                $.ajax({
                    url : 'guardaremail.php',
                    data : params2,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "cotizacion.php?id="+params2.idcotizacion+"&msg="+respuesta; 
                        var url = "cot-"+params2.idcotizacion+"-"+respuesta; 
                        $(location).attr('href',url);
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }else{
                alert('Complete todos los datos');
            }
        });
		$("#btn-estatus").on( "click", function() {
            var cambiarestatus = $("#idcotizacion").val();
            $.ajax({
                url : 'vercotizacion.php',
                data : { 'id' : cambiarestatus },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);
                    $("#nombreestatus").val(obj.nombre + " " + obj.apellidos);
                    $("#modalestatus").modal("show");
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
        $("#btn-estatus-confirmado").on( "click", function() {
            var params = { 
                estatus : 'CONCRETADO',
                idcotizacion : $("#idcotizacion").val(),
            }
            $.ajax({
                url : 'guardarestatus.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "cotizacion.php?msg="+respuesta; 
                    var url = "cot-"+params.idcotizacion+"-"+respuesta;
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
		$("#btn-whatsapp").on( "click", function() {
			$("#modalwhatsapp").modal("show");
        });
		$("#btn-sendwhatsapp").on( "click", function() {
			$("#modalwhatsapp").modal("hide");
			
			$.ajax({
				url : 'guardarobservaciones.php',
				data : {
					idcotizacion : $("#idcotizacion").val(),
					tipo : "OBSERVACION",
					observacion : "inició mensajería whatsapp."
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
					console.log("(mensajería whatsapp): "+respuesta);
				},
				error : function(xhr, status) {
					console.log('Error: no se pudo crear observación de mensajería whatsapp.');
				},
			});
<?php if (isset($wsp_telf) && isset($wsp_mensaje)): ?>
			var wsp_number = $("#wsp_number").val() ? $("#wsp_number").val() : "<?php echo $wsp_telf ?>";
			var wsp_text = $("#wsp_text").val() ? $("#wsp_text").val() : "<?php echo $wsp_mensaje ?>";
			var wsp_url = "https://api.whatsapp.com/send?phone="+wsp_number+"&text="+wsp_text;
			window.open(wsp_url,'_blank');
<?php endif ?>
        });
		$("#btn-descargarpdf").on( "click", function() {
			$.ajax({
				url : 'guardarobservaciones.php',
				data : {
					idcotizacion : $("#idcotizacion").val(),
					tipo : "OBSERVACION",
					observacion : "realizó descarga de PDF."
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
					console.log("(descarga pdf): "+respuesta);
				},
				error : function(xhr, status) {
					console.log('Error: no se pudo crear observación de descarga de pdf.');
				},
			});
		});
		
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		});
    </script>

</body>
</html>
<?php } ?>