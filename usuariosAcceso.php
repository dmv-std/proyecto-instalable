<?php if (file_exists("config.php"))
        include("config.php");
    else { header("location: instalador"); exit(); }
?>
<?php session_start();
    if(empty($_SESSION['userPersona']) || $_SESSION['permisosPersona'] == "no" ){
        header("location: $basehttp");
    } elseif($_SESSION['permisosPersona']=="admin") {
		
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Sistemas - Usuarios  - <?php echo $sitename ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- Open Sans font from Google CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <!-- Pixel Admin's stylesheets -->
    <link href="cotizador/admin/assets/stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="cotizador/admin/assets/stylesheets/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="cotizador/admin/assets/stylesheets/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="cotizador/admin/assets/stylesheets/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="cotizador/admin/assets/stylesheets/themes.min.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="cotizador/admin/assets/javascripts/ie.min.js"></script>
    <![endif]-->

</head>
<body class="theme-frost no-main-menu">
<script>var init = [];</script>
<div id="main-wrapper">

    <?php include("header.php");?>


    <div id="content-wrapper">
        <div class="page-header">
            <div class="row">
                <h1 class="col-xs-12 col-sm-12 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Registro de Acceso de Usuarios</h1>				
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
            <div class="col-md-12">
                <script>
					init.push(function () {
						$('#jq-datatables-example').dataTable();
						$('#jq-datatables-example_wrapper .table-caption').text('Usuarios');
						$('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
					});
				</script>
				<div class="panel">
					<div class="panel-body">
						<div class="table-primary">
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-example">
								<thead>
								<tr>
									<th class="text-center">USER</th>
									<th class="text-center">FECHA</th>
									<th class="text-center">ACCESO</th>
									<th class="text-center">IP</th>
                                    <th class="text-center">HOST</th>
                                    <th class="text-center">CIUIDAD</th>
                                    <th class="text-center">REGION</th>
                                    <th class="text-center">PAIS</th>
                                    <th class="text-center">GEOMAP</th>
                                    <th class="text-center">ORIGEN</th>                                    
								</tr>
								</thead>
								<tbody>
								<?php
								    $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
								    /* check connection */
								    if($mysqli->connect_errno > 0){
									   die('Unable to connect to database [' . $db->connect_error . ']');
								    }
								    $mysqli->set_charset("utf8");
								    $query = "SELECT * FROM sist_usersloginacceso ORDER BY fecha DESC";
								    $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
								    while($row = $result->fetch_assoc()) {
									   $idregistro = $row['id_usersLoginAcceso'];
									   $acceso = $row['acceso'];
									   
                                       if($acceso=="1"){
										  $accesoStatus="Login OK";
									   }else{
										  $accesoStatus="Login FAIL";
									   }

                                       $latLong = explode("," , $row['loc']);

                                       if(isset($latLong[0]) && isset($latLong[1])){
                                            $latLongMapa = "http://www.latlong.net/c/?lat=".$latLong[0]."&long=".$latLong[1]; 
                                       }else{
                                            $latLongMapa = "javascript:void(0);";
                                       }                                       

    									echo "<tr data-id=\"".$idregistro."\">";
    									echo "<td>".$row['username']."</td>";
    									echo "<td>".$row['fecha']."</td>";
                                        echo "<td>".$accesoStatus."</td>";
                                        echo "<td>".$row['ip']."</td>";
                                        echo "<td>".$row['hostname']."</td>";
                                        echo "<td>".$row['city']."</td>";
                                        echo "<td>".$row['region']."</td>";
                                        echo "<td>".$row['country']."</td>";
                                        echo "<td><a href='".$latLongMapa."' target='_blank'>".$row['loc']."</a></td>";
                                        echo "<td>".$row['org']."</td>";
    									echo "</tr>";
								    }
								// CLOSE CONNECTION
								mysqli_close($mysqli);
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
            </div>
        </div> <!-- / #content-wrapper -->
        <div id="main-menu-bg"></div>
    </div> <!-- / #main-wrapper -->

    <!-- Get jQuery from Google CDN -->
    <!--[if !IE]> -->
    <script type="text/javascript" src="cotizador/admin/assets/js/jquery.js"></script>
    <!-- <![endif]-->
    <!--[if lte IE 9]>
    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">'+"<"+"/script>"); </script>
    <![endif]-->

    <!-- Pixel Admin's javascripts -->
    <script src="cotizador/admin/assets/javascripts/bootstrap.min.js"></script>
    <script src="cotizador/admin/assets/javascripts/pixel-admin.min.js"></script>

    <script type="text/javascript">

        init.push(function () {
            // Javascript code here
        })
        window.PixelAdmin.start(init);
        
    </script>

</body>
</html>
<?php }else{
    header("location: $basehttp");
} ?>