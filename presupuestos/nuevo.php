<?php if (file_exists("../config.php"))
        include ("../config.php");
    else { header("location: ../instalador"); exit(); }
?>
<?php session_start();
	if(empty($_SESSION['userPersona'])){
		header("location: $basehttp");
	} else {
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname); // base de datos: sistema
		if($mysqli->connect_errno > 0){ // check connection
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		$query = "SELECT * FROM presupuestos_configuracion WHERE id = '1'";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$row = $result->fetch_assoc();
		
		$max_size = $row['max_size'];
		
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
    <link href="assets/css/main.css" rel="stylesheet" type="text/css">
	<!-- Open Sans font from Google CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <!-- Pixel Admin's stylesheets -->
    <link href="assets/stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/themes.min.css" rel="stylesheet" type="text/css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<style type="text/css">.fa-file-pdf::after{content:"\f1c1"}</style>
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
					<h1 class="col-xs-12 col-sm-4 text-center text-left-sm">
						<i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp; Crear presupuesto
					</h1>
				</div>
			</div> <!-- / .page-header -->
			<?php 
				if(isset($_GET['msg'])){
					$mensaje = $_GET['msg'];
					echo "<div class='alert alert-success'>".$mensaje."</div>";
				}
			?>
			
			<div id="modal-opciones-de-edicion" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title">Opciones de edición</h4>
						</div>
						<div class="modal-body">
							<div>*cursivas*: <i>cursivas</i></div>
							<div>**negritas**: <strong>negritas</strong></div>
							<div>_subrayadas_: <u>subrayadas</u></div>
							<div>~tachadas~: <strike>tachadas</strike></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="container">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title t-uppercase">Crear presupuesto - (<?php echo $_SESSION['userPersona'] ?>)</h3>
					</div>
					<div class="panel-body">
						<form id="formulario-crear-presupuesto">
							<input type="hidden" id="usuario-id" value="" />
							<div class="form-group">
								<label class="col-md-2 form-label" for="cliente-nombre">Nombre:</label>
								<div class="col-md-8 input-group">
									<input type="text" class="form-control" id="cliente-nombre" placeholder="Nombre del cliente" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 form-label" for="cliente-nombre">Apellido:</label>
								<div class="col-md-8 input-group">
									<input type="text" class="form-control" id="cliente-apellido" placeholder="Apellido del cliente" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 form-label" for="cliente-nombre">Teléfono:</label>
								<div class="col-md-8 input-group">
									<input type="text" class="form-control" id="cliente-telf" placeholder="Teléfono del cliente" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 form-label" for="cliente-nombre">Correo:</label>
								<div class="col-md-8 input-group">
									<input type="email" class="form-control" id="cliente-correo" placeholder="Correo del cliente" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 form-label" for="prs-detalles">
									Detalle de presupuesto:<br/>
									<i class="fa fa-question-circle btn-prs-detalles-info" data-toggle="tooltip" data-placement="bottom" title="Click para ver opciones de edición" style="cursor:pointer"></i>
								</label>
								<div class="col-md-8 input-group">
									<textarea class="form-control" id="prs-detalles" rows="4" placeholder="Agregar detalle de presupuesto" style="resize:none" required></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 form-label" for="prs-condiciones">Condiciones:</label>
								<div class="col-md-8 input-group">
									<textarea class="form-control" id="prs-condiciones" rows="3" placeholder="Agregar condiciones" style="resize:none"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 form-label" for="prs-importe">Importe cotizado ($):</label>
								<div class="col-md-8 input-group">
									<input type="number" step="0.01" min="0" class="form-control" id="prs-importe" required />
								</div>
							</div>
							<div class="form-group" id="form-group-archivos">
								<style type="text/css">
								#form-group-archivos .input-group>div{
									width: 25px;font-size: 1.85rem;display: flex;justify-content: center;align-items: center;margin-left: 10px;
								}
								.btn-remover-archivo{color:#ac0101;cursor:pointer}
								</style>
								<label class="col-md-2 form-label" for="prs-importe">Archivos:</label>
								<!--<div class="col-md-8 input-group">
									<input class="form-control" type="file" name="archivos" />
									<div><i class="fa fa-trash btn-remover-archivo" data-toggle="tooltip" data-placement="bottom" title="Remover"></i></div>
								</div>-->
								<button type="button" class="btn btn-secondary btn-agregar-archivo btn1">Agregar</button>
								<button type="button" class="btn btn-secondary btn-agregar-archivo btn2 col-md-offset-2" style="margin-top:8px;display:none">Agregar</button>
							</div>
							<div class="row text-right">
								<div class="col-md-offset-6 col-md-2">
									<button type="button" class="btn btn-info btn-previsualizar" style="width:100%"><i class="fa fa-file-pdf"></i> Previsualizar PDF</button>
								</div>
								<div class="col-md-2">
									<button class="btn btn-success btn-crear-presupuesto" style="width:100%" type="submit">Crear presupuesto</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div> <!-- / .container -->
		</div> <!-- / #content-wrapper -->
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
		
		$(".btn-prs-detalles-info").on( "click", function() {
			$("#modal-opciones-de-edicion").modal("show");
        });
		
		$(".btn-previsualizar").on( "click", function() {
			let usuario		= "usuario="		+ <?php echo $_SESSION['id'] ?>,
				nombre		= "&nombre=" 		+ $('#cliente-nombre').val(),
				apellido	= "&apellido=" 		+ $('#cliente-apellido').val(),
				telefono	= "&telefono=" 		+ $('#cliente-telf').val(),
				correo		= "&correo=" 		+ $('#cliente-correo').val(),
				detalles	= "&detalles=" 		+ $('#prs-detalles').val(),
				condiciones	= "&condiciones=" 	+ $('#prs-condiciones').val();
			detalles = detalles.replace(/\n/g, "<br>");
			condiciones = condiciones.replace(/\n/g, "<br>");
			let url_params = usuario + nombre + apellido + telefono + correo + detalles + condiciones;
			window.open("generar-pdf.php" + "?" + url_params, "_blank")
        });
		
		$('#formulario-crear-presupuesto').on('submit', function(e){
			e.preventDefault();
			var validated = true;
			var initial_text = $('.btn-crear-presupuesto').text()
			$('.btn-crear-presupuesto').prop('disabled', 'true').text('Creando presupuesto...');
			console.log("Validando datos...")
			
			// Validando campo importe:
			let importe = document.querySelector("#prs-importe").value;
			if (!importe.match(/^\d+(?:[\.,]\d+)?$/)) {
				alert(	"Ha ingresado un formato para el Importe Cotizado no válido.\n"
						+ "Se acepta únicamente números (opcionalmente con decimales separados por puntos ó comas)");
				$('.btn-crear-presupuesto').prop('disabled', false).text(initial_text);
				return;
			}
			if (importe.match(/,/))
				importe = importe.replace(",", ".");
			
			// Validando archivos:
			const max_size = <?php echo $max_size ?>;
			
			if ($('input[type=file]').length > 0) {
				$('input[type=file]').each(function(index){
					if (this.files[0].size > max_size){
						alert("Uno de sus archivos sobrepasa el límite permitido."
							+"\n"+"Límite: "+max_size+" bytes"
							+"\n"+"Archivo: "+this.files[0].name+", "+this.files[0].size+" bytes")
						$('.btn-crear-presupuesto').prop('disabled', false).text(initial_text);
						validated = false;
					}
				})
			}
			
			if (!validated)
				return;
			
			// Crear presupuesto
			$.ajax({
				url : 'crear.php',
				data : {
					id: <?php echo $_SESSION['id'] ?>,
					nombre: $('#cliente-nombre').val(),
					apellido: $('#cliente-apellido').val(),
					telf: $('#cliente-telf').val(),
					correo: $('#cliente-correo').val(),
					detalles: $('#prs-detalles').val(),
					condiciones: $('#prs-condiciones').val(),
					importe: importe,
					estado: "COTIZADO",
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
					var idpres = respuesta;
					
					console.log("Presupuesto creado...")
					// Guardar observación
					$.ajax({
						url : 'guardar-observacion.php',
						data : {
							id_presupuesto: idpres,
							tipo: "OBSERVACION",
							observacion: "creación de nuevo presupuesto.",
						},
						type : 'GET',
						dataType : 'html',
						success : function() {
							console.log("Observación guardada...")
						},
						error : function(xhr, status) {
							alert('Ha ocurrido un problema tratando de guardar la observación.');
							$('.btn-crear-presupuesto').prop('disabled', false).text(initial_text);
						},
					});
					
					// Programar reenvíos
					$.ajax({
						url : 'programarreenvios.php',
						data : {id : idpres},
						type : 'GET',
						dataType : 'html',
						success : function() {
							console.log("Reenvíos programados...")
						},
						error : function(xhr, status) {
							alert('Disculpe, ocurrió un problema tratando de crear reenvíos automáticos');
						},
					});
					
					// Subir archivos
					if ($('input[type=file]').length > 0) {
						var formData = new FormData(document.querySelector("#formulario-crear-presupuesto"));
						formData.append('idpresupuesto', idpres);
						
						$('.btn-remover-archivo').hide();

						$.ajax({
							url: "subir-archivo.php",
							xhr: function () { // custom xhr (is the best)
								var xhr = new XMLHttpRequest();
								xhr.upload.addEventListener("progress", function (evt) {
									if (evt.lengthComputable) {
										var percentComplete = ((evt.loaded / evt.total) * 100);
										$('.btn-crear-presupuesto').text(`Subiendo: ${percentComplete.toFixed()}%`);
									}
								}, false);
								return xhr;
							},
							type: 'POST',
							processData: false,
							contentType: false,
							data: formData,
							beforeSend: function(){
								$('.btn-crear-presupuesto').text(`Subiendo: 0%`);
							},
							success: function(r){
								console.log("Archivos creados...")
								
								// Enviar correo al cliente
								enviarCorreo(idpres, initial_text);
							},
							error: function (xhr, ajaxOptions, thrownError) {
								alert(xhr.status + ', ' + thrownError + '\n');
								$('.btn-crear-presupuesto').prop('disabled', false).text(initial_text);
							}
						});
						
					} else enviarCorreo(idpres, initial_text);
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
					$('.btn-crear-presupuesto').prop('disabled', false).text(initial_text);
				},
			});
		});
		
		function enviarCorreo(idpres, initial_text){
			$.ajax({
				url : 'enviarcorreo.php',
				data : {
					id: idpres,
					email: $('#cliente-correo').val(),
				},
				type : 'GET',
				dataType : 'html',
				success : function() {
					console.log("Correo enviado...")
					let url = "nuevo.php?msg=" + "Presupuesto (" + idpres + ") creado con éxito!"; 
					url = "nuevo-Presupuesto (" + idpres + ") creado con éxito!"; 
					$(location).attr('href',url);
				},
				error : function(xhr, status) {
					alert('Ha ocurrido un problema tratando de enviar el correo.');
					$('.btn-crear-presupuesto').prop('disabled', false).text(initial_text);
				},
			});
		}
		
		$('.btn-agregar-archivo').on('click', function(){
			$('.btn-agregar-archivo.btn1').hide()
			$('.btn-agregar-archivo.btn2').show()
			if ($('#form-group-archivos .col-md-8.input-group').length > 0) {
				$('.btn-agregar-archivo.btn2').before(''
					+'<div class="col-md-8 col-md-offset-2 input-group">'
						+'<input class="form-control" type="file" name="archivos[]" />'
						+'<div><i class="fa fa-trash btn-remover-archivo" data-toggle="tooltip" data-placement="bottom" title="Remover"></i></div>'
					+'</div>');
			} else {
				$('.btn-agregar-archivo.btn2').before(''
					+'<div class="col-md-8 input-group">'
						+'<input class="form-control" type="file" name="archivos[]" />'
						+'<div><i class="fa fa-trash btn-remover-archivo" data-toggle="tooltip" data-placement="bottom" title="Remover"></i></div>'
					+'</div>');
			}
			$('.btn-remover-archivo').on('click', removerArchivo);
			$('[data-toggle="tooltip"]').tooltip();
		});
		
		function removerArchivo() {
			$(this).closest('.col-md-8.input-group').remove()
			if ($('#form-group-archivos .col-md-8.input-group').length == 0) {
				$('.btn-agregar-archivo.btn1').show()
				$('.btn-agregar-archivo.btn2').hide()
			}
		}
		
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
		});
    </script>

</body>
</html>
<?php } ?>