<?php if (file_exists("../../config.php"))
        include ("../../config.php");
    else { header("location: ../../instalador"); exit(); }
?>
<?php session_start();
   if(empty($_SESSION['userPersona']) || $_SESSION['permisosPersona'] == "no" ){
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
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Lista de Utilidades</h1>

                <div class="col-xs-12 col-sm-8">
                    <div class="row">
                        <hr class="visible-xs no-grid-gutter-h">
                        <!-- "Create project" button, width=auto on desktops -->
                        <div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-lg btn-info" id="btn-nuevoprecio" >AGREGAR UTILIDAD</button></div>

                    </div>
                </div>
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
                <div id="modal-permisos" class="modal modal-alert modal-warning fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <i class="fa fa-warning"></i>
                            </div>
                            <div class="modal-title">Acceso denegado</div>
                            <div class="modal-body">Solicite permisos de un administrador para realizar esta operacion</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">SALIR</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title modal-accion" id="myModalLabel">Agregar Nueva Utilidad</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" id="jq-validation-form">
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Utilidad</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="utilidad" name="utilidad" placeholder="Utilidad">
                                            <input type="hidden" class="form-control" id="idutilidad" name="idutilidad">                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Espesor</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="espesor" name="espesor" placeholder="Espesor de la Utilidad">
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- / .modal-body -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="button" id="btn-guardar" class="btn btn-primary">Guardar</button>
                            </div>
                        </div> <!-- / .modal-content -->
                    </div> <!-- / .modal-dialog -->
                </div>
                <!-- / Modal -->
                <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Eliminar Utilidad</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">Desea eliminar esta utilidad?</div>
                                <form class="form-horizontal" id="jq-validation-form">
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Utilidad</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="utilidadeli" name="utilidadeli" placeholder="Utilidad">
                                            <input type="hidden" class="form-control" id="idutilidadeli" name="idutilidadeli">                                            
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- / .modal-body -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                                <button type="button" id="btn-eliminar-confirmado" class="btn btn-danger">SI, LA QUIERO ELIMINAR</button>
                            </div>
                        </div> <!-- / .modal-content -->
                    </div> <!-- / .modal-dialog -->
                </div>
            </div>
            <div class="row">


                <script>
                    init.push(function () {
                        $('#jq-datatables-example').dataTable();
                        $('#jq-datatables-example_wrapper .table-caption').text('Utilidades');
                        $('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
                    });
                </script>
                                    <!-- / Javascript -->
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Lista de Utilidades</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-primary">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-example">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>UTILIDAD</th>
                                    <th>ESPESOR</th>
                                    <th>MODIFICAR</th>
                                    <th>ELIMINAR</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                include ("conexion.php");
                                $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
                                /* check connection */
                                if($mysqli->connect_errno > 0){
                                    die('Unable to connect to database [' . $db->connect_error . ']');
                                }
                                $mysqli->set_charset("utf8");
                                $query = "SELECT * FROM utilidad ORDER BY id ASC";
                                $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
                                while($row = $result->fetch_assoc()) {
                                    $idutilidad = $row['id'];
                                    echo "<tr data-id=\"".$idutilidad."\">";
                                    echo "<td>".$row['id']."</td>";
                                    echo "<td>".$row['utilidad']."</td>";
                                    echo "<td>".$row['espesor']." MM</td>";
                                    echo "<td><button  class=\"btn-modificar col-xs-12 btn btn-info\">MODIFICAR</button></td>";
                                    echo "<td><button  class=\"btn-eliminar col-xs-12 btn btn-danger\">ELIMINAR</button></td>";
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

        var permisosuser = "<?= $_SESSION['permisosPersona'] ?>";

        $(".btn-modificar").on( "click", function() {
            if(permisosuser=="admin"){
                tr = $(this).closest("tr");
                var modprecio = tr.data("id");
                $.ajax({
                    url : 'verutilidad.php',
                    data : { 'id' : modprecio },
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var obj = jQuery.parseJSON(respuesta);
                        $("#idutilidad").val(obj.id);
                        $("#utilidad").val(obj.utilidad);
                        $("#espesor").val(obj.espesor);
                        $(".modal-title").text("Modificar Utilidad");
                        $("#myModal").modal("show");
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }else{
                $("#modal-permisos").modal("show");
            }
        });
        $("#btn-nuevoprecio").on( "click", function() {
            if(permisosuser=="admin"){
                $("#utilidad").val("");
                $("#espesor").val("");
                $(".modal-title").text("Agregar Nueva Utilidad");
                $("#myModal").modal("show");
            }else{
                $("#modal-permisos").modal("show");
            }
        });
        $("#btn-guardar").on( "click", function() {
            var tipomodal = $(".modal-accion").text();
            if (tipomodal === "Agregar Nueva Utilidad"){
                var tipomodal1 = 'agregar';
                var params = { 
                    accion : tipomodal1,
                    utilidad : $("#utilidad").val(),
                    espesor : $("#espesor").val()
                }
                $.ajax({
                    url : 'guardarutilidad.php',
                    data : params,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "utilidades.php?msg="+respuesta; 
                        $(location).attr('href',url);
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }else if(tipomodal === "Modificar Utilidad"){
                var tipomodal1 = 'modificar';
                var params = { 
                    accion : tipomodal1,
                    idutilidad : $("#idutilidad").val(),
                    utilidad : $("#utilidad").val(),
                    espesor : $("#espesor").val()
                }
                $.ajax({
                    url : 'guardarutilidad.php',
                    data : params,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "utilidades.php?msg="+respuesta; 
                        $(location).attr('href',url);
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }
        });
		$(".btn-eliminar").on( "click", function() {
            if(permisosuser=="admin"){
                tr = $(this).closest("tr");
                var eliminarutilidad = tr.data("id");
                $.ajax({
                    url : 'verutilidad.php',
                    data : { 'id' : eliminarutilidad },
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var obj = jQuery.parseJSON(respuesta);
                        $("#idutilidadeli").val(obj.id);
                        $("#utilidadeli").val(obj.utilidad);
                        $("#myModal2").modal("show");
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }else{
                $("#modal-permisos").modal("show");
            }
        });
        $("#btn-eliminar-confirmado").on( "click", function() {
            alert($("#idutilidadeli").val());
            var params = { 
                accion : "eliminar",
                idutilidad : $("#idutilidadeli").val(),
            }
            $.ajax({
                url : 'guardarutilidad.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "utilidades.php?msg="+respuesta; 
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