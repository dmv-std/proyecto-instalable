<?php if (file_exists("../../config.php"))
        include ("../../config.php");
    else { header("location: ../../instalador"); exit(); }
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
    <title>Sistema de Formularios - <?php echo $sitename ?></title>
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
                    <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;ADMINISTRACION</h1>
                    <div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-lg btn-info" id="btn-nuevo-user" >AGREGAR USUARIO</button></div>
                </div>
            </div> <!-- / .page-header -->
            <div id="modaleditarusuario" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title modal-title-user" id="myModalLabel">Modificar Usuario</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="jq-validation-form">
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Nombre:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del usuario">
                                        <input type="hidden" class="form-control" id="idusuario" name="idusuario">    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Usuario:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="user" name="user" placeholder="Usuario para acceso">                                          
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Password:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pass" name="pass" placeholder="Password para acceso">                                          
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Email:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Correo electronico">                                          
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Permisos:</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="permisos" id="permisos">
                                           
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="btn-guardar-user" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="modaleliminarusuario" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel2">Eliminar Usuario</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">Desea eliminar este usuario?</div>
                            <form class="form-horizontal" id="jq-validation-form">
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Usuario</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="usereli" name="usereli" placeholder="Usuario">
                                        <input type="hidden" class="form-control" id="idusuarioeli" name="idusuarioeli">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                            <button type="button" id="btn-eliminar-confirmado-user" class="btn btn-danger">SI, LO QUIERO ELIMINAR</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
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
                                    <th>#</th>
                                    <th>NOMBRE</th>
                                    <th>USUARIO</th>
                                    <th>PERMISOS</th>
                                    <th>MODIFICAR</th>
                                    <th>ELIMINAR</th>
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
                                $query = "SELECT * FROM cot2_usuarios ORDER BY id ASC";
                                $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
                                while($row = $result->fetch_assoc()) {
                                    $idusuarios = $row['id'];
                                    $permisos = $row['permisos'];
                                    if($permisos=="admin"){
                                        $perm1="ADMINISTRADOR";
                                    }elseif($permisos=="usuario"){
                                        $perm1="USUARIO";
                                    }elseif($permisos=="externo"){
                                        $perm1="EXTERNO";
                                    }
                                    echo "<tr data-id=\"".$idusuarios."\">";
                                    echo "<td>".$row['id']."</td>";
                                    echo "<td>".$row['nombre']."</td>";
                                    echo "<td>".$row['user']."</td>";
                                    echo "<td>".$perm1."</td>";
                                    echo "<td><button class=\"btn-modificar-user col-xs-12 btn btn-info\">MODIFICAR</button></td>";
                                    echo "<td><button class=\"btn-eliminar-user col-xs-12 btn btn-danger\">ELIMINAR</button></td>";
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
        

        $(".btn-modificar-user").on( "click", function() {
            tr = $(this).closest("tr");
            var moduser = tr.data("id");
            $.ajax({
                url : 'verusuario.php',
                data : { 'id' : moduser },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);
                    $("#idusuario").val(obj.id);
                    $("#nombre").val(obj.nombre);
                    $("#user").val(obj.user);
                    $("#pass").val(obj.pass);
                    $("#email").val(obj.correo);
                    $("#permisos").empty();
                    if(obj.permisos=="admin"){
                        $("<option value='admin' selected>ADMINISTRADOR</option>").appendTo("#permisos");
                        $("<option value='usuario'>USUARIO</option>").appendTo("#permisos");
                        $("<option value='externo'>EXTERNO</option>").appendTo("#permisos");
                    }else{
                        $("<option value='admin'>ADMINISTRADOR</option>").appendTo("#permisos");
                        $("<option value='usuario' selected>USUARIO</option>").appendTo("#permisos");
                        $("<option value='externo'>EXTERNO</option>").appendTo("#permisos");
                    }
                    if(obj.permisos=="externo"){
                        $("<option value='admin'>ADMINISTRADOR</option>").appendTo("#permisos");
                        $("<option value='usuario'>USUARIO</option>").appendTo("#permisos");
                        $("<option value='externo' selected>EXTERNO</option>").appendTo("#permisos");
                    }
                    $(".modal-title-user").text("Modificar Usuario");
                    $("#modaleditarusuario").modal("show");
                    $("#modalusuarios").modal("hide");
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
            
        });
        $("#btn-nuevo-user").on( "click", function() {
            $("#nombre").val("");
            $("#user").val("");
            $("#pass").val("");
            $("#email").val("");
            $("#permisos").empty();
            $("<option value='admin'>ADMINISTRADOR</option>").appendTo("#permisos");
            $("<option value='usuario'>USUARIO</option>").appendTo("#permisos");
            $("<option value='externo'>EXTERNO</option>").appendTo("#permisos");
            $(".modal-title-user").text("Agregar Nuevo Usuario");
            $("#modaleditarusuario").modal("show");
            $("#modalusuarios").modal("hide");

        });
        $("#btn-guardar-user").on( "click", function() {
            var tipomodal = $(".modal-title-user").text();
            if (tipomodal === "Agregar Nuevo Usuario"){
                var tipomodal1 = 'agregar';
                var params = { 
                    accion : tipomodal1,
                    nombre : $("#nombre").val(),
                    user : $("#user").val(),
                    pass : $("#pass").val(),
                    correo : $("#email").val(),
                    permisos : $("#permisos").val()
                }
                $.ajax({
                    url : 'guardarusuario.php',
                    data : params,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "administracion.php?msg="+respuesta; 
                        url = "administracion-"+respuesta; 
                        $(location).attr('href',url);
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }else if(tipomodal === "Modificar Usuario"){
                var tipomodal1 = 'modificar';
                var params = { 
                    accion : tipomodal1,
                    idusuario : $("#idusuario").val(),
                    nombre : $("#nombre").val(),
                    user : $("#user").val(),
                    pass : $("#pass").val(),
                    correo : $("#email").val(),
                    permisos : $("#permisos").val()
                }
                $.ajax({
                    url : 'guardarusuario.php',
                    data : params,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "administracion.php?msg="+respuesta; 
                        url = "administracion-"+respuesta; 
                        $(location).attr('href',url);
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }
        });
        $(".btn-eliminar-user").on( "click", function() {
            tr = $(this).closest("tr");
            var eliminarusuario = tr.data("id");
            $.ajax({
                url : 'verusuario.php',
                data : { 'id' : eliminarusuario },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);
                    $("#idusuarioeli").val(obj.id);
                    $("#usereli").val(obj.user);
                    $("#modaleliminarusuario").modal("show");
                    $("#modalusuarios").modal("hide");
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
        $("#btn-eliminar-confirmado-user").on( "click", function() {
            var params = { 
                accion : "eliminar",
                idusuario : $("#idusuarioeli").val(),
            }
            $.ajax({
                url : 'guardarusuario.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "administracion.php?msg="+respuesta; 
                    url = "administracion-"+respuesta; 
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
<?php }else{
    header("location: $basehttp");
} ?>