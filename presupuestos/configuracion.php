<?php if (file_exists("../config.php"))
        include ("../config.php");
    else { header("location: ../instalador"); exit(); }
?>
<?php session_start();
if($_SESSION['rrhh'] != 1){
 header ("Location: $basehttp");
}
?>
<?php if( empty($_SESSION['userPersona']) || $_SESSION['permisosPersona'] == "no" ){
		header("location: $basehttp");
	} elseif( $_SESSION['permisosPersona']=="admin" ) {
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){ // check connection
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		$query = "SELECT * FROM presupuestos_configuracion WHERE id = '1'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$row = $result->fetch_assoc();
		
		// Configuración básica
		$empresa = $row['empresa'];
		$direccion = preg_replace("/<br>/", "\n", $row['direccion']);
		$telefonos = $row['telefonos'];
		$web = $row['web'];
		$email = $row['email'];
		$logo = $row['logo'];

        $presupuestos_pdf_logo = explode("/", $presupuestos_pdf_logo);
        array_pop($presupuestos_pdf_logo);
        $presupuestos_pdf_logo = implode("/", $presupuestos_pdf_logo);

        $logo_url = $basehttp.$presupuestos_pdf_logo."/".$logo;
		
		// Email
		$emailremitente = $row['emailremitente'];
		$emailconf = $row['emailconf'];
        $asuntoemail = $row['asuntoemail'];
        $mensajeprincipal = $row['mensajeprincipal'];
        $mensajeintermedio = $row['mensajeintermedio'];
        $mensajefinal = $row['mensajefinal'];
        $mail_logo = $row['mail_logo'];
		
		// Reenvíos automáticos
        $remitentenombre = $row['reenvio_remitente_nombre'];
        $remitenteemail = $row['reenvio_remitente_email'];
		if ($row['reenvio_datos_json'] != ""){
			$row['reenvio_datos_json'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row['reenvio_datos_json']), ENT_NOQUOTES, 'UTF-8');
			$correos = json_decode($row['reenvio_datos_json']);
			$reenvio_datos_json = $row['reenvio_datos_json'];
		} else {
			$correos = [];
			$reenvio_datos_json = "[]";
		}
		
		// Misceláneas
		$estados = $row['estados'] ? explode("|", $row['estados']) : array();
		$max_size = $row['max_size'];
		$dias_borrado = $row['dias_borrado'];
		
		mysqli_close($mysqli);
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
    <link href="<?php echo $css_url ?>/presupuestos.css" rel="stylesheet" type="text/css">
    <!-- Open Sans font from Google CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <!-- Pixel Admin's stylesheets -->
    <link href="<?php echo $styles_url ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/themes.min.css" rel="stylesheet" type="text/css">
	<script src="https://kit.fontawesome.com/b8c47e2cca.js" crossorigin="anonymous"></script><!-- Font Awesome kit latest -->
    <!--[if lt IE 9]>
    <script src="<?php echo $js_url ?>/ie.min.js"></script>
    <![endif]-->
    <style type="text/css">
    .mb-1 {margin-bottom: 0.25rem}
    .mb-2 {margin-bottom: 0.50rem}
    .mb-3 {margin-bottom: 0.75rem}
    .mb-4 {margin-bottom: 1.00rem}
    .mb-5 {margin-bottom: 1.25rem}
    .mt-1 {margin-top: 0.25rem}
    .mt-2 {margin-top: 0.50rem}
    .mt-3 {margin-top: 0.75rem}
    .mt-4 {margin-top: 1.00rem}
    .mt-5 {margin-top: 1.25rem}
    .ml-1 {margin-left: 0.25rem}
    .ml-2 {margin-left: 0.50rem}
    .ml-3 {margin-left: 0.75rem}
    .ml-4 {margin-left: 1.00rem}
    .ml-5 {margin-left: 1.25rem}
	.ml-6 {margin-left: 1.60rem}
    .mt-lg {margin-top: 2.75rem}
	.stat-panel {
		height: 16rem;
		text-transform: uppercase;
	}
    </style>
