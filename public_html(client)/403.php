<?php if (file_exists("config.php"))
        include("config.php");
    else header("location: instalador");
?>
<?php session_start() ?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>403 - <?php echo $sitename ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- Open Sans font from Google CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <!-- Pixel Admin's stylesheets -->
    <link href="<?php echo $styles_url ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/themes.min.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="assets/javascripts/ie.min.js"></script>
    <![endif]-->
    <style type="text/css">code{color: #333;background-color: #ddd;}</style>
</head>
<body class="theme-frost no-main-menu">
    <div id="main-wrapper">

        <?php if(isset($_SESSION['nombrePersona'])): ?>
            <?php include("header.php");?>
        <?php else: ?>
            <nav class="navbar navbar-default" role="navigation">
              <!-- El logotipo y el icono que despliega el menú se agrupan
                   para mostrarlos mejor en los dispositivos móviles -->
              <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo $basehttp ?>">
                  <?php echo $sitename ?>
                </a>
              </div>
            </nav>
        <?php endif ?>


        <div id="content-wrapper">
            <div class="page-header">

                <div class="row">
                    <!-- Page header, center on small screens -->
                    <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-exclamation-triangle page-header-icon"></i>&nbsp;&nbsp;Error 403</h1>
                </div>
				
				<p style="margin-top: 5px;">
					No tienes permisos para acceder a la dirección <code><?php echo $_SERVER['REQUEST_URI'] ?></code>.<br>(acceso restringido)
				</p>
            </div> <!-- / .page-header -->
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

</body>
</html>