<?php if (file_exists("../../config.php"))
        include ("../../config.php");
    else { header("location: ../../instalador"); exit(); }
?>
<?php session_start();
    if($_SESSION['cotizador2'] != 1){
        header ("Location: $basehttp");
    }
   if(empty($_SESSION['userPersona'])){
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
    <title>Cotizador Online - <?php echo $sitename ?></title>
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
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Lista de Cotizacion</h1>
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
                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Cambiar estatus de cotizacion</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" id="jq-validation-form">
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Cotizacion de</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="">
                                            <input type="hidden" class="form-control" id="idcotizacion" name="idcotizacion">                                            
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- / .modal-body -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR</button>
                                <button type="button" id="btn-estatus-confirmado" class="btn btn-success">CONCRETADO</button>
                            </div>
                        </div> <!-- / .modal-content -->
                    </div> <!-- / .modal-dialog -->
                </div>
            </div>
            <div class="row">


                 <script>
                    init.push(function () {
                        $('#jq-datatables-example').dataTable({
                            "order": [[ 7, "desc" ]]
                        } );
                        $('#jq-datatables-example_wrapper .table-caption').text('COTIZACIONES');
                        $('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
                    });
                </script>
                                    <!-- / Javascript -->
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Lista de Cotizaciones</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-primary">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-example">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NOMBRE</th>
                                    <th>EMAIL</th>
                                    <th>TELEFONO</th>
                                    <th>TOTAL</th>
                                    <th>FECHA</th>
                                    <th>COTIZACION</th>
                                    <th>ESTADO</th>
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
                                $query = "SELECT * FROM cot2_cotizacion ORDER BY fecha DESC";
                                $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
                                while($row = $result->fetch_assoc()) {
                                    $idcotizacion = $row['id'];
                                    $email = $row['email'];
                                    echo "<tr data-id=\"".$idcotizacion."\">";
                                    echo "<td>".$row['id']."</td>";
                                    echo "<td>".$row['nombre']." ".$row['apellidos']."</td>";
                                    echo "<td><a href=mailto:".$email.">".$email."</a></td>";
                                    echo "<td>".$row['telefono']."</td>";
                                    echo "<td>$ ".number_format($row['total'], 2, ",", ".")."</td>";
                                    echo "<td>".$row['fecha']."</td>";
                                    echo "<td><a href=\"cot-$idcotizacion\" class=\"btn-detalles col-xs-12 btn btn-info\">VER COTIZACION</button></td>";
                                    //echo "<td><button  class=\"btn-detalles col-xs-12 btn btn-info\">VER COTIZACION</button></td>";
                                    if($row['estatus']=="COTIZADO"){
                                        echo "<td><button  class=\"btn-estatus col-xs-12 btn btn-info\">".$row['estatus']."</button></td>";
                                    }elseif($row['estatus']=="CONCRETADO"){
                                        echo "<td><button  class=\"btn-estatus col-xs-12 btn btn-success\">".$row['estatus']."</button></td>";
                                    }
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
        /*$(".btn-detalles").on( "click", function(e) {
			e.preventDefault();
            tr = $(this).closest("tr");
            var idcontacto = tr.data("id");
            var url = "cotizacion.php?id="+idcontacto; 
            $(location).attr('href',url);
        });*/
        $(".btn-estatus").on( "click", function() {
            tr = $(this).closest("tr");
            var cambiarestatus = tr.data("id");
            $.ajax({
                url : 'vercotizacion.php',
                data : { 'id' : cambiarestatus },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);
                    $("#idcotizacion").val(obj.id);
                    $("#nombre").val(obj.nombre + " " + obj.apellidos);
                    $("#myModal").modal("show");
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
        $("#btn-estatus-confirmado").on( "click", function() {
            var params = { 
                estatus : 'CONCRETADO',
                idcotizacion : $("#idcotizacion").val(),
            }
            $.ajax({
                url : 'guardarestatus.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "cotizaciones.php?msg="+respuesta; 
                    var url = "cotizaciones-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
    </script>

</body>
</html>
<?php } ?>