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
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Lista de Productos</h1>

                <div class="col-xs-12 col-sm-8">
                    <div class="row">
                        <hr class="visible-xs no-grid-gutter-h">
                        <!-- "Create project" button, width=auto on desktops -->
                        <div class="pull-right col-xs-12 col-sm-auto"><a href="productos.xlsx" class="btn btn-lg btn-success">VER ARCHIVO EJEMPLO</a></div>
                        <div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-lg btn-info" id="btn-archivoproductos" >ARCHIVO DE PRODUCTOS</button></div>
						<div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-lg btn-info" id="btn-nuevoproducto" >AGREGAR PRODUCTO</button></div>

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
                                <h4 class="modal-title modal-accion" id="myModalLabel">Agregar Nuevo Producto</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" id="jq-validation-form">
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Codigo</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Codigo del producto">
                                            <input type="hidden" class="form-control" id="idprod" name="idprod">
                                        </div>
										<label for="jq-validation-required" class="col-sm-1 control-label">Medida</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="medida" name="medida" placeholder="Medida">
                                        </div>
										<label for="jq-validation-required" class="col-sm-1 control-label">Espesor</label>
                                        <div class="col-sm-1">
                                            <input type="text" class="form-control" id="espesor" name="espesor" placeholder="Espesor">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Descripcion</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion del producto">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Rubros</label>
                                        <div class="col-sm-3">
                                            <select class="form-control" id="rubros" name="rubros"></select>
                                        </div>
                                        <label for="jq-validation-required" class="col-sm-2 control-label">Subrubros</label>
                                        <div class="col-sm-3">
                                            <select class="form-control" id="subrubros" name="subrubros"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
										<label for="jq-validation-required" class="col-sm-3 control-label">Packaging</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="packaging" name="packaging" placeholder="Packaging">
                                        </div>
										<label for="jq-validation-required" class="col-sm-2 control-label">Cantidad Minima</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="cantidadminima" name="cantidadminima" placeholder="0.00">
                                        </div>
                                    </div>
									<div class="form-group">
										<label for="jq-validation-required" class="col-sm-3 control-label">Precios</label>
									</div>
									<div class="form-group">
										<label for="jq-validation-required" class="col-sm-3 control-label">Precio Unitario</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="preciounitario" name="preciounitario" placeholder="0.00">
                                        </div>
                                        <label for="jq-validation-required" class="col-sm-1 control-label">+ 100</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="cantidades100" name="cantidades100" placeholder="0.00">
                                        </div>
                                        <label for="jq-validation-required" class="col-sm-1 control-label">+ 200</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="cantidades200" name="cantidades200" placeholder="0.00">
                                        </div>
									</div>
									<div class="form-group">
										<label for="jq-validation-required" class="col-sm-3 control-label">+ 500</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="cantidades500" name="cantidades500" placeholder="0.00">
                                        </div>
										<label for="jq-validation-required" class="col-sm-1 control-label">+ 1,000</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="cantidades1000" name="cantidades1000" placeholder="0.00">
                                        </div>
										<label for="jq-validation-required" class="col-sm-1 control-label">+ 5,000</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="cantidades5000" name="cantidades5000" placeholder="0.00">
                                        </div>
									</div>
									<div class="form-group">
										<label for="jq-validation-required" class="col-sm-3 control-label">+ 10,000</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="cantidades10000" name="cantidades10000" placeholder="0.00">
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
                                <h4 class="modal-title" id="myModalLabel">Eliminar Precio</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">Desea eliminar este producto?</div>
                                <form class="form-horizontal" id="jq-validation-form">
                                    <div class="form-group">
                                        <label for="jq-validation-required" class="col-sm-3 control-label">Producto</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="codigoeli" name="codigoeli">
                                            <input type="hidden" class="form-control" id="idprodeli" name="idprodeli">
										</div>
										<div class="col-sm-6">
                                            <input type="text" class="form-control" id="descripcioneli" name="descripcioneli">
										</div>
                                    </div>
                                </form>
                            </div> <!-- / .modal-body -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                                <button type="button" id="btn-eliminar-confirmado" class="btn btn-danger">SI, LO QUIERO ELIMINAR</button>
                            </div>
                        </div> <!-- / .modal-content -->
                    </div> <!-- / .modal-dialog -->
                </div>
				<div id="modal-archivo" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Subir Archivo de Productos</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    Seleccione el archivo que desea cargar
                                    <br/><br/>Obtenga un archivo ejemplo de aqui:
                                    <a href="productos.xlsx" class="btn btn-success">VER ARCHIVO EJEMPLO</a>.
                                </div>
                                <form class="form-horizontal" id="jq-validation-form">
                                    <div class="form-group" style="margin-left:0">
                                        <div class="anticipobien">Cargar Archivo:</div>
										<input type="file" name="Filedata" id="fileInput" style="display:none" />
										<button type="button" class="btn btn-primary btn-file-upload" style="margin:2px 0 4px 0">SELECCIONAR</button>
										<div id="archivocargado"></div>
                                    </div>
                                </form>
                            </div> <!-- / .modal-body -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                                <button type="button" id="btn-subir-confirmado" class="btn btn-danger">SUBIR ARCHIVO</button>
                            </div>
                        </div> <!-- / .modal-content -->
                    </div> <!-- / .modal-dialog -->
                </div>
            </div>
            <div class="row">


                <script>
                    init.push(function () {
                        $('#jq-datatables-example').dataTable();
                        $('#jq-datatables-example_wrapper .table-caption').text('Precios');
                        $('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
                    });
                </script>
                                    <!-- / Javascript -->
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Lista de Precios</span>
                    </div>
                    <div class="panel-body">
                        <div class="table-primary">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-example">
                                <thead>
                                <tr>
                                    <th>CODIGO</th>
                                    <th>RUBRO</th>
                                    <th>SUBRUBRO</th>
                                    <th>DESCRIPCION</th>
                                    <th>MEDIDA</th>
                                    <th>ESPESOR</th>
									<th>PACKAGING</th>
									<th>CANTIDAD MINIMA</th>
									<th>PRECIO UNITARIO</th>
									<th>PRECIO 100</th>
									<th>PRECIO 200</th>
									<th>PRECIO 500</th>
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
                                $query = "SELECT * FROM cot2_productos ORDER BY id ASC";
                                $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
                                while($row = $result->fetch_assoc()) {
                                    $idprecio = $row['id'];
                                    echo "<tr data-id=\"".$idprecio."\">";
                                    echo "<td>".$row['codigo']."</td>";
                                    echo "<td>".$row['rubro']."</td>";
                                    echo "<td>".$row['subrubro']."</td>";
                                    echo "<td>".$row['descripcion']."</td>";
                                    echo "<td>".$row['medida']."</td>";
                                    echo "<td>".$row['espesor']."</td>";
									echo "<td>".$row['packaging']."</td>";
									echo "<td>".$row['cantidadminima']."</td>";
									echo "<td>".$row['preciounitario']."</td>";
									echo "<td>".$row['cantidades100']."</td>";
									echo "<td>".$row['cantidades200']."</td>";
									echo "<td>".$row['cantidades500']."</td>";
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

        var permisosuser = "admin";
		
		$(document).ready(function() {
			$.ajax({
				url : '../verrubros.php',
				type : 'GET',
				dataType : 'json',
				success : function(respuesta) {
					$("#rubros").empty();
					$("<option value='0'>TODOS</option>").appendTo("#rubros");
					$.each(respuesta, function(k,v){
						$("<option value='"+k+"'>"+v+"</option>").appendTo("#rubros");
					});
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});
			$("#subrubros").empty();
			$("<option value='0'>TODOS</option>").appendTo("#subrubros");
		});
		$("#rubros").change(function() {
			var rubro = $("#rubros").val();
			$.ajax({
				url : '../versubrubros.php',
				type : 'GET',
				data : 'id='+rubro,
				dataType : 'json',
				success : function(respuesta) {
					$("#subrubros").empty();
					$("<option value='0'>TODOS</option>").appendTo("#subrubros");
					$.each(respuesta, function(k,v){
						$("<option value='"+k+"'>"+v+"</option>").appendTo("#subrubros");
					});
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});
		  
		});
		

        $(".btn-modificar").on( "click", function() {
            if(permisosuser=="admin"){
                tr = $(this).closest("tr");
                var modprod = tr.data("id");
                $.ajax({
                    url : 'verproducto.php',
                    data : { 'id' : modprod },
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var obj = jQuery.parseJSON(respuesta);
                        $("#idprod").val(obj.id);
                        $("#codigo").val(obj.codigo);
						$.ajax({
                            url : '../verrubros.php',
                            type : 'GET',
                            dataType : 'json',
                            success : function(respuesta) {
                                $("#rubros").empty();
                                $.each(respuesta, function(k,v){
                                    if(obj.rubro==k){
                                        $("<option value='"+k+"' selected>"+v+"</option>").appendTo("#rubros");
                                    }else{
                                        $("<option value='"+k+"'>"+v+"</option>").appendTo("#rubros");
                                    }
                                });
                            },
                            error : function(xhr, status) {
                                alert('Disculpe, existió un problema');
                            },
                        });
						$.ajax({
                            url : '../versubrubros.php',
                            type : 'GET',
                            dataType : 'json',
                            success : function(respuesta) {
                                $("#subrubros").empty();
                                $.each(respuesta, function(k,v){
                                    if(obj.subrubro==k){
                                        $("<option value='"+k+"' selected>"+v+"</option>").appendTo("#subrubros");
                                    }else{
                                        $("<option value='"+k+"'>"+v+"</option>").appendTo("#subrubros");
                                    }
                                });
                            },
                            error : function(xhr, status) {
                                alert('Disculpe, existió un problema');
                            },
                        });
                        $("#descripcion").val(obj.descripcion);
                        $("#medida").val(obj.medida);
                        $("#espesor").val(obj.espesor);
						$("#packaging").val(obj.packaging);
						$("#cantidadminima").val(obj.cantidadminima);
						$("#preciounitario").val(obj.preciounitario);
						$("#cantidades100").val(obj.cantidades100);
						$("#cantidades200").val(obj.cantidades200);
						$("#cantidades500").val(obj.cantidades500);
						$("#cantidades1000").val(obj.cantidades1000);
						$("#cantidades5000").val(obj.cantidades5000);
						$("#cantidades10000").val(obj.cantidades10000);
                        $(".modal-title").text("Modificar Producto");
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
        $("#btn-nuevoproducto").on( "click", function() {
            if(permisosuser=="admin"){
				$("#codigo").val("");
				$("#descripcion").val("");
				$("#medida").val("");
				$("#espesor").val("");
				$("#packaging").val("");
				$("#cantidadminima").val("");
				$("#preciounitario").val("");
				$("#cantidades100").val("");
				$("#cantidades200").val("");
				$("#cantidades500").val("");
				$("#cantidades1000").val("");
				$("#cantidades5000").val("");
				$("#cantidades10000").val("");
                $(".modal-title").text("Agregar Nuevo Producto");
                $("#myModal").modal("show");
            }else{
                $("#modal-permisos").modal("show");
            }
        });
        $("#btn-guardar").on( "click", function() {
            var tipomodal = $(".modal-accion").text();
            if (tipomodal === "Agregar Nuevo Producto"){
                var params = {
					accion : 'agregar',
                    codigo : $("#codigo").val(),
					rubro : $("#rubros").val(),
					subrubro : $("#subrubros").val(),
					descripcion : $("#descripcion").val(),
					medida : $("#medida").val(),
					espesor : $("#espesor").val(),
					packaging : $("#packaging").val(),
					cantidadminima : $("#cantidadminima").val(),
					preciounitario : $("#preciounitario").val(),
					cantidades100 : $("#cantidades100").val(),
					cantidades200 : $("#cantidades200").val(),
					cantidades500 : $("#cantidades500").val(),
					cantidades1000 : $("#cantidades1000").val(),
					cantidades5000 : $("#cantidades5000").val(),
					cantidades10000 : $("#cantidades10000").val()
                }
                $.ajax({
                    url : 'guardarproducto.php',
                    data : params,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "productos.php?msg="+respuesta; 
                        var url = "productos-"+respuesta; 
                        $(location).attr('href',url);
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                });
            }else if(tipomodal === "Modificar Producto"){
                var params = { 
                    accion : 'modificar',
                    idprod : $("#idprod").val(),
                    codigo : $("#codigo").val(),
					rubro : $("#rubros").val(),
					subrubro : $("#subrubros").val(),
					descripcion : $("#descripcion").val(),
					medida : $("#medida").val(),
					espesor : $("#espesor").val(),
					packaging : $("#packaging").val(),
					cantidadminima : $("#cantidadminima").val(),
					preciounitario : $("#preciounitario").val(),
					cantidades100 : $("#cantidades100").val(),
					cantidades200 : $("#cantidades200").val(),
					cantidades500 : $("#cantidades500").val(),
					cantidades1000 : $("#cantidades1000").val(),
					cantidades5000 : $("#cantidades5000").val(),
					cantidades10000 : $("#cantidades10000").val()
                }
                $.ajax({
                    url : 'guardarproducto.php',
                    data : params,
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var url = "productos.php?msg="+respuesta; 
                        var url = "productos-"+respuesta; 
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
                var eliminarprod = tr.data("id");
                $.ajax({
                    url : 'verproducto.php',
                    data : { 'id' : eliminarprod },
                    type : 'GET',
                    dataType : 'html',
                    success : function(respuesta) {
                        var obj = jQuery.parseJSON(respuesta);
                        $("#idprodeli").val(obj.id);
						$("#codigoeli").val(obj.codigo);
                        $("#descripcioneli").val(obj.descripcion);
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
                idprod : $("#idprodeli").val()
            }
            $.ajax({
                url : 'guardarproducto.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "productos.php?msg="+respuesta; 
                    var url = "productos-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
		$("#btn-archivoproductos").on( "click", function() {
			$("#modal-archivo").modal("show");
		});
		$("#btn-subir-confirmado").on( "click", function() {
			var rutaarch = $("#rutaarch").val();
			var button = $(this)
			var text = button.text();
			button.prop("disabled", "true").text("SUBIENDO...");
			$.ajax({
                url : 'guardarpaquete.php',
                data : { 'archivo' : rutaarch },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "productos.php?msg="+respuesta; 
                    var url = "productos-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
					button.attr("disabled", false).text( text );
                },
            });
		});
		$(".btn-file-upload").on( "click", function(e) {
			e.preventDefault();
			$("#fileInput").click();
		});
		$("#fileInput").change(function() {
			var file = this.files[0];
			var isFileExcelType = file.type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
				|| file.type.match(/(excel|xls)/) // https://stackoverflow.com/questions/974079/setting-mime-type-for-excel-document
			if (isFileExcelType) {
				$.ajax({
					url: "uploader.php",
					xhr: function () { // custom xhr (is the best)
						var xhr = new XMLHttpRequest();
						xhr.upload.addEventListener("progress", function (evt) {
							if (evt.lengthComputable) {
								var percentComplete = ((evt.loaded / evt.total) * 100);
								$('#archivocargado').html( `archivo: ${file.name} - cargando... ${percentComplete.toFixed()}%` )
							}
						}, false);
						return xhr;
					},
					type: 'POST',
					processData: false,
					contentType: false,
					data: new FormData(document.querySelector("#modal-archivo form")),
					beforeSend: function(){
						$('.btn-file-upload').prop('disabled', 'true').text("Subiendo...");
						$('#archivocargado').html( `archivo: ${file.name} - cargando... 0%` )
					},
					success: function (respuesta) {
						$('#archivocargado').html( respuesta );
						$('.btn-file-upload').attr('disabled', false).text("SELECCIONAR");
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + ', ' + thrownError + '\n');
						$('.btn-file-upload').attr('disabled', false).text("SELECCIONAR");
					}
				});
			} else {
				alert('ERROR: solo son admitidos los archivos excel (.xls, .xlsx).');
				return false;
			}
		});
    </script>
	
	<!-- Flash es obsoleto -->
	<!--<script type="text/javascript" src="<?php echo $js_url ?>/jquery.uploadify.v2.1.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $js_url ?>/swfobject.js"></script>
	<script type="text/javascript">// <![CDATA[
	$(document).ready(function() {
		divResultado = document.getElementById('archivocargado');
		$('#fileInput').uploadify({
			'uploader'  : 'uploadify.swf',
			'script'    : 'uploader.php',
			'cancelImg' : 'cancel.png',
			'auto'      : true,
			'folder'    : 'archivos',
			'fileExt'   : '*.xlsx;*.xls',
			'fileDesc'  : 'Archivos Excel 2007 (.XLSX)',
			'onComplete': function(event, queueID, fileObj, response, data) {
				//$('#archivocargado').append(response);
                divResultado.innerHTML=response; 
			}
		});
	});
	// ]]>
	</script>-->

</body>
</html>
<?php } ?>