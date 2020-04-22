<?php if (file_exists("config.php"))
        include("config.php");
    else { header("location: instalador"); exit(); }
?>
<?php session_start();
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
    <title>Sistemas - Enlaces  - <?php echo $sitename ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- Open Sans font from Google CDN -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <!-- Pixel Admin's stylesheets -->
    <link href="<?php echo $styles_url?>/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url?>/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url?>/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url?>/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $styles_url?>/themes.min.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="<?php echo $js_url?>/ie.min.js"></script>
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
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Lista de Enlaces</h1>

                <div class="col-xs-12 col-sm-8">
                    <div class="row">
                        <hr class="visible-xs no-grid-gutter-h">
                        <!-- "Create project" button, width=auto on desktops -->
                        <div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-lg btn-info" id="btn-nuevoenlace" >AGREGAR ENLACE</button></div>

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
                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title modal-accion" id="myModalLabel">Agregar Enlace</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" id="jq-validation-form">
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Titulo</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Titulo">
                                            <input type="hidden" class="form-control" id="idenlace" name="idenlace">                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Enlace</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="enlace" name="enlace" placeholder="Enlace">
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Comentario</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="comentario" name="comentario" placeholder="0">
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Orden</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="orden" name="orden" placeholder="0">
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Tipo</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="tipo" name="tipo"></select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Color</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="color" name="color"></select>
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
                                <h4 class="modal-title" id="myModalLabel">Eliminar Enlace</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">Desea eliminar esta enlace?</div>
                                <form class="form-horizontal" id="jq-validation-form">
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Enlace</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="enlaceeli" name="enlaceeli" placeholder="Enlace">
                                            <input type="hidden" class="form-control" id="idenlaceeli" name="idenlaceeli">                                            
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
                        $('#jq-datatables-example_wrapper .table-caption').text('Enlaces');
                        $('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
                    });
                </script>
                                    <!-- / Javascript -->
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Lista de Enlaces</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-primary">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-example">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>TITULO</th>
                                    <th>ENLACE</th>
									<th>COMENTARIO</th>
									<th>ORDEN</th>
									<th>COLOR</th>
									<th>TIPO</th>
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
								$userid = $_SESSION['idPersona'];
								$tipo = "PERSONAL";
								$query = "SELECT * FROM sist_enlaces WHERE tipo = '$tipo' AND usuario = '$userid' ORDER BY orden ASC";
                                $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
                                while($row = $result->fetch_assoc()) {
                                    $idenlace = $row['id'];
									if($row['color']=="info"){ $color = "AZUL";}
									if($row['color']=="success"){ $color = "VERDE";}
									if($row['color']=="warning"){ $color = "NARANJA";}
									if($row['color']=="danger"){ $color = "ROJO";}
									if($row['color']==""){ $color = "BLANCO";}
                                    echo "<tr data-id=\"".$idenlace."\">";
                                    echo "<td>".$row['id']."</td>";
                                    echo "<td>".$row['titulo']."</td>";
									echo "<td>".$row['enlace']."</td>";
									echo "<td>".$row['comentario']."</td>";
                                    echo "<td>".$row['orden']."</td>";
									echo "<td>".$color."</td>";
									echo "<td>".$row['tipo']."</td>";
                                    echo "<td><button  class=\"btn-modificar col-xs-12 btn btn-info\">MODIFICAR</button></td>";
                                    echo "<td><button  class=\"btn-eliminar col-xs-12 btn btn-danger\">ELIMINAR</button></td>";
                                    echo "</tr>";
                                }
                                // CLOSE CONNECTION
                                mysqli_close($mysqli);
								
								if($_SESSION['permisosPersona']=="admin"){
									$mysqli2 = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
									/* check connection */
									if($mysqli2->connect_errno > 0){
										die('Unable to connect to database [' . $db->connect_error . ']');
									}
									$mysqli2->set_charset("utf8");
									$tipo2 = "GENERAL";
									$query2 = "SELECT * FROM sist_enlaces WHERE tipo = '$tipo2' ORDER BY orden ASC";
									$result2 = $mysqli2->query($query2) or die($mysqli2->error.__LINE__);
									while($row2 = $result2->fetch_assoc()) {
										$idenlace = $row2['id'];
										if($row2['color']=="info"){ $color2 = "AZUL";}
										if($row2['color']=="success"){ $color2 = "VERDE";}
										if($row2['color']=="warning"){ $color2 = "NARANJA";}
										if($row2['color']=="danger"){ $color2 = "ROJO";}
										if($row2['color']==""){ $color2 = "BLANCO";}
										echo "<tr data-id=\"".$idenlace."\">";
										echo "<td>".$row2['id']."</td>";
										echo "<td>".$row2['titulo']."</td>";
										echo "<td>".$row2['enlace']."</td>";
										echo "<td>".$row2['comentario']."</td>";
										echo "<td>".$row2['orden']."</td>";
										echo "<td>".$color2."</td>";
										echo "<td>".$row2['tipo']."</td>";
										echo "<td><button  class=\"btn-modificar col-xs-12 btn btn-info\">MODIFICAR</button></td>";
										echo "<td><button  class=\"btn-eliminar col-xs-12 btn btn-danger\">ELIMINAR</button></td>";
										echo "</tr>";
									}
									// CLOSE CONNECTION
									mysqli_close($mysqli2);
									
								}
								
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
    <script src="<?php echo $js_url?>/bootstrap.min.js"></script>
    <script src="<?php echo $js_url?>/pixel-admin.min.js"></script>

    <script type="text/javascript">
        init.push(function () {
            // Javascript code here
        })
        window.PixelAdmin.start(init);

        var permisosuser = "<?= $_SESSION['permisosPersona'] ?>";
		
		$(document).ready(function() {
			
			$("#color").empty();
			$("<option value='info'>AZUL</option>").appendTo("#color");
			$("<option value='success'>VERDE</option>").appendTo("#color");
			$("<option value='warning'>NARANJA</option>").appendTo("#color");
			$("<option value='danger'>ROJO</option>").appendTo("#color");
		});

        $(".btn-modificar").on( "click", function() {
            if(permisosuser=="admin"){
                tr = $(this).closest("tr");
                var modprecio = tr.data("id");
                $.ajax({
                    url : 'verenlace.php',
                    data : { 'id' : modprecio },
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var obj = jQuery.parseJSON(respuesta);
                        $("#idenlace").val(obj.id);
                        $("#titulo").val(obj.titulo);
                        $("#enlace").val(obj.enlace);
						$("#comentario").val(obj.comentario);
						$("#color").empty();
						if(obj.color=="info"){
							$("<option value='info' selected>AZUL</option>").appendTo("#color");
						}else{
							$("<option value='info'>AZUL</option>").appendTo("#color");
						}
						if(obj.color=="success"){
							$("<option value='success' selected>VERDE</option>").appendTo("#color");
						}else{
							$("<option value='success'>VERDE</option>").appendTo("#color");
						}
						if(obj.color=="warning"){
							$("<option value='warning' selected>NARANJA</option>").appendTo("#color");
						}else{
							$("<option value='warning'>NARANJA</option>").appendTo("#color");
						}
						if(obj.color=="danger"){
							$("<option value='danger' selected>ROJO</option>").appendTo("#color");
						}else{
							$("<option value='danger'>ROJO</option>").appendTo("#color");
						}
						$("#orden").val(obj.orden);
						if(obj.tipo=="GENERAL"){
							$("<option value='GENERAL' selected>GENERAL</option>").appendTo("#tipo");
						}else{
							$("<option value='GENERAL'>GENERAL</option>").appendTo("#tipo");
						}
						if(obj.tipo=="PERSONAL"){
							$("<option value='PERSONAL' selected>PERSONAL</option>").appendTo("#tipo");
						}else{
							$("<option value='PERSONAL'>PERSONAL</option>").appendTo("#tipo");
						}
                        $(".modal-title").text("Modificar Enlace");
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
        $("#btn-nuevoenlace").on( "click", function() {
            if(permisosuser=="admin"){
				$("#color").empty();
				$("<option value='info'>AZUL</option>").appendTo("#color");
				$("<option value='success'>VERDE</option>").appendTo("#color");
				$("<option value='warning'>NARANJA</option>").appendTo("#color");
				$("<option value='danger'>ROJO</option>").appendTo("#color");
                $("#enlace").val("");
				$("#comentario").val("");
				$("#tipo").empty();
				$("<option value='GENERAL'>GENERAL</option>").appendTo("#tipo");
				$("<option value='PERSONAL'>PERSONAL</option>").appendTo("#tipo");
                $("#titulo").val("");
				$("#orden").val("");
                $(".modal-title").text("Agregar Nueva Enlace");
                $("#myModal").modal("show");
            }else{
                $("#color").empty();
				$("<option value='info'>AZUL</option>").appendTo("#color");
				$("<option value='success'>VERDE</option>").appendTo("#color");
				$("<option value='warning'>NARANJA</option>").appendTo("#color");
				$("<option value='danger'>ROJO</option>").appendTo("#color");
                $("#enlace").val("");
				$("#comentario").val("");
				$("#tipo").empty();
				$("<option value='PERSONAL'>PERSONAL</option>").appendTo("#tipo");
                $("#titulo").val("");
				$("#orden").val("");
                $(".modal-title").text("Agregar Nueva Enlace");
                $("#myModal").modal("show");
            }
        });
        $("#btn-guardar").on( "click", function() {
            var tipomodal = $(".modal-accion").text();
            if (tipomodal === "Agregar Nueva Enlace"){
                var tipomodal1 = 'agregar';
                var params = { 
                    accion : tipomodal1,
                    enlace : $("#enlace").val(),
					color : $("#color").val(),
					comentario : $("#comentario").val(),
					orden : $("#orden").val(),
					tipo : $("#tipo").val(),
                    titulo : $("#titulo").val()
                }
                $.ajax({
                    url : 'guardarenlace.php',
                    data : params,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "enlaces.php?msg="+respuesta; 
                        url = "enlaces-"+respuesta; 
                        $(location).attr('href',url);
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }else if(tipomodal === "Modificar Enlace"){
                var tipomodal1 = 'modificar';
                var params = { 
                    accion : tipomodal1,
                    idenlace : $("#idenlace").val(),
                    enlace : $("#enlace").val(),
					comentario : $("#comentario").val(),
					orden : $("#orden").val(),
					tipo : $("#tipo").val(),
					color : $("#color").val(),
                    titulo : $("#titulo").val()
                }
                $.ajax({
                    url : 'guardarenlace.php',
                    data : params,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "enlaces.php?msg="+respuesta; 
                        url = "enlaces-"+respuesta; 
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
                    url : 'verenlace.php',
                    data : { 'id' : eliminarutilidad },
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var obj = jQuery.parseJSON(respuesta);console.log(obj)
                        $("#idenlaceeli").val(obj.id);
                        $("#tituloeli").val(obj.titulo);
                        $("#enlaceeli").val(obj.enlace);
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
            var params = { 
                accion : "eliminar",
                idenlace : $("#idenlaceeli").val(),
            }
            $.ajax({
                url : 'guardarenlace.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "enlaces.php?msg="+respuesta; 
                    url = "enlaces-"+respuesta; 
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