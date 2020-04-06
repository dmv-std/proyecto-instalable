<?php if (file_exists("../../config.php"))
        include ("../../config.php");
    else { header("location: ../../instalador"); exit(); }
?>
<?php session_start();
    if($_SESSION['cotizador2'] != 1){
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
    <title>Cotizador Online - <?php echo $sitename?></title>
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
                    <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Dashboard</h1>
                    <div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-lg btn-info" id="btn-crearcotizacion" >CREAR COTIZACION</button></div>
                </div>
            </div> <!-- / .page-header -->
            <div class="col-md-12">
                <div class="col-sm-4">
                    <div class="stat-panel gh-cotizaciones">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-success valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-users bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>COTIZACIONES</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div> <!-- /.stat-cell -->
                    </div> <!-- /.stat-panel -->
                </div>
                <div class="col-sm-4">
                    <div class="stat-panel gh-productos">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-warning valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-home bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>PRODUCTOS</strong></span><br>
                            <!-- Big text -->
                            <span class="text-bg"></span><br>
                            <!-- Small text -->
                            <span class="text-sm"></span>
                        </div> <!-- /.stat-cell -->
                    </div> <!-- /.stat-panel -->
                </div>
                <div class="col-sm-4">
                    <div class="stat-panel gh-administracion">
                        <!-- Success background. vertically centered text -->
                        <div class="stat-cell bg-danger valign-middle">
                            <!-- Stat panel bg icon -->
                            <i class="fa fa-bar-chart-o bg-icon"></i>
                            <!-- Extra large text -->
                            <span class="text-xlg"><strong>ADMINISTRACION</strong></span><br>
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

    <!-- Get jQuery from Google CDN -->
    <!--[if !IE]> -->
    <?php //<script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js">'+"<"+"/script>"); </script> ?>
    <script type="text/javascript"> window.jQuery || document.write('<script src="assets/js/jquery.min.js">'+"<"+"/script>"); </script>
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
        $(".gh-cotizaciones").on( "click", function() {
            var url = "cotizaciones"; 
            $(location).attr('href',url);
        });
        $(".gh-productos").on( "click", function() {
            var url = "productos"; 
            $(location).attr('href',url);
        });
        $("#btn-crearcotizacion").on( "click", function() {
            var url = "<?php echo $basehttp ?>/cotizador2"; 
            $(location).attr('href',url);
        });
        $(".gh-administracion").on( "click", function() {
            var url = "administracion"; 
            $(location).attr('href',url);
        });
    </script>

</body>
</html>
<?php } ?>