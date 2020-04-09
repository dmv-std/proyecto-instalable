<?php if (file_exists("../config.php"))
        include ("../config.php");
    else { header("location: ../instalador"); exit(); }
?>
<?php session_start();
    if($_SESSION['rrhh'] != 1){
        header ("Location: $basehttp");
    }
    if(empty($_SESSION['nombrePersona']) || $_SESSION['permisosPersona'] == "no" ){
        header("location: $basehttp");
    } else {
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
    <link href="<?php echo $styles_url ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/themes.min.css" rel="stylesheet" type="text/css">
	<script src="https://kit.fontawesome.com/b8c47e2cca.js" crossorigin="anonymous"></script><!-- Font Awesome kit latest -->
    <!--[if lt IE 9]>
    <script src="<?php echo $js_url ?>/ie.min.js"></script>
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
                    <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Dashboard</h1>
                    <div class="pull-right col-xs-12 col-sm-auto">
						<button class="btn btn-lg btn-info btn-crearpresupuesto"><i class="fas fa-plus-circle"></i> CREAR PRESUPUESTO</button>
					</div>
                </div>
            </div> <!-- / .page-header -->
            <div class="col-md-12">
                <div class="col-sm-4">
                    <div class="stat-panel btn-crearpresupuesto">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-info valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fas fa-file-invoice-dollar bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>CREAR PRESUPUESTO</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div> <!-- /.stat-cell -->
                    </div> <!-- /.stat-panel -->
                </div>
                <div class="col-sm-4">
                    <div class="stat-panel gh-ver-presupuestos">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-default valign-middle">
                            <!-- Stat panel bg icon -->
							<i class="fas fa-clipboard-list bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>VER PRESUPUESTOS</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div> <!-- /.stat-cell -->
                    </div> <!-- /.stat-panel -->
                </div>
                <div class="col-sm-4">
                    <div class="stat-panel gh-configuracion">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-success valign-middle">
                            <!-- Stat panel bg icon -->
							<i class="fas fa-cogs bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>CONFIGURACIÓN</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div> <!-- /.stat-cell -->
                    </div> <!-- /.stat-panel -->
                </div>
                <hr class="no-grid-gutter-h grid-gutter-margin-b no-margin-t">
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
        $(".btn-crearpresupuesto").on( "click", function() {
            $(location).attr('href', "nuevo");
        });
        $(".gh-ver-presupuestos").on( "click", function() {
            $(location).attr('href', "listado");
        });
        $(".gh-configuracion").on( "click", function() {
            $(location).attr('href', "configuracion");
        });
    </script>

</body>
</html>
<?php } ?>