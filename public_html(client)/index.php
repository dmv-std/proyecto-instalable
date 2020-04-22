<?php if (file_exists("config.php"))
        include("config.php");
    else { header("location: instalador"); exit(); }
?>
<?php session_start();
    if(empty($_SESSION['nombrePersona'])){
        header("location: login");
    } else {
        include("modulos.php");
        $instalados = explode("|", $instalados);
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
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
    <link href="<?php echo $styles_url ?>/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url ?>/themes.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $css_url ?>/index.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="<?php echo $js_url ?>/ie.min.js"></script>
    <![endif]-->
</head>
<body class="theme-frost no-main-menu">
    <script>var init = [];</script>
    <div id="main-wrapper">

    <?php include("header.php");?>


        <div>
            <div class="page-header">

                <div class="row">
                    <!-- Page header, center on small screens -->
                    <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Vista Principal</h1>
                    
                </div>
            </div> <!-- / .page-header -->
            <div class="col-md-12">
                <?php foreach ($modulos as $modulo): ?>
                    <?php if (in_array($modulo['nombre'], $instalados)): ?>
                        <?php if($modulo['permisos']()): ?>
                        <div class="col-sm-3">
                            <div class="stat-panel gh-<?php echo $modulo['nombre']?>">
                                <!-- Success background. vertically centered text -->
                                <div class="stat-cell bg-<?php echo $modulo['clase']?> valign-middle">
                                    <!-- Stat panel bg icon -->
                                    <i class="fa fa-<?php echo $modulo['icono']?> bg-icon"></i>
                                    <!-- Extra large text -->
                                    <span class="text-xlg"><strong><?php echo $modulo['titulo']?></strong></span><br>
                                    <!-- Big text -->
                                    <span class="text-bg"></span><br>
                                    <!-- Small text -->
                                    <span class="text-sm"></span>
                                </div> <!-- /.stat-cell -->
                            </div> <!-- /.stat-panel -->
                        </div>
                        <?php endif ?>
                    <?php endif ?>
                <?php endforeach ?>
				<?php
					$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
					if($mysqli->connect_errno > 0){
						die('Unable to connect to database [' . $mysqli->connect_error . ']');
					}
					$mysqli->set_charset("utf8");
					$tipo = "GENERAL";
					$query = "SELECT * FROM sist_enlaces WHERE tipo = '$tipo' ORDER BY orden ASC";
					$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
					while($row = $result->fetch_assoc()) {
                        if(($_SESSION['permisosPersona'] != "externo")) {
						?>
						<div class="col-sm-3">
							<a title="<?php echo $row['comentario']; ?>" href="<?php echo $row['enlace']; ?>" target="_blank"><div class="stat-panel">
								<!-- Success background. vertically centered text -->
								<div class="stat-cell bg-<?php echo $row['color']; ?> valign-middle">
									<!-- Stat panel bg icon -->
									<i class="fa fa-link bg-icon"></i>
									<!-- Extra large text -->
									<span class="text-xlg"><strong><?php echo $row['titulo']; ?></strong></span><br>
									<!-- Big text -->
									<span class="text-bg"></span><br>
									<!-- Small text -->
									<span class="text-sm"></span>
								</div> <!-- /.stat-cell -->
							</div> <!-- /.stat-panel -->
						</div>
						<?php
                        }
					}
					// CLOSE CONNECTION
					mysqli_close($mysqli);
				?>
				<?php
                    $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
					/* check connection */
					if($mysqli->connect_errno > 0){
						die('Unable to connect to database [' . $db->connect_error . ']');
					}
					$mysqli->set_charset("utf8");
					$userid = $_SESSION['idPersona'];
					$tipo = "PERSONAL";
					$query = "SELECT * FROM sist_enlaces WHERE tipo = '$tipo' AND usuario = '$userid' ORDER BY orden ASC";
					$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
					while($row = $result->fetch_assoc()) {
                        if(($_SESSION['permisosPersona'] != "externo")) {
						?>
						<div class="col-sm-3">
							<a title="<?php echo $row['comentario']; ?>" href="<?php echo $row['enlace']; ?>" target="_blank"><div class="stat-panel">
								<!-- Success background. vertically centered text -->
								<div class="stat-cell bg-<?php echo $row['color']; ?> valign-middle">
									<!-- Stat panel bg icon -->
									<i class="fa fa-link bg-icon"></i>
									<!-- Extra large text -->
									<span class="text-xlg"><strong><?php echo $row['titulo']; ?></strong></span><br>
									<!-- Big text -->
									<span class="text-bg"></span><br>
									<!-- Small text -->
									<span class="text-sm"></span>
								</div> <!-- /.stat-cell -->
							</div> <!-- /.stat-panel -->
						</div>
						<?php
                        }
					}
					// CLOSE CONNECTION
					mysqli_close($mysqli);
				?>
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
<?php foreach ($modulos as $modulo): ?>
<?php if (in_array($modulo['nombre'], $instalados)): ?>
        $(".gh-<?php echo $modulo['nombre'] ?>").on( "click", function() {
            $(location).attr('href', "<?php echo $modulo['uri'] ?>");
        });
<?php endif ?>
<?php endforeach ?>
        $(".gh-cerrar").on( "click", function() {
            var url = "cerrarSesion.php"; 
            $(location).attr('href',url);
        });
    </script>

</body>
</html>
<?php } ?>