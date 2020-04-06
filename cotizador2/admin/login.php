<!DOCTYPE html>
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Cotizador Online - Pisos Goma Eva  - Eva Magic S.A.</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

	<!-- Open Sans font from Google CDN -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">

	<!-- Pixel Admin's stylesheets -->
	<link href="assets/stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="assets/stylesheets/pixel-admin.min.css" rel="stylesheet" type="text/css">
	<link href="assets/stylesheets/pages.min.css" rel="stylesheet" type="text/css">
	<link href="assets/stylesheets/rtl.min.css" rel="stylesheet" type="text/css">
	<link href="assets/stylesheets/themes.min.css" rel="stylesheet" type="text/css">

	<!--[if lt IE 9]>
		<script src="assets/javascripts/ie.min.js"></script>
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
		    bgs  = [ 'assets/images/signin-bg-1.jpg', 'assets/images/signin-bg-1.jpg', 'assets/images/signin-bg-1.jpg'];
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
		<img src="assets/images/signin-bg-1.jpg" alt="">
	</div>
	<!-- / Page background -->

	<!-- Container -->
	<div class="signin-container">

		<!-- Left side -->
		<div class="signin-info">
			<a href="index.html" class="logo">
				<img src="assets/images/logo2.gif" alt="" style="margin-top: -5px;">
			</a><!-- / Info list -->
		</div>
		<!-- / Left side -->

		<!-- Right side -->
		<div class="signin-form">

			<!-- Form -->
			<form action="index.php" id="signin-form_id" >
				<h1>Ingresar al Sistema</h1><br />
				<div class="form-group">
					<input type="text" name="signin_username" id="email" class="form-control input-lg" placeholder="Correo Electronico">
				</div> <!-- / Username -->

				<div class="form-group signin-password">
					<input type="password" name="signin_password" id="password" class="form-control input-lg" placeholder="Password">
				</div> <!-- / Password -->

				<div class="form-actions">
					<input type="button" value="Entrar" name="conectar" id="conectar" class="btn btn-info btn-block btn-lg">
				</div> <!-- / .form-actions -->
			</form>
	<!-- / Form -->

			</div>
			<!-- / Password reset form -->
		</div>
		<!-- Right side -->
	</div>
	<!-- / Container -->

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
	$(function(){
        $("#conectar").click(function(){
            var mail = $("#email").val();
            var pass = $("#password").val();
            var url = 'log.php?mail='+mail+'&pass='+pass;
            window.location = url;
        });
    });
</script>

</body>
</html>
