<?php if (file_exists("config.php"))
        include("config.php");
    else { header("location: instalador"); exit(); }
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Herramientas de Control y Administración - <?php echo $sitename ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

	<!-- Open Sans font from Google CDN -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">

	<!-- Pixel Admin's stylesheets -->
	<link href="<?php echo $styles_url ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $styles_url ?>/pixel-admin.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $styles_url ?>/pages.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $styles_url ?>/rtl.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $styles_url ?>/themes.min.css" rel="stylesheet" type="text/css">

	<!--[if lt IE 9]>
		<script src="<?php echo $js_url ?>/ie.min.js"></script>
	<![endif]-->

</head>


<!-- 1. $BODY ======================================================================================
	
	Body

	Classes:
	* 'theme-{THEME NAME}'
	* 'right-to-left'     - Sets text direction to right-to-left
-->
<body class="theme-frost page-signin">

<script>
	var init = [];
	init.push(function () {
		var $div = $('<div id="signin-demo" class="hidden-xs"><div>PAGE BACKGROUND</div></div>'),
		    bgs  = [ 'cotizador/admin/assets/images/signin-bg-1.jpg', 'cotizador/admin/assets/images/signin-bg-1.jpg', 'cotizador/admin/assets/images/signin-bg-1.jpg'];
		for (var i=0, l=bgs.length; i < l; i++) $div.append($('<img src="' + bgs[i] + '">'));
		$div.find('img').click(function () {
			var img = new Image();
			img.onload = function () {
				$('#page-signin-bg > img').attr('src', img.src);
				$(window).resize();
			}
			img.src = $(this).attr('src');
		});
		$('body').append($div);
	});
</script>

	<!-- Page background -->
	<div id="page-signin-bg">
		<!-- Background overlay -->
		<div class="overlay"></div>
		<!-- Replace this with your bg image -->
		<img src="<?php echo $images_url ?>/signin-bg-1.jpg" alt="">
	</div>
	<!-- / Page background -->

	<!-- Container -->
	<div class="signin-container">

		<!-- Left side -->
		<div class="signin-info">
			<a href="index.html" class="logo">
				<img src="<?php echo $images_url ?>/logo2.gif" alt="" style="margin-top: -5px;">
			</a><!-- / Info list -->
		</div>
		<!-- / Left side -->

		<!-- Right side -->
		<div class="signin-form">

			<!-- Form -->
			<form action="validaLogin.php" id="signin-form_id" method="POST">
				<h1>Ingresar al Sistema</h1><br />
				<div class="form-group">
					<input type="text" name="username" id="email" class="form-control input-lg" placeholder="Usuario">
				</div> <!-- / Username -->

				<div class="form-group signin-password">
					<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Contraseña">
				</div> <!-- / Password -->

				<div style="margin: 12px;" class="g-recaptcha" data-sitekey="<?php echo $captcha_front_code ?>"></div>

				<div class="form-actions">
					<input type="submit" value="Entrar" name="conectar" id="conectar" class="btn btn-info btn-block btn-lg">
				</div> <!-- / .form-actions -->
			</form>
	<!-- / Form -->

			</div>
			<!-- / Password reset form -->
		</div>
		<!-- Right side -->
	</div>
	<!-- / Container -->

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
<script src="<?php echo $js_url ?>/md5.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js?hl=es'></script>

<script type="text/javascript">

var f2f = "<?php echo md5(date("d")); ?>";

	// Resize BG
	window.PixelAdmin.start([
		function () {
			$("#signin-form_id").validate({ focusInvalid: true, errorPlacement: function () {} });
			
			// Validate username
			$("#email").rules("add", {
				required: true,
				minlength: 3
			});

			// Validate password
			$("#password").rules("add", {
				required: true,
				minlength: 6
			});
		}
	]);

	$(document).ready(function(){

		$('#signin-form_id').submit(function(e){
			$('#password').val(f2f + md5($('#password').val()));
			e.preventdefault();
		});

	});

</script>

</body>
</html>