</head>
<body class="theme-frost no-main-menu">
<script>var init = [];</script>
<div id="main-wrapper">

    <?php include("header.php");?>


    <div id="content-wrapper">
        <div class="page-header">

            <div class="row">
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Configuraciones</h1>
            </div>
        </div> <!-- / .page-header -->
		<div id="modalemail" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">Configuracion de correo electronico</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="jq-validation-form">
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Remitente:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="emailremitente" value="<?= $emailremitente ?>" name="emailremitente" placeholder="Nombre del remitente">                                          
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Email:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="emailconf" value="<?= $emailconf ?>" name="emailconf" placeholder="Email remitente">                                          
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Asunto:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="asuntoemail" name="asuntoemail" value="<?= $asuntoemail ?>"  placeholder="Asunto del email">                                          
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Logo:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="mail_logo" name="mail_logo" value="<?= $mail_logo ?>"  placeholder="Logo para el correo">                                          
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">
									Mensaje Principal:<br />
									<i class="fa fa-question-circle btn-formato-info" data-toggle="tooltip" data-placement="bottom" title="Click para ver opciones de edición" style="cursor:pointer"></i>
								</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" name="mensajeprincipal" id="mensajeprincipal" rows="5" placeholder="Message"><?= $mensajeprincipal ?></textarea>                                        
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">
									Mensaje Intermedio:<br />
									<i class="fa fa-question-circle btn-formato-info" data-toggle="tooltip" data-placement="bottom" title="Click para ver opciones de edición" style="cursor:pointer"></i>
								</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" name="mensajeintermedio" id="mensajeintermedio" rows="5" placeholder="Message"><?= $mensajeintermedio ?></textarea>                                        
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">
									Mensaje Final:<br />
									<i class="fa fa-question-circle btn-formato-info" data-toggle="tooltip" data-placement="bottom" title="Click para ver opciones de edición" style="cursor:pointer"></i>
								</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" name="mensajefinal" id="mensajefinal" rows="5" placeholder="Message"><?= $mensajefinal ?></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-guardar-email" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal-parametros-basicos" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">Parámetros generales</h4>
                    </div>
                    <div class="modal-body">
						<form class="form-horizontal" id="jq-validation-form" novalidate="novalidate">
							<div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Logo:</label>
                                <div class="col-sm-7" style="position:relative">
									<button class="btn btn-primary btn-file-upload" style="margin-right:15px">Subir</button>
									<span class="sin-logo" style="<?php echo $logo?'display:none':'' ?>"><em>Ninguno</em></span>
									<span class="logo" style="<?php echo $logo?'':'display:none' ?>">
										<img style="height:31px" src="<?php echo $logo ? "$logo_url" : "" ?>" />
										<a class="btn-remover-logo" style="font-size:1.5rem;color:#ae6767" href="#" data-toggle="tooltip" data-placement="bottom" title="Remover Logo"><i class="fa fa-trash-alt"></i></a>
									</span>
									<input type="file" style="display:none" id="logo" name="logo" />
									<input type="hidden" id="action" name="action" value="store" />
                                </div>
                            </div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Empresa:</label>
								<div class="col-sm-7">
									<input required type="text" class="form-control" id="empresa" name="empresa" placeholder="Nombre de la empresa" value="<?php echo $empresa ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Dirección:</label>
								<div class="col-sm-7">
									<textarea required id="direccion" name="direccion" placeholder="Dirección"><?php echo $direccion ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Teléfonos:</label>
								<div class="col-sm-7">
									<input required type="text" class="form-control" id="telefonos" name="telefonos" placeholder="Teléfonos" value="<?php echo $telefonos ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Página web:</label>
								<div class="col-sm-7">
									<input required type="text" class="form-control" id="web" name="web" placeholder="Página web" value="<?php echo $web ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Correo electrónico:</label>
								<div class="col-sm-7">
									<input required type="text" class="form-control" id="email" name="email" placeholder="Correo electrónico" value="<?php echo $email ?>">
								</div>
							</div>
						</form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						<button type="button" id="btn-previsualizar-pdf" class="btn btn-success"><i class="fas fa-file-pdf"></i> Vista previa</button>
						<button type="button" id="btn-guardar-configuracion-basica" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>
		<div id="modalreenvios" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <form id="programar-reenvios-formulario">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title modal-accion" id="myModalLabel">Reenvíos automáticos de cotizaciones</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group form-horizontal">
                                <label class="col-sm-3 control-label">Nombre del Remitente:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="remitente-nombre" name="remitente-nombre" value="<?php echo $remitentenombre ?>" placeholder="Nombre del remitente" required>
                                </div>
                            </div>
                            <div class="form-group form-horizontal">
                                <label class="col-sm-3 control-label">Correo del Remitente:</label>
                                <div class="col-sm-7">
                                    <input type="email" class="form-control" id="remitente-email" name="remitente-email" value="<?php echo $remitenteemail ?>" placeholder="Correo del remitente" required>
                                </div>
                            </div>
                            <?php if (count($correos) > 0) { ?>

                                <?php foreach ($correos as $i => $correo) { ?>

                                    <div id="email-field<?php echo $i+1; ?>">
                                        <div class="form-group form-horizontal mt-lg">
                                            <label class="col-sm-3 control-label">
												Correo <?php echo $i+1; ?>:<br />
												<i class="fa fa-question-circle btn-formato-info" data-toggle="tooltip" data-placement="bottom" title="Click para ver opciones de edición" style="cursor:pointer"></i>
											</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="asunto<?php echo $i+1; ?>" name="asunto<?php echo $i+1; ?>" value="<?php echo $correo->asunto; ?>" placeholder="Asunto del correo">
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="checkbox" name="correo<?php echo $i+1; ?>-checkbox" id="correo<?php echo $i+1; ?>-checkbox" <?php echo $correo->habilitado ? 'checked' : ''; ?>>
                                            </div>
                                            <div class="col-sm-1">Activar</div>
                                        </div>
                                        <div class="form-group form-horizontal">
                                            <div class="col-sm-7 col-md-offset-3">
                                                <textarea class="form-control" id="cuerpo<?php echo $i+1; ?>" rows="6" name="cuerpo<?php echo $i+1; ?>" placeholder="Cuerpo del correo"><?php echo preg_replace("/###/","\r\n", $correo->correo) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group form-horizontal">
                                            <div class="col-sm-7 col-md-offset-3">
                                                <input type="number" class="form-control" id="dias<?php echo $i+1; ?>" name="dias<?php echo $i+1; ?>" min="0" value="<?php echo $correo->dias ?>" placeholder="Dias para el reenvío">
                                            </div>
                                            <div class="col-sm-2">Dias</div>
                                        </div>
                                    </div>

                                <?php } ?>

                            <?php } else { ?>

                                <div id="email-field1">
                                    <div class="form-group form-horizontal mt-lg">
                                        <label class="col-sm-3 control-label">Correo 1:</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="asunto1" name="asunto1" value="" placeholder="Asunto del correo">
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" name="correo1-checkbox" id="correo1-checkbox" value="1">
                                        </div>
                                        <div class="col-sm-1">Activar</div>
                                    </div>
                                    <div class="form-group form-horizontal">
                                        <div class="col-sm-7 col-md-offset-3">
                                            <textarea class="form-control" id="cuerpo1" rows="6" name="cuerpo1" placeholder="Cuerpo del correo"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-horizontal">
                                        <div class="col-sm-7 col-md-offset-3">
                                            <input type="number" class="form-control" id="dias1" name="dias1" min="0" value="" placeholder="Dias para el reenvío">
                                        </div>
                                        <div class="col-sm-2">Dias</div>
                                    </div>
                                </div>

                            <?php } ?>

                            <div class="form-group form-horizontal">
                                <button id="btn-agregarcorreos" class="btn btn-secondary col-md-offset-2">Agregar</button>
                                <?php if (count($correos) > 1) { ?>
                                <button class="btn btn-secondary" id="btn-quitarcorreos">Quitar</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-reenvio-cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btn-guardarreenvio" class="btn btn-primary">Guardar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div id="modal-cotizador1-config" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">Configurar cotizaciones</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="jq-validation-form">
                            <div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Estados:</label>
								<div class="col-sm-6" id="div-estados">
									<span class="badge badge-info estado">COTIZADO</span>
									<span class="badge badge-success estado">CONCRETADO</span>
									<?php foreach ($estados as $estado): ?>
										<?php if ($estado != "COTIZADO" && $estado != "CONCRETADO"): ?>
											<span class="badge badge-secondary estado editable"><?php echo $estado ?></span>
										<?php endif ?>
									<?php endforeach ?>
								</div>
								<style>
									.badge.estado{margin-bottom:2px}
									.badge.estado.editable{cursor:pointer;padding-top:0}
									.badge.estado.editable:hover{background:#7788a2;border:1px solid #6f7e95;}
								</style>
                            </div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Agrega un nuevo estado:</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="campo-nuevoestado" name="campo-nuevoestado" placeholder="Nuevo estado">
								</div>
								<button type="button" id="btn-nuevoestado" class="btn btn-success">Agregar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
		<div id="modal-archivos-config" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">Configuración de archivos adjuntos</h4>
                    </div>
                    <div class="modal-body">
						<form class="form-horizontal" id="jq-validation-form" novalidate="novalidate">
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Tamaño máximo permitido (bytes):</label>
								<div class="col-sm-7">
									<input required type="number" class="form-control" id="max_size" name="max_size" placeholder="Tamaño máximo (bytes)" value="<?php echo $max_size ?>" min="0" />
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Borrar automáticamente despues de:</label>
								<div class="col-sm-6">
									<input required type="number" class="form-control" id="dias_borrado" name="dias_borrado" placeholder="Dias de borrado automático" value="<?php echo $dias_borrado ?>" min="0" />
								</div>
								<label for="jq-validation-required" class="col-sm-1 control-label" style="text-align:left">dias.</label>
							</div>
						</form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						<button type="button" id="btn-guardar-config-archivos" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>
		<div id="modal-editarestado" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">Editar estado</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="jq-validation-form">
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Estado:</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="campo-estadonombre" name="campo-estadonombre" placeholder="Nombre del estado">
									<input type="hidden" class="form-control" id="campo-estadonombreprevio" name="campo-estadonombreprevio">
								</div>
								<button type="button" id="btn-eliminarestado" class="btn btn-danger">Eliminar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-cambiarestado" class="btn btn-primary">Cambiar</button>
                    </div>
                </div>
            </div>
        </div>
		<div id="modalopcionesformato" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title modal-accion" id="myModalLabel">Opciones</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
							<h4>Opciones de Formato:</h4>
							<ul>
								<li>*cursivas*: <em>cursivas</em></li>
								<li>**negritas**: <strong>negritas</strong></li>
								<li>_subrayadas_: <u>subrayadas</u></li>
								<li>~tachadas~: <strike>tachadas</strike></li>
								<li><strong>Imágenes:</strong> !(url-de-la-imagen)</li>
							</ul>
							<br />
							<h4>Datos:</h4>
							<ul>
								<li><strong>+nombre+:</strong> <em>nombre</em> del cliente</li>
								<li><strong>+apellido+:</strong> <em>apellido</em> del cliente</li>
								<li><strong>+cliente+:</strong> <em>nombre</em> completo del cliente</li>
								<li><strong>+correo+:</strong> <em>correo</em> del cliente</li>
								<li><strong>+telf+:</strong> <em>teléfono</em> del cliente</li>
							</ul>
						</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
		
        <div class="row">
            <div class="row">
                <?php 
                    if(isset($_GET['msg'])){
                        $mensaje = $_GET['msg'];
                        echo "<div class='alert alert-success'>".$mensaje."</div>";
                    }
                ?>                
            </div>
            <div class="col-md-12">
                <div class="col-sm-4">
                    <div class="stat-panel bt-email">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-warning valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-envelope-o bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>E-MAIL</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div> <!-- /.stat-cell -->
                    </div> <!-- /.stat-panel -->
                </div>
                <div class="col-sm-4">
                    <div class="stat-panel cfg-parametros-basicos">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-success valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-file-pdf bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>Datos generales para el documento</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div> <!-- /.stat-cell -->
                    </div> <!-- /.stat-panel -->
                </div>
                <div class="col-md-4">
                    <div class="stat-panel bt-cotizador1-config">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-danger valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-cogs bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>CONFIGURAR ESTADOS</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-panel bt-reenvios">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-primary valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-bell-o bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>REENVÍOS AUTOMÁTICOS</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-panel bt-archivos">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-info valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-paperclip bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>Archivos adjuntos</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- / #content-wrapper -->
        <div id="main-menu-bg"></div>
    </div> <!-- / #main-wrapper -->

    <?php if ($load_resources_locally): ?>
        <script src="<?php echo $js_url?>/jquery-2.0.3.min.js"></script>
    <?php else: ?>
    <!-- Get jQuery from Google CDN -->
    <!--[if !IE]> -->
    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js">'+"<"+"/script>"); </script>
    <!-- <![endif]-->
    <!--[if lte IE 9]>
    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">'+"<"+"/script>"); </script>
    <![endif]-->
    <?php endif ?>


    <!-- Pixel Admin's javascripts -->
    <script src="<?php echo $js_url ?>/bootstrap.min.js"></script>
    <script src="<?php echo $js_url ?>/pixel-admin.min.js"></script>

    <script type="text/javascript">
        init.push(function () {
            // Javascript code here
        })
        window.PixelAdmin.start(init);
        Array.prototype.last = function(){
            return this[this.length - 1];
            //return this.slice(-1); // alternative!
        };
		$(".bt-email").on( "click", function() {
            $("#modalemail").modal("show");
        });
        $(".cfg-parametros-basicos").on( "click", function() {
            $("#modal-parametros-basicos").modal("show");
        });
        $(".bt-reenvios").on( "click", function() {
            $("#modalreenvios").modal("show");
        });
        $(".bt-cotizador1-config").on( "click", function() {
			$("#modal-cotizador1-config").modal("show");
        });
        $(".bt-archivos").on( "click", function() {
			$("#modal-archivos-config").modal("show");
        });
		
		$("#btn-guardar-email").on( "click", function() {
            var params = { 
                emailremitente : $("#emailremitente").val(),
                emailconf : $("#emailconf").val(),
                asuntoemail : $("#asuntoemail").val(),
                mensajefinal : $("#mensajefinal").val(),
                mensajeprincipal : $("#mensajeprincipal").val(),
                mensajeintermedio : $("#mensajeintermedio").val(),
                mail_logo : $("#mail_logo").val(),
            }
            $.ajax({
                url : 'guardar-configuracion-email.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuracion.php?msg="+respuesta; 
                    var url = "configuracion-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
		
		$("#btn-previsualizar-pdf").on( "click", function() {
			let empresa		= "empresa="	+ $("#empresa").val(),
				direccion	= "&direccion="	+ $("#direccion").val().replace(/\n/g, "<br>"),
				telefonos	= "&telefonos="	+ $("#telefonos").val(),
				web			= "&web="		+ $("#web").val(),
				email		= "&email="		+ $("#email").val();
			
			let logo = "&logo=" + $('.logo img').attr('src').split("/").last();
			
			let url_params = empresa + direccion + telefonos + web + email + logo;
			window.open("generar-pdf.php" + "?" + url_params, "_blank");
		});
		
		$("#btn-guardar-configuracion-basica").on( "click", function() {
			$.ajax({
				url : 'guardar-configuracion.php',
				data : {
					empresa: $("#empresa").val(),
					direccion: $("#direccion").val().replace(/\n/g, "<br>"),
					telefonos: $("#telefonos").val(),
					web: $("#web").val(),
					email: $("#email").val(),
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
                    let url = "configuracion.php?msg="+respuesta; 
                    url = "configuracion-"+respuesta; 
                    $(location).attr('href', url);
				},
				error : function(xhr, status) {
					alert('Ocurrió un problema. Asegúrese de contar con conexión a la red y compruebe si el problema persiste.');
				},
			});
		});
		
		$(".btn-file-upload").on( "click", function(e) {
			e.preventDefault();
			$("#logo").click();
		});
		
		$("#logo").change(function() {
			var file = this.files[0];
			if (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/jpg") {
				$('.btn-file-upload').prop('disabled', 'true').text("Subiendo...");
				
				$.ajax({
					url: "guardar-configuracion.php",
					xhr: function () { // custom xhr (is the best)

						var xhr = new XMLHttpRequest();
						var total = 0;

						// Get the total size of files
						$.each(document.getElementById('logo').files, function (i, file) {
							total += file.size;
						});

						// Called when upload progress changes. xhr2
						xhr.upload.addEventListener("progress", function (evt) {
							// show progress like example
							var loaded = (evt.loaded / total).toFixed(2) * 100; // percent

							//$('.btn-file-upload').text("Subiendo... " + loaded + "%");
						}, false);

						return xhr;
					},
					type: 'POST',
					processData: false,
					contentType: false,
					data: new FormData(document.querySelector("#modal-parametros-basicos form")),
					success: function (respuesta) {
						let rsp = jQuery.parseJSON(respuesta);
						if (rsp.result != "SUCCESS") {
							alert( rsp.result );
						} else {
							$('.btn-file-upload').prop('disabled', '').text("Subir");
							$('.sin-logo').hide();
							$('.logo').show();
							$('.logo img').attr("src", "<?php echo $basehttp.$presupuestos_pdf_logo ?>" + '/' + rsp.filename);
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + ', ' + thrownError + '\n');
						$('#imagePreviewRow').find('.col-md-3').last().remove();
					}
				});
				$('#btn-upload-images').attr('disabled', false);
			} else {
				alert('ERROR: solo son admitidos los formatos jpeg, jpg y png.');
				return false;
			}
		});
		
		$('.btn-remover-logo').on( "click", function() {
			$.ajax({
                url : 'guardar-configuracion.php',
                data : {
					logo: $('.logo img').attr('src').split('/').last(),
					action: "delete",
				},
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
					let rsp = jQuery.parseJSON(respuesta);
					if (rsp.result != "SUCCESS") {
						alert(respuesta);
					}else{
						$('.sin-logo').show();
						$('.logo').hide();
						$('.logo img').attr("src", "");
					}
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
		});
		
		$('[data-toggle="tooltip"]').tooltip()
		
		$('.btn-formato-info').on('click', function(){
			$('#modalopcionesformato').modal('show')
		});
		
		var j = <?php echo (count($correos)==0) ? 1 : count($correos) ?>;
		
		var habilitarCampos = function(i, prefijo, flag) {
            $("#"+prefijo+"asunto"+i).prop("disabled", !flag).prop("required", flag);
            $("#"+prefijo+"cuerpo"+i).prop("disabled", !flag).prop("required", flag);
            $("#"+prefijo+"dias"+i).prop("disabled", !flag).prop("required", flag);
		};

        for (var i = 1; i <= j; i++) {
			habilitarCampos( i, "", $("#correo"+i+"-checkbox").prop("checked") );

            $("#correo"+i+"-checkbox").on( "change", {value: i}, function(e) {
				habilitarCampos( e.data.value, "", this.checked );
            });
        };

        if (j > 1) {
            $("#btn-quitarcorreos").on( "click", function(e) {
                e.preventDefault();
                $("#email-field"+j).remove();
                j--;
                if (j == 1) {
                    $(this).remove();
                }
            });
        }

        $("#btn-agregarcorreos").on( "click", function(e) {

            e.preventDefault();

            j++;

            $(this).parent().before(
                $("<div>").css("display", "none").attr("id", "email-field"+j).append(
                    $("<div>").addClass("form-group form-horizontal mt-lg").append(
                        $("<label>").addClass("col-sm-3 control-label").append( "Correo "+j+":" )
                    ).append(
                        $("<div>").addClass("col-sm-7").append(
                            $("<input>").attr("type", "text").addClass("form-control").attr("id", "asunto" + j)
                                .attr("name", "asunto" + j).attr("placeholder", "Asunto del correo")
                        )
                    ).append(
                        $("<div>").addClass("col-sm-1").append(
                            $("<input>").attr("type", "checkbox").attr("name", "correo"+j+"-checkbox")
                                .attr("id", "correo"+j+"-checkbox").prop("checked", true)
                        )
                    ).append(
                        $("<div>").addClass("col-sm-1").append( "Activar" )
                    )
                ).append(
                    $("<div>").addClass("form-group form-horizontal").append(
                        $("<div>").addClass("col-sm-7 col-md-offset-3").append(
                            $("<textarea>").addClass("form-control").attr("id", "cuerpo"+j)
                                .attr("rows", "6").attr("name", "cuerpo"+j).attr("placeholder", "Cuerpo del correo")
                        )
                    )
                ).append(
                    $("<div>").addClass("form-group form-horizontal").append(
                        $("<div>").addClass("col-sm-7 col-md-offset-3").append(
                            $("<input>").attr("type", "number").addClass("form-control").attr("id", "dias"+j)
                                .attr("name", "dias"+j).attr("min", "1").attr("placeholder", "Dias para el reenvío")
                        )
                    ).append(
                        $("<div>").addClass("col-sm-2").append( "Dias" )
                    )
                )
            );

            $("#asunto"+j).prop("required", true);
            $("#cuerpo"+j).prop("required", true);
            $("#dias"+j).prop("required", true);

            $("#correo"+j+"-checkbox").on( "change", {value: j}, function(e) {
				habilitarCampos( e.data.value, "", this.checked );
            });

            $("#email-field"+j).slideDown();

            if (j <= 2) {
                $(this).parent().append(
                    $("<button>").addClass("btn btn-secondary").attr("id", "btn-quitarcorreos").append( "Quitar" )
                );
                $("#btn-quitarcorreos").on( "click", function(e) {
                    e.preventDefault();
                    $("#email-field"+j).remove();
                    j--;
                    if (j == 1) {
                        $(this).remove();
                    }
                });
            }
        });


        $("#btn-guardarreenvio").on( "click", function(e) {

            // Validar
            for (var i = 1; i <= j; i++) {

                if ( !$("#remitente-nombre").val() ) return;
                if ( !$("#remitente-email").val() ) return;

                if ($("#correo"+i+"-checkbox").prop("checked")) {

                    if (!$("#asunto"+i).val()) return;
                    if (!$("#cuerpo"+i).val()) return;
                    if (!$("#dias"+i).val()) return;
                }
            }

            e.preventDefault();

            $("#btn-guardarreenvio").prop("disabled", true).text( "Guardando..." );
            $("#btn-reenvio-cancelar").prop("disabled", true);
            $("#btn-agregarcorreos").prop("disabled", true);
            $("#btn-quitarcorreos").prop("disabled", true);

            $("#remitente-nombre").prop("disabled", true);
            $("#remitente-email").prop("disabled", true);

            json_obj = [];

            for (var i = 1; i <= j; i++) {

                $("#correo"+i+"-checkbox").prop("disabled", true);
                $("#asunto"+i).prop("disabled", true);
                $("#cuerpo"+i).prop("disabled", true);
                $("#dias"+i).prop("disabled", true);

                json_obj.push({
                    habilitado: $("#correo"+i+"-checkbox").prop("checked"),
                    asunto: $("#asunto"+i).val(),
                    correo: $("#cuerpo"+i).val(),
                    dias: $("#dias"+i).val()
                });
            };

            $.ajax({
                url : 'guardar-config-reenvios.php',
                data : {
                    remitentenombre: $("#remitente-nombre").val(),
                    remitenteemail: $("#remitente-email").val(),
                    correos: JSON.stringify(json_obj)
                },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuracion.php?msg="+respuesta; 
                    var url = "configuracion-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
		
		$("#btn-nuevoestado").on( "click", function() {
			if ( $('#campo-nuevoestado').val() != "" ) {
				
				let nuevo_estado = $('#campo-nuevoestado').val();
				
				let nuevos_estados = [];
				$('.badge.estado.editable').each(function(){
					nuevos_estados.push( $(this).text() )
				});
				nuevos_estados.push( nuevo_estado );
				
				$.ajax({
					url : 'guardar-config-estados.php',
					data : {
						estados: [
							"COTIZADO", "CONCRETADO"
						].concat(Array.prototype.slice.call(nuevos_estados)).join("|"),
					},
					type : 'GET',
					dataType : 'html',
					success : function(respuesta) {
						if ( respuesta == "EXITO" ) {
							$('#div-estados').html( '<span class="badge badge-info estado">COTIZADO</span> <span class="badge badge-success estado">CONCRETADO</span>' );
							for (let i=0; i<nuevos_estados.length; i++)
								$('#div-estados').append( ' <span class="badge badge-secondary estado editable">' + nuevos_estados[i] + '</span>' );
							agregarEventosAlBotonEstadoEditable();
							$('#campo-nuevoestado').val( "" );
						}
						else
							alert( "Ocurrió un problema tratando de guardar el nuevo estado '" + nuevo_estado + "'" );
					},
					error : function(xhr, status) {
						alert('Disculpe, existió un problema');
					},
				});
			}
        });
		
		function agregarEventosAlBotonEstadoEditable () {
			$(".badge.estado.editable").on( "click", function() {
				$('#modal-editarestado h4.modal-title').text( "Editar estado: " + $(this).text() )
				$('#campo-estadonombre').val( $(this).text() );
				$('#campo-estadonombreprevio').val( $(this).text() );
				$("#modal-editarestado").modal("show");
			});
		}
		
		agregarEventosAlBotonEstadoEditable();
		
        $("#btn-cambiarestado").on( "click", function() {
			let nombre = $('#campo-estadonombre').val();
			let nombre_previo = $('#campo-estadonombreprevio').val()
			
			if ( nombre != "" ) {
				
				if ( nombre != nombre_previo ) {
					
					let nuevos_estados = [];
					$('.badge.estado.editable').each(function(){
						let estado = $(this).text();
						nuevos_estados.push( (estado==nombre_previo) ? nombre : estado );
					});
					
					$.ajax({
						url : 'guardar-config-estados.php',
						data : {
							estados: [
								"COTIZADO", "CONCRETADO"
							].concat(Array.prototype.slice.call(nuevos_estados)).join("|"),
						},
						type : 'GET',
						dataType : 'html',
						success : function(respuesta) {
							if ( respuesta == "EXITO" ) {
								$('#div-estados').html( '<span class="badge badge-info estado">COTIZADO</span> <span class="badge badge-success estado">CONCRETADO</span>' );
								for (let i=0; i<nuevos_estados.length; i++)
									$('#div-estados').append( ' <span class="badge badge-secondary estado editable">' + nuevos_estados[i] + '</span>' );
								agregarEventosAlBotonEstadoEditable();
								$('#modal-editarestado').modal('hide');
							}
							else
								alert( "Ocurrió un problema tratando de guardar el nuevo estado '" + nombre + "'" );
						},
						error : function(xhr, status) {
							alert('Disculpe, existió un problema');
						},
					});
					
				} else $('#modal-editarestado').modal('hide');
				
			}
        });
		
        $("#btn-eliminarestado").on( "click", function() {
			let estado_a_eliminar = $('#campo-estadonombreprevio').val();
			
			let nuevos_estados = [];
			$('.badge.estado.editable').each(function(){
				if ( $(this).text() != estado_a_eliminar )
					nuevos_estados.push( $(this).text() );
			});
			
			$.ajax({
				url : 'guardar-config-estados.php',
				data : {
					estados: [
						"COTIZADO", "CONCRETADO"
					].concat(Array.prototype.slice.call(nuevos_estados)).join("|"),
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
					if ( respuesta == "EXITO" ) {
						$('#div-estados').html( '<span class="badge badge-info estado">COTIZADO</span> <span class="badge badge-success estado">CONCRETADO</span>' );
						for (let i=0; i<nuevos_estados.length; i++)
							$('#div-estados').append( ' <span class="badge badge-secondary estado editable">' + nuevos_estados[i] + '</span>' );
						agregarEventosAlBotonEstadoEditable();
						$('#modal-editarestado').modal('hide');
					}
					else
						alert( "Ocurrió un problema tratando de eliminar el estado '" + estado_a_eliminar + "'" );
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});
        });
		
		$("#btn-guardar-config-archivos").on( "click", function() {
			$.ajax({
				url : 'guardar-config-archivos.php',
				data : {
					max_size: $("#max_size").val(),
					dias_borrado: $("#dias_borrado").val(),
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
                    let url = "configuracion.php?msg="+respuesta; 
                    url = "configuracion-"+respuesta; 
                    $(location).attr('href', url);
				},
				error : function(xhr, status) {
					alert('Ocurrió un problema. Asegúrese de contar con conexión a la red y compruebe si el problema persiste.');
				},
			});
		});
		
    </script>

</body>
</html>
<?php }else{
    header("location: $basehttp/presupuestos/");
} ?>