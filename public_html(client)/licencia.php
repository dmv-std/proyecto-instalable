<?php if (file_exists("config.php"))
        include("config.php");
    else { header("location: instalador"); exit(); }
?>
<?php session_start();
    if(empty($_SESSION['userPersona']) || $_SESSION['permisosPersona'] == "no" ){
        header("location: ".$basehttp);
    } elseif($_SESSION['permisosPersona']=="admin") {
		
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Licencia - <?php echo $sitename ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link href="<?php echo $css_url ?>/rrhh.css" rel="stylesheet" type="text/css"/>
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
	input#empleado{width:416px}
	@media (max-width:991px){input#empleado{width:242px}}
	@media (max-width:767px){
		input#empleado{width:100%}
		input#empleado~span{right:69px;z-index:100}
	}
    .red{color:red;}
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
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-key page-header-icon"></i>&nbsp;&nbsp;Administrar Licencia</h1>
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
        </div> <!-- / #content-wrapper -->

        <div class="page-header" style="margin-top: 0">
            <div class="row">
                <h2 class="col-xs-12 col-sm-4 text-center text-left-sm">Licencia</h2>
            </div>
            <div class="row">
                <input type="hidden" id="validator-url" value="<?php echo $license_validator_url ?>"/>
                <div class="form-group">
                    <label class="col-md-2 form-label text-right" for="license-code">Serial <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Si desea modificarlo, edite el archivo config.php"></i>:</label>
                    <div class="col-md-7 input-group">
                        <input type="text" class="form-control" id="license-code" placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXX-XXXXX" value="<?php echo $licenseKey ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 form-label text-right">Válida hasta:</label>
                    <div class="col-md-7 form-label text-left expire-date-div">
                        <em>Cargando...</em>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 form-label text-right">Estado:</label>
                    <div class="col-md-7 form-label text-left license-status-div">
                        <em>Cargando...</em>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 form-label text-right">Activa:</label>
                    <div class="col-md-7 form-label text-left license-active-div">
                        <em>Cargando...</em>
                    </div>
                </div>
                <div class="form-group">
                    <p class="col-xs-12 col-sm-10 text-center text-left-sm">Renueve o active su licencia en <a href="<?php echo $license_validator_url ?>/licencias-demo.php"><?php echo $validator_name ?></a></p>
                </div>
            </div>
        </div>

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
    <script src="<?php echo $js_url?>/bootstrap.min.js"></script>
    <script src="<?php echo $js_url?>/pixel-admin.min.js"></script>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

    <script type="text/javascript">
        init.push(function () {
            // Javascript code here
        })
        window.PixelAdmin.start(init);

        $(document).ready(function(){
            let serial = $('#license-code').val(),
                url = $('#validator-url').val();

            if(!url || !serial) {
                $('.license-active-div').html('-')
                $('.expire-date-div').html('-')
                $('.license-status-div').html('<em>Sin licencia</em>')
            } else {

                $.ajax({
                    dataType: 'jsonp',
                    data: {license: serial},
                    url: url+'/validar-licencia.php',
                    success : function(r) {
                        if (r.validated) {
                            $('.license-active-div').html('<em>'+(r.actived?'Sí':'No')+'</em>')
                            $('.expire-date-div').html('<em>'+r.expireDate+'</em>')
                            $('.license-status-div').html(r.expired?'<em class="red">Expirada</em>':'<em>Vigente</em>')
                        } else {
                            $('.license-active-div').html('<em>-</em>')
                            $('.expire-date-div').html('-')
                            $('.license-status-div').html('<em>Inválida</em>')
                        }
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, ocurrió un problema');
                    },
                })

            }
        })
		
		$('[data-toggle="tooltip"]').tooltip()
    </script>

</body>
</html>
<?php }else{
    header("location: $basehttp");
} ?>