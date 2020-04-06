<?php if (file_exists("../config.php"))
        include ("../config.php");
    else { header("location: ../instalador"); exit(); }
?>
<?php session_start();
   if(empty($_SESSION['userPersona'])){
      header("location: $basehttp");
   } else {
	function markups($s){
		$s = preg_replace( "/\*\*\*(.+?)\*\*\*/",	"<strong><em>$1</em></strong>",	$s);
		$s = preg_replace( "/\*\*(.+?)\*\*/",		"<strong>$1</strong>",			$s);
		$s = preg_replace( "/\*(.+?)\*/",			"<em>$1</em>",					$s);
		$s = preg_replace( "/_(.+?)_/",				"<u>$1</u>",					$s);
		$s = preg_replace( "/~(.+?)~/",				"<s>$1</s>",					$s);
		$s = preg_replace( "[\n|\r|\n\r]",			"<br>",							$s);
		return $s;
	}
    if(isset($_GET['id'])){
		
        $idpresupuesto = $_GET['id'];
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $mysqli->set_charset("utf8");
		
		// Configuración
		$query = "SELECT * FROM presupuestos_configuracion WHERE id = '1'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$row = $result->fetch_assoc();
		$estados = $row['estados'] ? explode("|", $row['estados']) : ["COTIZADO", "CONCRETADO"];
		
		// Usuarios
		$query = "SELECT user,id FROM sist_usuarios";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$usuarios=array();
		while($row = $result->fetch_assoc()) {
			$usuarios[$row['id']] = $row['user'];
		}
		
		// Presupuesto
        $query = "SELECT * FROM presupuestos WHERE id = '$idpresupuesto'";
        $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
        $row = $result->fetch_assoc();
		
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        $correo = $row['correo'];
        $telefono = $row['telefono'];
        $detalles = $row['detalles'];
		$detalles_formato = markups($detalles);
		$condiciones = $row['condiciones'];
		$condiciones_formato = $condiciones ? markups($condiciones) : "<em>(vacío)</em>";
        $importe = $row['importe'];
		$fecha = date("d/m/Y", strtotime($row['fecha']));
        $usuario = $usuarios[$row['id_usuario']];
		$estado_p = $row['estado'];
		
		// Observaciones
		$query = "SELECT * FROM presupuestos_observaciones WHERE id_presupuesto = '$idpresupuesto'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$observaciones = [];
		while($row = $result->fetch_assoc()) {
			$observaciones[] = $row;
		}
		
		// Archivos adjuntos
		$query = "SELECT * FROM presupuestos_archivos WHERE id_presupuesto = '$idpresupuesto'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$archivos = [];
		while($row = $result->fetch_assoc()) {
			$archivos[] = $row;
		}
        
		mysqli_close($mysqli);
		
		// Datos para el PDF
		$pdf_url = "$basehttp/presupuestos/generar-pdf.php?id=$idpresupuesto&mode=saveas";
        $pdf_url = "$idpresupuesto/pdf/exportar";
		
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
    <title>Presupuestos - <?php echo $sitename ?></title>
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
						<h4 class="modal-title" id="myModalLabel">Cambiar estatus del presupuesto</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" id="jq-validation-form">
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Presupuesto de</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="nombreestatus" name="nombreestatus" placeholder="Cliente" value="<?php echo $nombre." ".$apellido ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Estado</label>
								<div class="col-sm-9">
									<select id="select-cotiz-estatus" class="form-control">
										<?php foreach($estados as $estado): ?>
										<option value="<?php echo $estado ?>" <?php echo $estado == $estado_p ? "selected" : "" ?>>
											<?php echo $estado ?>
										</option>
										<?php endforeach ?>
									</select>                                      
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
						<!--<button type="button" id="btn-estatus-confirmado" class="btn btn-success">CONCRETADO</button>-->
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
							<hr />
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-4">
									<input type="text" class="form-control" id="reenviarnombre" name="reenviarnombre" value="<?php echo $nombre; ?>" readonly>
									<input type="hidden" class="form-control" id="reenviaridpresupuesto" name="reenviaridpresupuesto" value="<?php echo $idpresupuesto; ?>">
								</div>
                                <label for="jq-validation-required" class="col-sm-1 control-label">Apellido</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="reenviarapellidos" name="reenviarapellidos" value="<?php echo $apellido; ?>" readonly>                                            
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Correo Electronico</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="reenviaremailemail" name="reenviaremailemail" value="<?php echo $correo; ?>">
                                </div>
                                <label for="jq-validation-required" class="col-sm-1 control-label">Telefono</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="reenviartelefono" name="reenviartelefono" value="<?php echo $telefono; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Detalles</label>
                                <div class="col-sm-9"><?php echo $detalles_formato ?></div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Condiciones</label>
                                <div class="col-sm-9"><?php echo $condiciones_formato ?></div>
                            </div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-2 col-sm-offset-6 control-label">Importe cotizado:</label>
                                <div class="col-sm-3 col-sm-3-offset-3">
                                    <input type="text" class="form-control" id="reenviartotal" name="reenviartotal" value="$ <?php echo $importe ?>" readonly>
								</div>
                            </div>
							<hr />
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
                        <button type="button" class="btn btn-danger" id="guardarreenviar">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
		<div id="modal-enviaremail" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
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
                                        <input type="text" class="form-control" id="emailemail" value="<?php echo $correo ?>" name="emailemail" />
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
                        <button type="button" class="btn btn-danger" id="guardarenviaremail">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal-agregarobservacion" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
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
                            <span class="panel-title">Datos del Presupuesto</span>
							<span style="float: right;"><?php echo $fecha ?></span>
                        </div>
                        <br /><br />
                        <form class="form-horizontal" id="jq-validation-form">
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-4">
									<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" readonly>
									<input type="hidden" class="form-control" id="idpresupuesto" name="idpresupuesto" value="<?php echo $idpresupuesto; ?>">
								</div>
                                <label for="jq-validation-required" class="col-sm-1 control-label">Apellido</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $apellido; ?>" readonly>                                            
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Correo Electronico</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="correo" name="correo" value="<?php echo $correo; ?>" readonly>
                                </div>
                                <label for="jq-validation-required" class="col-sm-1 control-label">Telefono</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Detalles</label>
                                <div class="col-sm-9">
									<textarea class="form-control" id="detalles" name="detalles" placeholder="Detalles del presupuesto" style="resize:none;height:150px" readonly><?php echo $detalles ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Condiciones</label>
                                <div class="col-sm-9">
									<textarea class="form-control" id="condiciones" name="condiciones" placeholder="Condiciones" style="resize:none" readonly><?php echo $condiciones ?></textarea>
                                </div>
                            </div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label col-sm-offset-4">Importe cotizado:</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="importe" name="importe" value="$ <?php echo number_format($importe, 2, '.', ','); ?>" readonly>
								</div>
							</div>
<?php if (count($archivos) > 0): ?>
							<div class="form-group">
                                <label for="jq-validation-required" class="col-sm-2 control-label">Archivos adjuntos</label>
                                <div class="col-sm-9">
									<style type="text/css">
										.attachment {
											font-size: 1.12rem;display: inline-flex;background: #eee;border-radius: 4px;padding: 8px 30px 8px 8px;margin-right: 10px;margin-bottom: 10px;box-shadow: 2px 2px 6px 0 rgba(0,0,0,0.4);
										}
										.attachment>*{align-self:center}
										.attachment>:first-child{
											width: 35px;font-size: 2.3rem;display: flex;justify-content: center;
										}
										.attachment>:last-child>:first-child{font-weight: bold;font-size: 1.32rem;}
										.attachment>:last-child>:last-child{font-style: italic;color: #888;}
									</style>
									<?php foreach($archivos as $archivo): ?>
										<a href="<?php echo "$basehttp/presupuestos/archivos/".$archivo['file'] ?>">
										<div class="attachment">
											<div>
												<i class="fa fa-paperclip"></i>
											</div>
											<div>
												<div><?php echo $archivo['file'] ?></div>
												<div><?php echo $archivo['size'] ?> bytes</div>
												<div><?php echo date('d/m/Y h:i:s', strtotime($archivo['fecha'])) ?></div>
											</div>
										</div>
										</a>
									<?php endforeach ?>
								</div>
                            </div>
                            <br />
<?php endif ?>
                        </form>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <span class="panel-title">Acciones</span>
                        </div>
                        <div class="panel-body tab-content-padding">
                            <div class="form-group">
								<button class="btn btn-lg btn-warning col-lg-offset-1 col-md-12 col-lg-4 col-xs-12" id="btn-estatus" >CAMBIAR ESTATUS</button>
                                <button class="btn btn-lg btn-info col-lg-offset-2 col-md-12 col-lg-4 col-xs-12" id="btn-agregarobservacion" >AGREGAR OBSERVACION</button>
                            </div>
							<div class="form-group">
                                <button class="btn btn-lg btn-info col-lg-offset-1 col-md-12 col-lg-4 col-xs-12" id="btn-reenviar" >REENVIAR PRESUPUESTO</button>
                                <button class="btn btn-lg btn-info col-lg-offset-2 col-md-12 col-lg-4 col-xs-12" id="btn-enviaremail" >ENVIAR E-MAIL</button>
                            </div>
							<div class="form-group">
                                <a class="btn btn-lg btn-danger col-lg-offset-1 col-md-12 col-lg-4 col-xs-12" id="btn-descargarpdf" href="<?php echo $pdf_url ?>" target="_blank" ><i class="fa fa-file"></i> DESCARGAR PDF</a>
								<?php if ( $wsp_enabled ): ?>
									<button class="btn btn-lg btn-success col-lg-offset-2 col-md-12 col-lg-4 col-xs-12" id="btn-whatsapp" ><i class="fa fa-comment"></i> MENSAJERIA WHATSAPP</button>
								<?php else: ?>
									<div class="col-lg-offset-2 col-md-12 col-lg-4 col-xs-12" data-toggle="tooltip" data-placement="bottom" title="El cliente no introdujo un numero valido" style="padding:0;cursor:not-allowed;">
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
									<?php if (count($observaciones) > 0): ?>
										<?php foreach($observaciones as $observacion): ?>
											<div class="ticket">
												<span class="label label-success ticket-label"><?php echo $observacion['tipo'] ?></span>
												<span>[<?php echo $observacion['fecha'] ?>]</span>
												<span class="ticket-info"><?php echo $observacion['observacion'] ?></span>
											</div>
										<?php endforeach ?>
									<?php else: ?>
										<em>No hay ninguna observación para mostrar.</em>
									<?php endif ?>
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
    <script type="text/javascript"> window.jQuery || document.write('<script src="assets/js/jquery-2.0.3.min.js">'+"<"+"/script>"); </script>
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
            $("#modal-agregarobservacion").modal("show");
        });
		$("#btn-estatus").on( "click", function() {
			$("#modalestatus").modal("show");
        });
        
        $("#guardarobservaciones").on( "click", function() {
			let idpres = $("#idpresupuesto").val()
            $.ajax({
                url : 'guardar-observacion.php',
                data : {
					id_presupuesto : idpres,
					tipo : $("#modtipoob").val(),
					observacion : $("#modobservacion").val(),
				},
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "presupuesto.php?id="+idpres+"&msg="+respuesta; 
                    url = idpres+"-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });           
        });
		$("#btn-enviaremail").on( "click", function() {
            $("#modal-enviaremail").modal("show");
        });
		$("#btn-reenviar").on( "click", function() {

            var emailaenviar = $("#email").val();
            $("#emailemailreenviar").val(emailaenviar);
            $("#modalreenviar").modal("show");
        });
		$("#guardarreenviar").on("click", function(){
			let text = $("#guardarreenviar").text()
			$("#guardarreenviar").attr("disabled", true).text("Enviando...");
            var params2 = {
				id : $("#reenviaridpresupuesto").val(),
				reenviarremitenteemail : $("#reenviarremitenteemail").val(),
				reenviarremitente : $("#reenviarremitente").val(),
				reenviaremailemail : $("#reenviaremailemail").val(),
				reenviarmensajeemail : $("#reenviarmensajeemail").val(),
            }
			var params1 = {
				id_presupuesto : params2.id,
				tipo : "REENVIO DE PRESUPUESTO",
				observacion : "El usuario reenvió el presupuesto al siguiente email: "+params2.reenviaremailemail+" con el siguiente mensaje: "+params2.reenviarmensajeemail,
			}
            var revision = "true";
            for (i in params2) {
                if(params2[i]==""){
                    revision = "false";
                }            
            }
            if(revision=="true"){
                $.ajax({
                    url : 'enviarcorreo.php',
                    data : params2,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
						if (!respuesta.startsWith("Ocurrió un error inesperado tratando de enviar el correo")) {
							$.ajax({
								url : 'guardar-observacion.php',
								data : params1,
								type : 'GET',
								dataType : 'html',
								success : function(respuesta2) {
									console.log("(reenviar mail): "+respuesta2);
                                    var url = "presupuesto.php?id="+params2.id+"&msg="+respuesta; 
                                    url = params2.id+"-"+respuesta; 
									$(location).attr('href',url);
								},
								error : function(xhr, status) {
									alert('El correo fue enviado pero ocurrió un error tratando de guardar observación.');
									$("#guardarreenviar").attr("disabled", false).text(text);
								},
							});
						}
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
						$("#guardarreenviar").attr("disabled", false).text(text);
                    },
                });
            }else{
                alert('Complete todos los datos');
				$("#guardarreenviar").attr("disabled", false).text(text);
            }
        });
		$("#guardarenviaremail").on("click", function(){
			let text = $("#guardarenviaremail").text()
			$("#guardarenviaremail").attr("disabled", true).text("Enviando...");
			var idpresupuesto = $("#idpresupuesto").val();
			var params2 = { 
                emailemail : $("#emailemail").val(),
                asuntoemail : $("#asuntoemail").val(),
                mensajeemail : $("#mensajeemail").val(),
                remitenteemail : $("#remitenteemail").val(),
                remitente : $("#remitente").val(),
			}
			var params1 = {
				id_presupuesto : idpresupuesto,
				tipo : "CORREO ELECTRONICO",
				observacion : "El usuario envió el siguiente email: "+params2.asuntoemail+" - "+params2.mensajeemail,
			}
            var revision = "true";
            for (i in params2) {
                if(params2[i]==""){
                    revision = "false";
                }            
            }
            if(revision=="true"){
                $.ajax({
                    url : 'enviarcorreo.php',
                    data : params2,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta1) {
						if (!respuesta1.startsWith("Ocurrió un error inesperado tratando de enviar el correo")) {
							$.ajax({
								url : 'guardar-observacion.php',
								data : params1,
								type : 'GET',
								dataType : 'html',
								success : function(respuesta2) {
									console.log("(enviar mail): "+respuesta2);
                                    var url = "presupuesto.php?id="+idpresupuesto+"&msg="+respuesta1;
                                    url = idpresupuesto+"-"+respuesta1;
									$(location).attr('href',url);
								},
								error : function(xhr, status) {
									alert('Error: el correo fue enviado pero no se pudo crear observación.');
									$("#guardarenviaremail").attr("disabled", false).text(text);
								},
							});
						}
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
						$("#guardarenviaremail").attr("disabled", false).text(text);
                    },
                });
            }else{
                alert('Complete todos los datos');
				$("#guardarenviaremail").attr("disabled", false).text(text);
            }
        });
		$('#select-cotiz-estatus').on('change', function() {console.log("select-cotiz-estatus changed")
            var params = { 
                estado : $("#select-cotiz-estatus").val(),
				idpresupuesto : $("#idpresupuesto").val(),
            }
            $.ajax({
                url : 'guardar-estado.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
					if (!respuesta.startsWith("No se puede cambiar")) {
						$.ajax({
							url : 'guardar-observacion.php',
							data : {
								id_presupuesto : params.idpresupuesto,
								tipo : "CAMBIO DE ESTATUS",
								observacion : "El usuario cambió el estatus del presupuesto a " + params.estado,
							},
							type : 'GET',
							dataType : 'html',
							success : function() {
                                var url = "presupuesto.php?id="+params.idpresupuesto+"&msg="+respuesta;
                                url = params.idpresupuesto+"-"+respuesta;
								$(location).attr('href',url);
							},
							error : function(xhr, status) {
								alert('Error: el estatus fue cambiado pero no se pudo crear observación.');
							},
						});
					} else {
                        var url = "presupuesto.php?id="+params.idpresupuesto+"&msg="+respuesta;
                        url = params.idpresupuesto+"-"+respuesta;
						$(location).attr('href',url);
					}
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
				url : 'guardar-observacion.php',
				data : {
					id_presupuesto : $("#idpresupuesto").val(),
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
				url : 'guardar-observacion.php',
				data : {
					id_presupuesto : $("#idpresupuesto").val(),
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