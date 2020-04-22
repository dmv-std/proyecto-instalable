<?php if (file_exists("../config.php"))
        include ("../config.php");
    else { header("location: ../instalador"); exit(); }
?>
<?php session_start();
    if($_SESSION['presupuestos'] != 1){
        header ("Location: $basehttp");
    }
	if(empty($_SESSION['userPersona'])){
		header("location: $basehttp");
	} else {
		
		// Base de datos: Sistema
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){ // check connection
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		
		// Configuración
		$query = "SELECT * FROM presupuestos_configuracion WHERE id = '1'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$row = $result->fetch_assoc();
		$estados = $row['estados'] ? explode("|", $row['estados']) : ["COTIZADO", "CONCRETADO"];
		
		// Prespuestos
		$query = "	SELECT presupuestos.*,
						(SELECT COUNT(*) FROM presupuestos_archivos
						 WHERE presupuestos_archivos.id_presupuesto=presupuestos.id) AS archivos
					FROM presupuestos";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$presupuestos=array();
		while($row = $result->fetch_assoc()) {
			//$row1['observacion'] = str_replace("\n","<br>",$row1['observacion']);
			$presupuestos[] = $row;
		}

		// Usuarios
		$query = "SELECT user,id FROM sist_usuarios";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$usuarios=array();
		while($row = $result->fetch_assoc()) {
			$usuarios[$row['id']] = $row['user'];
		}
		
		// Cerrar conexión
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
	<link href="<?php echo $js_url ?>/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
	<!--<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">-->
	<script src="https://kit.fontawesome.com/b8c47e2cca.js" crossorigin="anonymous"></script><!-- Font Awesome kit latest -->
    <!--[if lt IE 9]>
    <script src="<?php echo $js_url ?>/ie.min.js"></script>
    <![endif]-->
	<style type="text/css">
		.w-auto{width:auto}
		.d-inline{display:inline}
		.w-auto-i{width:auto!important}
		.d-inline-i{display:inline!important}
		.date input{float:none!important}
		.date:not(.input-daterange) input+span{line-height:unset;vertical-align:unset}
		.date-range-from,.date-range-to,.daterange{display:none}
		.input-group label{display:table-cell;padding-right:5px}
		.btn-exportar{/*float:right*/}
		.btn-consultar{margin-left:4px}
		.export-section > *{float:right}
		span.date-filter{color:red;font-weight:bold;font-style:italic;padding-left:20px}
		.strong{font-weight:bold}
		.red{color:red}
		.em{font-style:italic}
		
		.small-screen{display:none}
		
		.btn-custom{
			border-color: #697282;
			border-bottom-color: #555c68;
			background-image: linear-gradient(to bottom,#707f9b 0,#626b79 100%);
			color: #fff;
		}
		.btn-custom:hover,.btn-custom:focus{
			border-color: #697282;
			border-bottom-color: #555c68;
			background-image: linear-gradient(to bottom,#64718a 0,#4e5560 100%) !important;
			color: #fff;
		}
		.documento{
			padding: 0 !important;
			text-align: center;
			vertical-align: middle !important;
		}
		.documento a{
			font-size: 2rem;
			margin: 0px 10px;
			color: #bb1919;
			-webkit-transition: all 500ms ease;
			-moz-transition: all 500ms ease;
			-ms-transition: all 500ms ease;
			-o-transition: all 500ms ease;
			transition: all 500ms ease;
		}
		.documento a:hover{color: #570b0b}
		.documento div{display:flex;justify-content:center}
		
		/*Breakpoints*/
		@media (min-width: 100px) and (max-width: 1125px) {
			/* ocultando columna: correo */
			#tabla-presupuestos tr td:nth-of-type(5), #tabla-presupuestos tr th:nth-of-type(5){display:none!important}
		}
		@media (min-width: 100px) and (max-width: 890px) {
			/* ocultando columna: telefono */
			#tabla-presupuestos tr td:nth-of-type(6), #tabla-presupuestos tr th:nth-of-type(6){display:none!important}
		}
		@media (min-width: 100px) and (max-width: 791px) {
			/* ocultando columna: usuario */
			#tabla-presupuestos tr td:nth-of-type(3), #tabla-presupuestos tr th:nth-of-type(3){display:none!important}
		}
		@media (min-width: 100px) and (max-width: 694px) {
			/* ocultando columna: id */
			#tabla-presupuestos tr td:nth-of-type(1), #tabla-presupuestos tr th:nth-of-type(1){display:none!important}
		}
		@media (min-width: 100px) and (max-width: 661px) {
			/* ocultando columna: fecha */
			#tabla-presupuestos tr td:nth-of-type(2), #tabla-presupuestos tr th:nth-of-type(2){display:none!important}
		}
		@media (min-width: 100px) and (max-width: 575px) {
			/* ocultando columna: estado */
			#tabla-presupuestos tr td:nth-of-type(8), #tabla-presupuestos tr th:nth-of-type(8){display:none!important}
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
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Listado de presupuestos</h1>
            </div>
        </div> <!-- / .page-header -->


        <div class="row">
            <div class="row">
                <?php 
                    if(isset($_GET['msg'])){
                        $mensaje = $_GET['msg'];
                        echo "<div class='alert alert-success'>".$mensaje."</div>";
                    }
                ?>
            </div>
            <div class="row">
                <script>
                    init.push(function () {
                        $('#tabla-presupuestos').dataTable({
                            "order": [[ 0, "desc" ]]
                        } );
                        $('#tabla-presupuestos_wrapper .table-caption').text('PRESUPUESTOS');
                        $('#tabla-presupuestos_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
                    });
                </script> <!-- / Javascript -->
				
				
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
											<input type="text" class="form-control" id="nombreestatus" name="nombreestatus" placeholder="Cliente" />
											<input type="hidden" class="form-control" id="idpresupuesto" name="idpresupuesto" />
										</div>
									</div>
									<div class="form-group">
										<label for="jq-validation-required" class="col-sm-3 control-label">Estado</label>
										<div class="col-sm-9">
											<select id="select-cotiz-estatus" class="form-control">
												<?php foreach($estados as $estado): ?>
												<option value="<?php echo $estado ?>">
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
				<div id="modal-editar" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id="form-modificar-presupuesto">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h4 class="modal-title">Modificar datos de presupuesto</h4>
								</div>
								<div class="modal-body">
									<input type="hidden" id="prs-id" value="" />
									<div class="form-group">
										<label class="col-md-3 form-label" for="cliente-nombre">Nombre:</label>
										<div class="col-md-9 input-group">
											<input type="text" class="form-control" id="cliente-nombre" placeholder="Nombre del cliente" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 form-label" for="cliente-nombre">Apellido:</label>
										<div class="col-md-9 input-group">
											<input type="text" class="form-control" id="cliente-apellido" placeholder="Apellido del cliente" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 form-label" for="cliente-nombre">Teléfono:</label>
										<div class="col-md-9 input-group">
											<input type="text" class="form-control" id="cliente-telf" placeholder="Teléfono del cliente" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 form-label" for="cliente-nombre">Correo:</label>
										<div class="col-md-9 input-group">
											<input type="email" class="form-control" id="cliente-correo" placeholder="Correo del cliente" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 form-label" for="prs-detalles">
											Detalle de presupuesto:<br/>
											<i class="fa fa-question-circle btn-prs-detalles-info" data-toggle="tooltip" data-placement="bottom" title="Negritas: **n**, Cursivas: *c*, Subrayado: _u_, tachado: ~s~" style="cursor:pointer"></i>
										</label>
										<div class="col-md-9 input-group">
											<textarea class="form-control" id="prs-detalles" rows="4" placeholder="Agregar detalle de presupuesto" style="resize:none" required></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 form-label" for="prs-condiciones">Condiciones:</label>
										<div class="col-md-9 input-group">
											<textarea class="form-control" id="prs-condiciones" rows="3" placeholder="Agregar condiciones" style="resize:none"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 form-label" for="prs-condiciones">Importe cotizado ($):</label>
										<div class="col-md-9 input-group">
											<input type="number" step="0.01" class="form-control" id="prs-importe" required />
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
									<button class="btn btn-success btn-modificar" type="submit">Modificar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">
							Listado de presupuestos
							<?php /* ?>
							-
							<?php if ( isset($_GET['range']) || isset($_GET['user']) ): ?>
								<span class="strong red">
									(Mostrando resultados para
									<?php echo isset($_GET['user']) ? "Usuario: $user" : "" ?>
									<?php echo (isset($_GET['user'])&&isset($_GET['range'])) ? "y" : "" ?>
									Fecha: <?php echo date("d/m/Y", strtotime($start)) ?> - <?php echo date("d/m/Y", strtotime($end)) ?>)
								</span>
							<?php else: ?>
								<span class="strong em">Vista: últimos 15 dias</span>
							<?php endif ?>
							<?php */ ?>
						</span>
                    </div>
                    <div class="panel-body">
						<!--
						<div class="form-group">
							<div class="row">
								<div class="col-md-12 export-section">
									<button type="button" class="btn btn-success btn-exportar">Exportar a CSV</button>
									<button type="button" class="btn btn-info btn-consultar">Consultar</button>
									<div class="col-md-3 w-auto">
										<label for="date-select">
											Usuario:
										</label>
										<select class="custom-select form-control d-inline w-auto" id="user-select">
											<option value="all">Todos</option>
											<option value="CLIENTE">CLIENTE</option>
											<?php /*foreach ($usuarios as $usuario): ?>
												<option value="<?php echo $usuario ?>"><?php echo $usuario ?></option>
											<?php endforeach*/ ?>
										</select>
									</div>
									<div class="col-md-3 w-auto">
										<label for="date-select">
											Fecha:
										</label>
										<select class="custom-select form-control d-inline w-auto" id="date-select">
											<option value="default">Por defecto</option>
											<option value="any">Cualquier fecha...</option>
											<option value="today">Hoy.</option>
											<option value="last-7-days">Últimos 7 días.</option>
											<option value="last-15-days">Últimos 15 días.</option>
											<option value="last-month">Último mes.</option>
											<option value="last-6-months">Últimos 6 meses.</option>
											<option value="last-year">Último año.</option>
											<option value="range">Seleccionar rango...</option>
										</select>
									</div>
									<div id="date-range">
										<div class="col-md-3 w-auto date-range-from">
											<label for="date-range">
												Desde:
											</label>
											<div class="input-group date d-inline w-auto">
												<input type="text" class="form-control d-inline-i w-auto-i"><span class="input-group-addon d-inline w-auto"><i class="glyphicon glyphicon-th"></i></span>
											</div>
										</div>
										<div class="col-md-3 w-auto date-range-to">
											<label for="date-range">
												Hasta:
											</label>
											<div class="input-group date d-inline w-auto">
												<input type="text" class="form-control d-inline-i w-auto-i"><span class="input-group-addon d-inline w-auto"><i class="glyphicon glyphicon-th"></i></span>
											</div>
										</div>
										<div class="col-md-3 w-auto daterange">
											<div class="input-daterange date input-group" id="datepicker">
												<label>
													Rango:
												</label>
												<input type="text" class="input-sm form-control" name="start" />
												<span class="input-group-addon">a</span>
												<input type="text" class="input-sm form-control" name="end" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						-->
                        <div class="table-primary">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tabla-presupuestos">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Cliente</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Importe</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
									<?php if ($_SESSION['permisosPersona']=="admin"): ?>
										<th><input type="checkbox" id="checkbox-select-all"></th>
									<?php endif ?>
                                </tr>
                                </thead>
                                <tbody>
									<?php foreach ($presupuestos as $presupuesto): ?>
										<tr data-id="<?php echo $presupuesto['id'] ?>"
											data-nombre="<?php echo $presupuesto['nombre'] ?>"
											data-apellido="<?php echo $presupuesto['apellido'] ?>"
											data-correo="<?php echo $presupuesto['correo'] ?>"
											data-telefono="<?php echo $presupuesto['telefono'] ?>"
											data-detalles="<?php echo $presupuesto['detalles'] ?>"
											data-condiciones="<?php echo $presupuesto['condiciones'] ?>"
											data-importe="<?php echo $presupuesto['importe'] ?>"
											data-estado="<?php echo $presupuesto['estado'] ?>"
										>
											<td><?php echo $presupuesto['id'] ?></td>
											<td><?php echo date("d/m/Y", strtotime($presupuesto['fecha'])) ?></td>
											<td><?php echo $usuarios[$presupuesto['id_usuario']] ?></td>
											<td><?php echo "$presupuesto[nombre] $presupuesto[apellido]" ?></td>
											<td><?php echo $presupuesto['correo'] ?></td>
											<td><?php echo $presupuesto['telefono'] ?></td>
											<td class="text-right">$ <?php echo number_format($presupuesto['importe'], 2, ",", ".") ?></td>
											<?php if ($presupuesto['estado']=="COTIZADO"): ?>
												<td><button class="btn-estatus col-xs-12 btn btn-info"><?php echo $presupuesto['estado'] ?></button></td>
											<?php elseif ($presupuesto['estado']=="CONCRETADO"): ?>
												<td><button class="btn-estatus col-xs-12 btn btn-success"><?php echo $presupuesto['estado'] ?></button></td>
											<?php else: ?>
												<td><button class="btn-estatus col-xs-12 btn btn-custom"><?php echo $presupuesto['estado'] ?></button></td>
											<?php endif ?>
											<td class="documento">
												<div>
													<a class="" href="<?php echo $presupuesto['id'] ?>" data-toggle="tooltip" data-placement="bottom" title="Ir a presupuesto"><i class="fa fa-external-link"></i></a>
													<a class="" href="<?php echo $presupuesto['id'] ?>/pdf" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Ver presupuesto"><i class="far fa-file-pdf"></i></a>
													<a class="" href="<?php echo $presupuesto['id'] ?>/pdf/exportar" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Descargar presupuesto"><i class="fas fa-file-pdf"></i></a>
													<a class="btn-editar" href="#" data-toggle="tooltip" data-placement="bottom" title="Modificar"><i class="fas fa-pen"></i></a>
													<?php if($presupuesto['archivos'] > 0): ?>
														<a class="btn-archivos" href="#" data-toggle="tooltip" data-placement="bottom" title="<?php echo $presupuesto['archivos'] ?> archivos"><i class="fa fa-paperclip"></i></a>
													<?php endif ?>
												</div>
											</td>
											<?php if ($_SESSION['permisosPersona']=="admin"): ?>
												<td class="checkbox-td"><input type="checkbox" data-id="<?php echo $presupuesto['id'] ?>"></td>
											<?php endif ?>
										</tr>
									<?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
						
						<div class="alert alert-warning eliminar-presupuestos" role="alert">
							<span><i class="fa fa-exclamation-triangle"></i> Opciones:</span>
							<button class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar presupuestos</button>
							<button class="btn btn-warning"><i class="fa fa-paperclip"></i> Eliminar solo archivos</button>
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


	<script src="<?php echo $js_url ?>/jquery-ui-1.12.1/jquery-ui.min.js"></script>

    <!-- Pixel Admin's javascripts -->
    <script src="<?php echo $js_url ?>/bootstrap.min.js"></script>
    <script src="<?php echo $js_url ?>/pixel-admin.min.js"></script>

    <script type="text/javascript">
        init.push(function () {
            // Javascript code here
        })
        window.PixelAdmin.start(init);
		
		$(".btn-estatus").on( "click", function() {
			let id			= $(this).closest('tr').data('id'),
				nombre		= $(this).closest('tr').data('nombre'),
				apellido	= $(this).closest('tr').data('apellido'),
				estado		= $(this).closest('tr').data('estado');
			$("#nombreestatus").val(nombre+' '+apellido);
			$("#select-cotiz-estatus").val(estado);
			$("#idpresupuesto").val(id);
			$("#modalestatus").modal("show");
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
								var url = "presupuestos.php?id="+params.idpresupuesto+"&msg="+respuesta;
								url = "listado-"+respuesta;
								$(location).attr('href',url);
							},
							error : function(xhr, status) {
								alert('Error: el estatus fue cambiado pero no se pudo crear observación.');
							},
						});
					} else {
						var url = "presupuestos.php?id="+params.idpresupuesto+"&msg="+respuesta;
						url = "listado-"+respuesta;
						$(location).attr('href',url);
					}
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
		});

		$('.btn-archivos,.btn-editar').on('click', function(e){
			e.preventDefault()
		})
		
		$(".btn-editar").on('click', function(){
			let id			= $(this).closest('tr').data('id'),
				nombre		= $(this).closest('tr').data('nombre'),
				apellido	= $(this).closest('tr').data('apellido'),
				correo		= $(this).closest('tr').data('correo'),
				telefono	= $(this).closest('tr').data('telefono'),
				detalles	= $(this).closest('tr').data('detalles'),
				condiciones	= $(this).closest('tr').data('condiciones'),
				importe		= $(this).closest('tr').data('importe');
			$("#prs-id").val(id);
			$("#cliente-nombre").val(nombre);
			$("#cliente-apellido").val(apellido);
			$("#cliente-correo").val(correo);
			$("#cliente-telf").val(telefono);
			$("#prs-detalles").val(detalles);
			$("#prs-condiciones").val(condiciones);
			$("#prs-importe").val(importe);
			
			$("#modal-editar h4.modal-title").text( "Modificar datos de presupuesto #"+id );
			$("#modal-editar").modal("show");
		});
		
		$('#form-modificar-presupuesto').on('submit', function(e){
			e.preventDefault();
			
			// Validando campo importe:
			let importe = document.querySelector("#prs-importe").value;
			if (!importe.match(/^\d+(?:[\.,]\d+)?$/)) {
				alert(	"Ha ingresado un formato para el Importe Cotizado no válido.\n"
						+ "Se acepta únicamente números (opcionalmente con decimales separados por puntos ó comas)");
				return;
			}
			if (importe.match(/,/))
				importe = importe.replace(",", ".");
			
			$.ajax({
				url : 'modificar.php',
				data : {
					id: $('#prs-id').val(),
					nombre: $('#cliente-nombre').val(),
					apellido: $('#cliente-apellido').val(),
					telf: $('#cliente-telf').val(),
					correo: $('#cliente-correo').val(),
					detalles: $('#prs-detalles').val(),
					condiciones: $('#prs-condiciones').val(),
					importe: importe,
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
					if (respuesta == "SUCCESS") {
						let fecha = $('#sancion-fecha').val(),
							empleado = $('#sancion-empleado').val(),
							tipo = $( "#sancion-tipo option:selected" ).text();
						var url = "presupuestos.php?msg=" + "Presupuesto modificado con éxito!"; 
						url = "listado-" + "Presupuesto modificado con éxito!"; 
						$(location).attr('href',url);
					}
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});
		});
		
		// documentation: https://uxsolutions.github.io/bootstrap-datepicker
		$('#date-range .input-group.date').datepicker({
			//format: 'dd/mm/yyyy'
			format: 'yyyy-mm-dd'
		});
		$('#date-select').change(function() {
			if ( $(this).children("option:selected").val() == "range" ) {
				$('.daterange').slideDown();
			} else {
				$('.daterange').slideUp();
			}
		});
		
		$('.btn-consultar').click( function() {
			var user = $('#user-select').children("option:selected").val();
			var sel = $('#date-select').children("option:selected").val();
			var start = $('#datepicker input[name=start]').val();
			var end = $('#datepicker input[name=end]').val();
			var user_param, range_param;
			
			user_param = ( user == "all" ) ? "" : "user="+user;
			
			if ( sel == "any") {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(0));
				end = $.datepicker.formatDate("yy-mm-dd", new Date());
				range_param="range="+start+","+end;
			} else if (sel == "range" ) {
				start=(start=="")?($.datepicker.formatDate("yy-mm-dd", new Date(0))):start;
				end=(end=="")?($.datepicker.formatDate("yy-mm-dd", new Date())):end;
				range_param="range="+start+","+end;
			} else if ( sel == "default" ) {
				range_param = "";
			} else if ( sel == "today" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date());
			} else if ( sel == "last-7-days" ) {
				// https://stackoverflow.com/questions/33204168/jquery-new-date-convert-to-yyyy-mm-dd-and-use-tolocaledatestring
				//start = new Date(Date.now() - 7*24*60*60*1000).toLocaleDateString("es-AR",{year:"numeric",month:"2-digit",day:"2-digit"});
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 7*24*60*60*1000));
			} else if ( sel == "last-15-days" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 15*24*60*60*1000));
			} else if ( sel == "last-month" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 30*24*60*60*1000));
			} else if ( sel == "last-6-months" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 6*30*24*60*60*1000));
			} else if ( sel == "last-year" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 365*24*60*60*1000));
			}
			if ( sel != "range" && sel != "any" && sel != "default" ) {
				end = $.datepicker.formatDate("yy-mm-dd", new Date());
				range_param="range="+start+","+end;
			}
			if ( user_param == "" && range_param == "" ) {
				$(location).attr( 'href', 'cotizaciones.php' );
			} else if ( user_param == "" ) {
				$(location).attr( 'href', "cotizaciones.php?"+range_param );
			} else if ( range_param == "" ) {
				$(location).attr( 'href', "cotizaciones.php?"+user_param );
			} else {
				$(location).attr( 'href', "cotizaciones.php?"+user_param+"&"+range_param );
			}
		});
		
		$('.btn-exportar').click( function() {
			var user = $('#user-select').children("option:selected").val();
			var sel = $('#date-select').children("option:selected").val();
			var start = $('#datepicker input[name=start]').val();
			var end = $('#datepicker input[name=end]').val();
			var user_param, range_param;
			
			user_param = ( user == "all" ) ? "" : "user="+user;
			
			if ( sel == "any") {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(0));
				end = $.datepicker.formatDate("yy-mm-dd", new Date());
				range_param="range="+start+","+end;
			} else if (sel == "range" ) {
				start=(start=="")?($.datepicker.formatDate("yy-mm-dd", new Date(0))):start;
				end=(end=="")?($.datepicker.formatDate("yy-mm-dd", new Date())):end;
				range_param="range="+start+","+end;
			} else if ( sel == "default" ) {
				range_param = "";
			/*if ( sel == "any" ) {
				range_param = "";
			} else if ( sel == "range" ) {
				if ( start=="" && end=="" ) {
					range_param = "";
				} else {
					start=(start=="")?($.datepicker.formatDate("yy-mm-dd", new Date(0))):start;
					end=(end=="")?($.datepicker.formatDate("yy-mm-dd", new Date())):end;
					range_param="range="+start+","+end;
				}*/
			} else if ( sel == "today" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date());
			} else if ( sel == "last-7-days" ) {
				// https://stackoverflow.com/questions/33204168/jquery-new-date-convert-to-yyyy-mm-dd-and-use-tolocaledatestring
				//start = new Date(Date.now() - 7*24*60*60*1000).toLocaleDateString("es-AR",{year:"numeric",month:"2-digit",day:"2-digit"});
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 7*24*60*60*1000));
			} else if ( sel == "last-15-days" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 15*24*60*60*1000));
			} else if ( sel == "last-month" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 30*24*60*60*1000));
			} else if ( sel == "last-6-months" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 6*30*24*60*60*1000));
			} else if ( sel == "last-year" ) {
				start = $.datepicker.formatDate("yy-mm-dd", new Date(Date.now() - 365*24*60*60*1000));
			}
			if ( sel != "range" && sel != "any" && sel != "default" ) {
				end = $.datepicker.formatDate("yy-mm-dd", new Date());
				range_param="range="+start+","+end;
			}
			if ( user_param == "" && range_param == "" ) {
				$(location).attr( 'href', 'csv.php?source=cotizaciones.php' );
			} else if ( user_param == "" ) {
				$(location).attr( 'href', 'csv.php'+"?source=cotizaciones.php&"+range_param );
			} else if ( range_param == "" ) {
				$(location).attr( 'href', 'csv.php'+"?source=cotizaciones.php&"+user_param );
			} else {
				$(location).attr( 'href', 'csv.php'+"?source=cotizaciones.php&"+user_param+"&"+range_param );
			}
			console.log({sel:sel,range_param:range_param,start:start,end:end,user_param:user_param})
		});
		
		$('button.small-screen').click(function(){
			var td = $(this).closest('tr').find('td');
			for (var i=2; i<13;i++)
				if ( $(td[i]).css('display') == 'none' )
					$( td[i] ).css('display', 'inline-block');
				else
					$( td[i] ).css('display', 'none');
		});
		
		<?php if ($_SESSION['permisosPersona']=="admin"): ?>
		var toDelete = [];
		$(".checkbox-td, th:last-child").on( "click", function() {
			var $checkbox = $(this).find("input[type=checkbox]");
			var checked = $checkbox.prop( "checked" );
			
			$(this).parent().toggleClass( "checked" );
			$checkbox.prop( "checked", $(this).parent().hasClass("checked") );
			
			if ( $checkbox.parent().parent().attr("role") ) {
				
				var $tr = $(this).closest("table").find("tbody tr");
				var $checkboxes = $tr.find("input[type=checkbox]");
				
				$tr.toggleClass( "checked", $(this).parent().hasClass("checked") );
				$checkboxes.prop( "checked", $(this).parent().hasClass("checked") );
				
				// Determinar ids para borrar
				$.each( $(".table-striped tbody tr"), function() {
					var id = $(this).find("input").data("id");
					if ( $(this).hasClass("checked") ) {
						if ( !toDelete.includes(id) )
							toDelete.push( id );
					} else {
						if ( toDelete.includes(id) )
							toDelete = toDelete.filter(function(e) { return e !== id })
					}
				});
				
			}
			else {
				
				// Determinar ids para borrar
				var id = $(this).find("input").data("id");
				if ($(this).parent().hasClass("checked")) {
					if ( !toDelete.includes(id) )
						toDelete.push( id );
				} else {
					if ( toDelete.includes(id) )
						toDelete = toDelete.filter(function(e) { return e !== id })
				}
			
				var $tr = $(this).closest("table").find("thead tr");
				var $checkboxes = $tr.find("input[type=checkbox]");
				
				if ( allEmpty( $(".table-striped").find("tbody").find("tr"), "checked" ) ) {
					$tr.toggleClass( "checked", false );
					$checkboxes.prop( "checked", false );
				} else {
					$tr.toggleClass( "checked", true );
					$checkboxes.prop( "checked", true );
				}
				
			}console.log("ids para borrar: "+toDelete.join(", "));
			
			if ( allEmpty( $(".table-striped").find("tbody").find("tr"), "checked" ) )
				$(".alert.eliminar-presupuestos").removeClass( "visible" );
			else
				$(".alert.eliminar-presupuestos").addClass( "visible" );
		});
		
		var allEmpty = function( data, class_name ) {
			var len = data.length;
			for (var i=0; i<len; i++)
			{
				if ( $(data[i]).hasClass(class_name) ) {
					return false;
				} else if ( i == (len-1) )
					return true;
			}
		};
		
		$(".alert.eliminar-presupuestos button.btn-danger").click(function() {
			$(this).prop("disabled", "true").text("Borrando...");
			console.log("ids para borrar: "+toDelete.join(", "));
			
			$.ajax({
				url : 'eliminar.php',
				data : {
					borrar: toDelete.join(","),
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
					var url = "presupuestos.php?msg="+respuesta; 
					url = "listado-"+respuesta; 
					$(location).attr('href',url);
				},
				error : function(xhr, status) {
					alert('Disculpe, ocurrió un problema');
				},
			});
		});
		$(".alert.eliminar-presupuestos button.btn-warning").click(function() {
			$(this).prop("disabled", "true").text("Borrando...");
			console.log("ids para borrar: "+toDelete.join(", "));
			
			$.ajax({
				url : 'eliminar-archivos.php',
				data : {
					borrar: toDelete.join(","),
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {console.log(respuesta)
					var url = "presupuestos.php?msg="+respuesta; 
					url = "listado-"+respuesta; 
					$(location).attr('href',url);
				},
				error : function(xhr, status) {
					alert('Disculpe, ocurrió un problema');
				},
			});
		});
		<?php endif ?>
		
		$('[data-toggle="tooltip"]').tooltip()
    </script>

</body>
</html>
<?php } ?>