<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Asistente de Instalación</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
	<!-- Open Sans font from Google CDN -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css"/>
	<!-- Pixel Admin's stylesheets -->
	<link href="assets/stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<!--<link href="assets/stylesheets/pixel-admin.min.css" rel="stylesheet" type="text/css"/>-->
	<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
	<link href="assets/stylesheets/widgets.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/stylesheets/rtl.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/stylesheets/themes.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/main.css" rel="stylesheet" type="text/css"/>
	<!--[if lt IE 9]>
	<script src="assets/javascripts/ie.min.js"></script>
	<![endif]-->
	<style type="text/css">
	.text-red{color:#ffbf00}
	</style>
</head>
<body class="<?php echo isset($_GET['fase']) ? "flex-body" : "" ?>">
	<?php include('config.php') ?>
	<script>var init = [];</script>

	<?php if (isset($_GET['fase'])): ?>
		
		<section class="sidebar">
		<h3>Etapas</h3>
			<ul>
				<?php foreach ($fases as $i => $fase): ?>
					<?php if($_GET['fase'] < $i+1): ?>
						<li style="">
							<i class="fa fa-circle-o"></i>
							<?php echo $fase ?>
						</li>
					<?php elseif($_GET['fase'] == $i+1): ?>
						<li style="font-weight:bold">
							<i class="fa fa-circle"></i>
							<?php echo $fase ?>
						</li>
					<?php elseif($_GET['fase'] > $i+1): ?>
						<li style="color: #a5c2fd">
							<i class="fa fa-check"></i>
							<?php echo $fase ?>
						</li>
					<?php endif ?>
				<?php endforeach ?>
			</ul>
		</section>
	<?php endif ?>

	<?php if (!isset($_GET['fase'])): ?>
	<?php /*************************
	* FASE INICIAL DE INSTALACIÓN
	********************************/ ?>
		<section class="main">
			<h1>Bienvenido al asistente de instalación</h1>
			<div class="subtext">Este asistente le guiará a través del proceso de instalación.</div>
			<div>
				<a class="btn btn-info btn-lg btn-continuar" href="?fase=2">Continuar</a>
				<div><a href="">Guía de instalación</a></div>
			</div>
		</section>

	<?php elseif (isset($_GET['fase']) && $_GET['fase']==1): ?>
	<?php /*************************
	* FASE 1: ?
	********************************/ ?>
		<section class="main2">
			<h1>Validar licencia</h1>
			<div class="subtext">Complete los campos con la información solicitada.</div>
			<form id="form-validar-licencia">
				<div class="form-group">
					<label class="col-md-2 form-label text-right" for="licence-code">Licencia:</label>
					<div class="col-md-8 input-group">
						<input type="text" class="form-control" id="licence-code" placeholder="Serial de la licencia" required>
					</div>
				</div>
			</form>
		</section>

	<?php elseif (isset($_GET['fase']) && $_GET['fase']==2): ?>
	<?php /*************************
	* FASE 1: ?
	********************************/ ?>
	<?php
		if (isset($_GET['error'])) {
			$host = isset($_GET['host']) ? $_GET['host'] : "localhost";
			$db = isset($_GET['db']) ? $_GET['db'] : "";
			$user = isset($_GET['user']) ? $_GET['user'] : "";
			$site = isset($_GET['site']) ? $_GET['site'] : "";
		} else {
			$host="localhost"; $db=""; $user=""; $site="";
		}
	?>
		<section class="main2">
			<h1>Datos básicos de configuración</h1>
			<div class="subtext">Complete los campos con la información solicitada.</div>
			<form id="form-detalles-servidor">
				<div class="form-group"><h4>Base de datos:</h4></div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="server-host">Host:</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" id="server-host" placeholder="Nombre del host para la base de datos (comúnmente: localhost)" value="<?php echo $host ?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="server-db">Base de datos:</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" id="server-db" placeholder="Nombre de la base de datos principal" value="<?php echo $db ?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right <?php echo isset($_GET['error']) ? "text-red":"" ?>" for="db-user">
						Usuario:
					</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" id="db-user" placeholder="Usuario de la base de datos" value="<?php echo $user ?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right <?php echo isset($_GET['error']) ? "text-red":"" ?>" for="db-pass">
						Contraseña:
					</label>
					<div class="col-md-7 input-group">
						<input type="password" class="form-control" id="db-pass" placeholder="Contraseña para la base de datos" required>
						<?php if(isset($_GET['error'])): ?>
							<div class="text-red">Datos incorrectos. Revise cuidadosamente los datos ingresados y reintente.</div>
						<?php endif ?>
					</div>
				</div>
				<div class="form-group"><h4>Página:</h4></div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="site-name">Nombre de la página:</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" id="site-name" placeholder="Nombre de la página" value="<?php echo $site ?>" required>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-7 col-md-offset-2 input-group">
						<button type="submit" class="btn btn-info">Continuar</button>
					</div>
				</div>
			</form>
		</section>

	<?php elseif (isset($_GET['fase']) && $_GET['fase']==3): ?>
	<?php /*************************
	* FASE 1: ?
	********************************/ ?>
		<section class="main2">
			<h1>Datos básicos de configuración</h1>
			<div class="subtext">Complete los campos con la información solicitada.</div>
			<form id="form-detalles-admin">
				<div class="form-group"><h4>Cuenta de administrador:</h4></div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="admin-firstname">Nombre:</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" id="admin-firstname" placeholder="Nombre del usuario" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="admin-lastname">Apellido:</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" id="admin-lastname" placeholder="Apellido del usuario" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="admin-email">Correo:</label>
					<div class="col-md-7 input-group">
						<input type="email" class="form-control" id="admin-email" placeholder="Correo del usuario" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="admin-user">Usuario:</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" id="admin-user" placeholder="Usuario administrador" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="admin-pass">Contraseña:</label>
					<div class="col-md-7 input-group">
						<input type="password" class="form-control" id="admin-pass" placeholder="Ingresar contraseña" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="admin-pass-confirm">Confirmar Contraseña:</label>
					<div class="col-md-7 input-group">
						<input type="password" class="form-control" id="admin-pass-confirm" placeholder="Ingresar contraseña nuevamente" required>
						<br/><span class="error-msg text-red"></span>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-7 col-md-offset-3 input-group">
						<button type="submit" class="btn btn-info">Continuar</button>
					</div>
				</div>
			</form>
		</section>

	<?php elseif (isset($_GET['fase']) && $_GET['fase']==4): ?>
	<?php /*************************
	* FASE 1: ?
	********************************/ ?>
		<section class="main2">
			<h1>Habilitar reCAPTCHA</h1>
			<div class="subtext">
				Necesitas habilitar una cuenta de reCAPTCHA para poder tener una página de login funcional.<br/>
				Ingresa a <a href="https://www.google.com/recaptcha/admin/create">https://www.google.com/recaptcha/admin/create</a>,
				y completa los datos para poder recibir las claves necesarias, luego valídalas.<br/><br/>
				<strong>NOTA:</strong> es necesario elegir la opción <code>reCAPTCHA v2 > Casilla No soy un robot</code>.
			</div>
			<form id="form-detalles-recaptcha">
				<div class="form-group"><h4>Datos de la cuenta de reCAPTCHA:</h4></div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right" for="front-code">Clave de sitio web:</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" name="front-code" id="front-code" placeholder="Clave para el FrontEnd" value="<?php echo isset($_GET['code1']) ? $_GET['code1'] : '' ?>" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 form-label text-right <?php echo isset($_GET['error']) ? "text-red":"" ?>" for="back-code">
						Clave secreta:
					</label>
					<div class="col-md-7 input-group">
						<input type="text" class="form-control" name="back-code" id="back-code" placeholder="Clave para el BackEnd" value="<?php echo isset($_GET['code2']) ? $_GET['code2'] : '' ?>" required>
						<?php if(isset($_GET['error'])): ?>
							<div class="text-red">Parece que la clave secreta es incorrecta. Revise cuidadosamente los datos y vuelva a intentar.</div>
						<?php endif ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-7 col-md-offset-3 input-group">
						<button type="submit" class="btn btn-info btn-validar-claves">Validar claves</button>
					</div>
				</div>
				<div class="form-group recaptcha-group">
					<label class="col-md-3 form-label text-right">Validar reCAPTCHA:</label>
					<div class="col-md-7 col-md-offset-3 input-group recaptcha-input-group">
						<div class="g-recaptcha" data-sitekey="<?php echo isset($_GET['code1']) ? $_GET['code1'] : 'empty' ?>"></div>
					</div>
					<div class="col-md-7 col-md-offset-3 input-group">
						<button type="submit" class="btn btn-info btn-validar-captcha">Validar reCAPTCHA y continuar</button>
					</div>
				</div>
			</form>
		</section>

		<script src='https://www.google.com/recaptcha/api.js?hl=es'></script>

	<?php elseif (isset($_GET['fase']) && $_GET['fase']==5): ?>
	<?php /*************************
	* FASE 1: ?
	********************************/ ?>
		<section class="main2">
			<h1>Personalizar instalación de módulos</h1>
			<div class="subtext">Seleccione los módulos que desea instalar.</div>
			<form id="form-detalles-modulos">
				<?php foreach ($modulos as $modulo): ?>
					<div class="form-group">
						<label class="container col-md-offset-1"><?php echo $modulo['nombre'] ?>
							<input type="checkbox" name="<?php echo $modulo['etiqueta'] ?>" checked/>
							<span class="checkmark"></span>
						</label>
					</div>
				<?php endforeach ?>
				<div class="form-group">
					<div class="col-md-7 col-md-offset-1 input-group">
						<button type="submit" class="btn btn-info">Continuar</button>
					</div>
				</div>
			</form>
		</section>

	<?php elseif (isset($_GET['fase']) && $_GET['fase']==6): ?>
	<?php /*************************
	* FASE 1: ?
	********************************/ ?>
		<section class="main2">
			<h1>Realizando instalación</h1>
			<div class="subtext">En breve la instalación habrá terminado.</div>
			<div class="progress" style="width:60%">
				<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
					<span class="sr-only">45% Complete</span>
				</div>
			</div>
			<div class="subtext estado-de-instalacion"><em>Iniciando instalación...</em></div>
			<div class="install-error text-red"></div>
		</section>

	<?php elseif (isset($_GET['fase']) && $_GET['fase']==7): ?>
	<?php /*************************
	* FASE 1: ?
	********************************/ ?>
		<section class="main2">
			<h1><i class="fa fa-check"></i> Proceso finalizado</h1>
			<div class="subtext">
				La instalación ha sido realizada con éxito.<br/>
				<br/>
				Puedes acceder al sitio a través del siguiente <a href="../login">enlace</a>.
			</div>
		</section>

	<?php else: ?>
		<section class="main2"></section>
	<?php endif ?>

	<!-- Get jQuery from Google CDN -->
	<!--[if !IE]> -->
	<script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js">'+"<"+"/script>"); </script>
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

		$('#form-validar-licencia').on('submit', function(e){
			e.preventDefault()
		})

		$('#form-detalles-servidor').on('submit', function(e){
			e.preventDefault()
			let host = $('#server-host').val()
			let db = $('#server-db').val()
			let user = $('#db-user').val()
			let pass = $('#db-pass').val()
			let sitename = $('#site-name').val()
			$.ajax({
				url: "crear-configuracion-servidor.php",
				data: {
					host: host,
					db: db,
					user: user,
					pass: pass,
					sitename: sitename,
				},
				type: 'POST',
				dataType: 'html',
				success: function(r) {console.log(r)
					if (r.startsWith("SUCCESS"))
						$(location).attr('href', "?fase=3")
					else
						$(location).attr('href', `?fase=2&error=1&host=${host}&db=${db}&user=${user}&site=${sitename}`)
				},
				error: function(xhr, status) {
					alert('Un error inesperado ha ocurrido.');
					console.log({xhr:xhr, status:status})
				},
			})
		})

		$('#form-detalles-admin').on('submit', function(e){
			e.preventDefault()
			if($("#admin-pass").val() != $("#admin-pass-confirm").val()) {
				$('label[for=admin-pass], label[for=admin-pass-confirm]').addClass('text-red')
				$('span.error-msg').text('Las contraseñas no coinciden.')
			} else if ($("#admin-pass").val().length<8){
				$('label[for=admin-pass], label[for=admin-pass-confirm]').addClass('text-red')
				$('span.error-msg').text('Elija una contraseña de mínimo 8 caracteres.')
			} else {
				$('label[for=admin-pass], label[for=admin-pass-confirm]').removeClass('text-red')
				$('span.error-msg').text('')
				$.ajax({
					url: "registrar-admin.php",
					data: {
						nombre: $("#admin-firstname").val(),
						apellido: $("#admin-lastname").val(),
						correo: $("#admin-email").val(),
						user: $("#admin-user").val(),
						pass: $("#admin-pass").val(),
					},
					type: 'POST',
					dataType: 'html',
					success: function() {
						$(location).attr('href', "?fase=4")
					},
					error: function(xhr, status) {
						alert('Un error inesperado ha ocurrido.');
						console.log({xhr:xhr, status:status})
					},
				})
			}
		})
		$('.btn-validar-claves').on('click', function() {
			let code1 = $('#front-code').val()
			let code2 = $('#back-code').val()
			$(location).attr('href', "?fase=4&code1="+code1+"&code2="+code2)
		})
		$('.btn-validar-captcha').on('click', function() {
			let code1 = $('#front-code').val()
			let code2 = $('#back-code').val()
			$('.btn-validar-captcha').attr('disabled', true).text('Validando captcha...')
			$.ajax({
				url: "validar-captcha.php",
				type: 'POST',
				processData: false,
				contentType: false,
				data: new FormData(document.querySelector("#form-detalles-recaptcha")),
				type : 'POST',
				success: function (respuesta) {
					if (respuesta == "SUCCESS") {
						$(location).attr('href', "?fase=5")
					} else {
						$(location).attr('href', "?fase=4&code1="+code1+"&code2="+code2+"&error=1")
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status + ', ' + thrownError + '\n')
				}
			});
		})
		$('#form-detalles-recaptcha').on('submit', function(e){
			e.preventDefault()
		})
		$('#form-detalles-modulos').on('submit', function(e){
			e.preventDefault()
			let modulos = []
			$('input').each(function(){
				if ($(this).prop('checked'))
					modulos.push($(this).prop('name'))
			})
			$.ajax({
				url: "validar-modulos.php",
				data: {
					modulos: modulos.join("|"),
				},
				type: 'POST',
				dataType: 'html',
				success: function() {
					$(location).attr('href', "?fase=6")
				},
				error: function(xhr, status) {
					alert('Un error inesperado ha ocurrido.');
					console.log({xhr:xhr, status:status})
				},
			})
		})

<?php if (isset($_GET['fase']) && $_GET['fase']==6): ?>
		$(document).ready(function(){
			performInstallAction()
		})

		function performInstallAction(step=1) {
			$.ajax({
				url: "instalacion.php",
				data: {
					step: step,
				},
				type: 'POST',
				dataType: 'html',
				success: function(r) {
					console.log(r)
					r = JSON.parse(r)
					let progress = Math.round(step / r.total * 100);
					console.log(`${step}/${r.total} ${progress}% - ${r.estado}`)
					if (!r.error && progress < 100 && !isNaN(r.total)) {
						performInstallAction(step+1)
					} else if (!r.error && progress >= 100) {
						setTimeout(function(){
							$('.progress-bar').css('width', '100%')
							$('.estado-de-instalacion').html(`<h1>100%</h1><em>Instalación completa!</em>`)
							setTimeout(function(){
								$(location).attr('href', "?fase=7")
							}, 1500)
						}, 1500)
					}
					$('.progress-bar').css('width', progress+'%')
					$('.estado-de-instalacion').html(`<h1>${progress}%</h1><em>${r.estado}</em>`)
					
					if ( r.error )
						$('.install-error').html( r.error )
				},
				error: function(xhr, status) {
					alert('Un error inesperado ha ocurrido.');
					console.log({xhr:xhr, status:status})
				},
			})
		}
<?php endif ?>

	</script>
</body>
</html>