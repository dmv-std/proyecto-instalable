<?php if (file_exists("../config.php"))
        include ("../config.php");
    else { header("location: ../instalador"); exit(); }
?>
<?php session_start(); 
	include ("../config.php");
	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){die('Unable to connect to database [' . $db->connect_error . ']');}
	$mysqli->set_charset("utf8");
	$query2 = "SELECT * FROM cot2_configuraciones WHERE id = '1'";
	$result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
	$row2 = $result2->fetch_assoc();
	$descuento = $row2['descuento'];
	$activardescuento = $row2['activardescuento'];
	$mensajecotizador2 = $row2['mensajecotizador'];
	$mensajecotizador = str_replace ( "+descuento+" , $descuento , $mensajecotizador2);
	$habilitar_impresion = $row2['habilitar_impresion'];
	$iva = $row2['iva'];
	mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Cotizador - <?php echo $sitename ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo $styles_url ?>/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="<?php echo $styles_url ?>/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo $styles_url ?>/theme.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?php echo $js_url ?>/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo $js_url ?>/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	</head>
	<body role="document">
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php">Cotizador - <?php echo $sitename ?></a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="admin/index.php">Administracion</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container theme-showcase" role="main">
			<div class="col-md-12">
				<h1><img src="http://d26lpennugtm8s.cloudfront.net/stores/045/277/themes/common/EM-color-chiq-29ee2c5d793a7027940d42b70f7a3586.jpg"></h1>
				<h2 class="titulo">Cotizador Online</h2>
			</div>
			<div class="col-sm-12">
				<div class="panel panel-info">
					<div class="panel-heading">
					  <h3 class="panel-title">1.- INGRESA TUS DATOS PERSONALES</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="control-group">
								<div class="col-md-2">
									<label>Nombre</label>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" placeholder="Nombre" id="nombre" required data-validation-required-message="Obligatorio">
									<?php if(empty($_SESSION['userPersona'])){?>
										<input type="hidden" class="form-control" placeholder="Usuario" value="CLIENTE" id="usuario">
									<?php }else{?>
										<input type="hidden" class="form-control" placeholder="Usuario" value="<?php echo $_SESSION['userPersona']; ?>" id="usuario">
									<?php } ?>
								</div>
								<div class="col-md-2">
									<label>Apellidos</label>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" placeholder="Apellidos" id="apellidos" required data-validation-required-message="Obligatorio">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="control-group">
								<div class="col-md-2">
									<label>Email</label>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" placeholder="Email" id="email" required data-validation-required-message="Obligatorio">
								</div>
								<div class="col-md-2">
									<label>Telefono</label>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" placeholder="Telefono" id="telefono" required data-validation-required-message="Obligatorio">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">2.- AGREGAR OBSERVACIONES (opcional)</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="control-group">
								<div class="col-md-2">
									<label>Comentarios:</label>
								</div>
								<div class="col-md-10">
									<textarea class="form-control" name="observaciones" id="observaciones" rows="5" placeholder="Agregar Observaciones"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">3.- DATOS PARA GENERAR COTIZACION</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="control-group">
								<div class="col-md-2 col-xs-12 col-sm-12">
									<label>Rubro</label>
								</div>
								<div class="col-md-4 col-xs-12 col-sm-12">
									<select class="form-control" id="rubros"></select>
									<br />
								</div>
								<div class="col-md-2 col-xs-12 col-sm-12">
									<label>Subrubro</label>
								</div>
								<div class="col-md-4 col-xs-12 col-sm-12">
									<select class="form-control" id="subrubros"></select>
									<br />
								</div>
								<br />
								<div class="col-md-2 col-xs-12 col-sm-12">
									<label>Producto</label>
								</div>
								<div class="col-md-4 col-xs-12 col-sm-12">
									<select class="form-control" id="productos"></select>
									<br />
								</div>
								<div class="col-md-2 col-xs-12 col-sm-12">
									<label>Cantidad</label>
								</div>
								<div class="col-md-4 col-xs-12 col-sm-12">
									<input type="text" class="form-control" placeholder="Ej. 1" value="1" id="cantidad">
									<br />
								</div>
								<br />
								<?php if ($habilitar_impresion): ?>
									<div class="col-md-2 col-xs-6 col-sm-12">
										<label>Impresion</label>
									</div>
									<div class="col-md-2 col-xs-6 col-sm-12">
										<label><input type="radio" value="SI" id="optradio" name="optradio">SI</label>
										<label><input type="radio" value="NO" id="optradio" name="optradio">NO</label>
										<br />
									</div>
									<div class="col-md-1 col-xs-6 col-sm-12">
										<label>Colores</label>
									</div>
									<div class="col-md-1 col-xs-6 col-sm-12">
										<select class="form-control" id="colores">
											<option value="0">0</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
										</select>
										<br />
									</div>
									<div class="col-md-4 col-md-offset-2 col-xs-12 col-sm-12">
										<input type="button" value="AGREGAR PRODUCTO" name="agregar" id="btn-agregar" class="btn btn-info btn-block btn-lg">
									</div>
								<?php else: ?>
									<div class="col-md-4 col-md-offset-8 col-xs-12 col-sm-12">
										<input type="button" value="AGREGAR PRODUCTO" name="agregar" id="btn-agregar" class="btn btn-info btn-block btn-lg">
									</div>
								<?php endif ?>
								<br />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">4.- DETALLES DE LA COTIZACION</h3>
					</div>
					<div class="panel-body">
						<table id="tablacotizacion" class="table">
							<thead>
								<tr>
									<th style="display:none;">ID</th>
									<th>CANTIDAD</th>
									<th>CODIGO</th>
									<th>DESCRIPCION</th>
<?php if ($habilitar_impresion): ?>
									<th>COLORES</th>
<?php endif ?>
									<th style="display:none;">PRECIO UNITARIO</th>
									<th style="display:none;">TOTAL</th>
								 </tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<br />
						<br />
						<div class="row">
							<div class="col-md-9">
								<?php echo $mensajecotizador; ?>
							</div>
							<div class="col-md-3 totaltotaltotal">
								
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-md-4 col-md-offset-8 col-xs-12 col-sm-12">
								<input type="button" value="Cotizar" name="cotizar" id="btn-cotizar" class="btn btn-warning btn-block btn-lg">
							</div>
						</div>
					</div>
				</div>
			</div>
			</div><!-- /.col-sm-4 -->
			
		</div> 

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php if ($load_resources_locally): ?>
    	<script src="<?php echo $js_url ?>/jquery.min.js"></script>
    <?php else: ?>
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <?php endif ?>
    <script src="<?php echo $js_url ?>/bootstrap.min.js"></script>
    <script src="<?php echo $js_url ?>/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo $js_url ?>/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
			$.ajax({
				url : 'verrubros.php',
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
			$.ajax({
				url : 'verproductos.php',
				type : 'GET',
				dataType : 'json',
				success : function(respuesta) {
					$("#productos").empty();
					$.each(respuesta, function(k,v){
						$("<option value='"+k+"'>"+v+"</option>").appendTo("#productos");
					});
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});
		});
		$("#rubros").change(function() {
			var rubro = $("#rubros").val();
			$.ajax({
				url : 'versubrubros.php',
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
			$.ajax({
				url : 'verproductos.php',
				type : 'GET',
				data : 'id='+rubro,
				dataType : 'json',
				success : function(respuesta) {
					$("#productos").empty();
					$.each(respuesta, function(k,v){
						$("<option value='"+k+"'>"+v+"</option>").appendTo("#productos");
					});
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});
		  
		});
		$("#subrubros").change(function() {
			var rubro = $("#rubros").val();
			var subrubro = $("#subrubros").val();
			$.ajax({
				url : 'verproductos.php',
				type : 'GET',
				data :  { 'id' : rubro, 'idsub' : subrubro,},
				dataType : 'json',
				success : function(respuesta) {
					$("#productos").empty();
					$.each(respuesta, function(k,v){
						$("<option value='"+k+"'>"+v+"</option>").appendTo("#productos");
					});
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});
		});
		$("#btn-agregar").on('click', function(){
            var idprod = $("#productos").val();
            var cantidad = $("#cantidad").val();
            var impresion = $("#impresion").val();
            var colores = $("#colores").val();
			$.ajax({
				url : 'vercantidadminima.php',
                data : { 'id' : idprod, 'cantidad' : cantidad,},
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
					if(parseInt(respuesta)==0){
						$.ajax({
							url : 'vertotales2.php',
							data : { 'id' : idprod, 'cantidad' : cantidad, 'colores' : colores,},
							type : 'GET',
							dataType : 'html',
							success : function(respuesta2) {
								var obj = jQuery.parseJSON(respuesta2);
								var precio = obj.producto;
								var total = obj.total;
								var codigo = obj.codigo;
								var descripcion = obj.descripcion;
<?php if ($habilitar_impresion): ?>
								$(".table tbody").append('<tr><td style="display:none;">'+idprod+'</td><td>'+cantidad+'</td><td>'+codigo+'</td><td>'+descripcion+'</td><td>'+colores+'</td><td style="display:none;">'+precio+'</td><td style="display:none;">'+total+'</td></tr>').appendTo(".table tbody");
<?php else: ?>
								$(".table tbody").append('<tr><td style="display:none;">'+idprod+'</td><td>'+cantidad+'</td><td>'+codigo+'</td><td>'+descripcion+'</td><td style="display:none;">'+precio+'</td><td style="display:none;">'+total+'</td></tr>').appendTo(".table tbody");
<?php endif ?>
							},
							error : function(xhr, status) {
								alert('Disculpe, existió un problema');
							},
						});
					}else{
						if(parseInt(cantidad)>=parseInt(respuesta)){
							$.ajax({
								url : 'vertotales2.php',
								data : { 'id' : idprod, 'cantidad' : cantidad, 'colores' : colores,},
								type : 'GET',
								dataType : 'html',
								success : function(respuesta2) {
									var obj = jQuery.parseJSON(respuesta2);
									var precio = obj.producto;
									var total = obj.total;
									var codigo = obj.codigo;
									var descripcion = obj.descripcion;  
<?php if ($habilitar_impresion): ?>
									$(".table tbody").append('<tr><td style="display:none;">'+idprod+'</td><td>'+cantidad+'</td><td>'+codigo+'</td><td>'+descripcion+'</td><td>'+colores+'</td><td style="display:none;">'+precio+'</td><td style="display:none;">'+total+'</td></tr>').appendTo(".table tbody");
<?php else: ?>
									$(".table tbody").append('<tr><td style="display:none;">'+idprod+'</td><td>'+cantidad+'</td><td>'+codigo+'</td><td>'+descripcion+'</td><td style="display:none;">'+precio+'</td><td style="display:none;">'+total+'</td></tr>').appendTo(".table tbody");
<?php endif ?>
								},
								error : function(xhr, status) {
									alert('Disculpe, existió un problema');
								},
							});
						}else{
							alert("La cantidad minima para este producto es: "+respuesta);
						}
						
					}					
				},
				error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});  
        });	
		$("#btn-cotizar").on('click', function(){
			var descuentototal = 0;
			var totaltotal = 0;
			var activardescuento = <?php echo $activardescuento; ?>;
			var descuento = <?php echo $descuento; ?>;
			var revision = "true";
			var nombre = $("#nombre").val();
			var usuario = $("#usuario").val();
			var apellidos = $("#apellidos").val();
			var email = $("#email").val();
			var telefono = $("#telefono").val();
			var observaciones = $("#observaciones").val();
			observaciones = observaciones.replace(/\r?\n/g, "<br>");
			
			var paramsx = {
				nombre : nombre,
				apellidos : apellidos, 
				email : email, 
				telefono : telefono
			}
						
			for (i in paramsx) {
				if(paramsx[i]==""){
					revision = "false";
				}            
			}
			if(revision=="true"){
				if($("#email").val().indexOf('@', 0) == -1 || $("#email").val().indexOf('.', 0) == -1) {  
					alert("La dirección E-mail es incorrecta");
					$("#email").addClass("has-error");
				}else{
					if (isNaN($("#telefono").val())){
						alert("El numero de telefono es incorrecto (Ingrese solo caracteres numericos)"); 
						$("#telefono").addClass("has-error"); 
					}else{
						var resultVal = 0;
						$("#tablacotizacion tbody tr").each(function() {
<?php if ($habilitar_impresion): ?>
							var total  = $(this).find('td:eq(6)');	
<?php else: ?>
							var total  = $(this).find('td:eq(5)');	
<?php endif ?>
							if (total.val() != null){
								resultVal += parseFloat(total.html().replace(',','.'));
							}
						});						
						
						var iva = <?php echo $iva ?>;
						if(activardescuento==1){
							descuentototal = (descuento/100)*resultVal;
							totaltotal = resultVal - descuentototal;
							let descuentototal_f = new Intl.NumberFormat("es-AR", {style: "currency", currency: "ARS"}).format(descuentototal)
							let totaltotal_f = new Intl.NumberFormat("es-AR", {style: "currency", currency: "ARS"}).format(totaltotal)
							
							let total_iva = totaltotal * (1 + iva*0.01)
							let total_iva_f = new Intl.NumberFormat("es-AR", {style: "currency", currency: "ARS"}).format(total_iva)
							
							$(".totaltotaltotal").append('<h3 class="text-center">Descuento Promocional: '+descuentototal_f+'</h3><br /><h2 class="text-center totaltot">TOTAL: '+totaltotal_f+'</h2><br /><h2 class="text-center totaltot">TOTAL + IVA: '+total_iva_f+'</h2>');
						}else{
							descuentototal = 0;
							totaltotal = resultVal;
							let totaltotal_f = new Intl.NumberFormat("es-AR", {style: "currency", currency: "ARS"}).format(totaltotal)
							
							let total_iva = totaltotal * (1 + iva*0.01)
							let total_iva_f = new Intl.NumberFormat("es-AR", {style: "currency", currency: "ARS"}).format(total_iva)
							
							$(".totaltotaltotal").append('<h2 class="text-center totaltot">TOTAL: '+totaltotal_f+'</h2><br /><h2 class="text-center totaltot">TOTAL + IVA: '+total_iva_f+'</h2>');
						}
						var params = {
							nombre : nombre, 
							usuario : usuario,
							apellidos : apellidos,
							email : email, 
							telefono : telefono, 
							descuento : descuento,
							descuentototal : descuentototal,
							total : totaltotal
						}
						$.ajax({
							url : 'guardarcotizacion.php',
							data : params,
							type : 'GET',
							dataType : 'html',
							success : function(respuesta1) {
								var paramsx = {
									idcot : respuesta1,
									observaciones : observaciones
								}
								$.ajax({
									url : 'guardarobservacion.php',
									data : paramsx,
									type : 'GET',
									dataType : 'html',
									success : function() {
										//alert(respuesta3); 
									},
									error : function(xhr, status) {
										alert('Disculpe, existió un problema');
									},
								});
								$("#tablacotizacion tbody tr").each(function() {
<?php if ($habilitar_impresion): ?>
									var idprodx = $(this).find('td:eq(0)').html(); 
									var cantidad  = $(this).find('td:eq(1)').html();
									var codigo  = $(this).find('td:eq(2)').html();
									var descripcion  = $(this).find('td:eq(3)').html();
									var colores  = $(this).find('td:eq(4)').html();
									var preciouni  = $(this).find('td:eq(5)').html();
									var total  = $(this).find('td:eq(6)').html();
									var params2 = {
										idcot : respuesta1,
										idprod : idprodx, 
										cantidad : cantidad, 
										codigo : codigo, 
										descripcion : descripcion, 
										colores : colores, 
										preciouni : preciouni, 
										total : total,
									}
<?php else: ?>
									var idprodx = $(this).find('td:eq(0)').html(); 
									var cantidad  = $(this).find('td:eq(1)').html();
									var codigo  = $(this).find('td:eq(2)').html();
									var descripcion  = $(this).find('td:eq(3)').html();
									var preciouni  = $(this).find('td:eq(4)').html();
									var total  = $(this).find('td:eq(5)').html();
									var params2 = {
										idcot : respuesta1,
										idprod : idprodx, 
										cantidad : cantidad, 
										codigo : codigo, 
										descripcion : descripcion, 
										preciouni : preciouni, 
										total : total,
									}
<?php endif ?>
									$.ajax({
										url : 'guardarcotizacion2.php',
										data : params2,
										type : 'GET',
										dataType : 'html',
										success : function(respuesta2) {
											//alert(respuesta2); 
										},
										error : function(xhr, status) {
											alert('Disculpe, existió un problema');
										},
									});
									$(this).remove();
									let preciouni_f = new Intl.NumberFormat("es-AR", {style: "currency", currency: "ARS"}).format(params2.preciouni)
									let total_f = new Intl.NumberFormat("es-AR", {style: "currency", currency: "ARS"}).format(params2.total) + " + IVA"
<?php if ($habilitar_impresion): ?>
									$(".table tbody").append('<tr><td style="display:none;">'+params2.idprod+'</td><td>'+params2.cantidad+'</td><td>'+params2.codigo+'</td><td>'+params2.descripcion+'</td><td>'+params2.colores+'</td><td>'+preciouni_f+'</td><td>'+total_f+'</td></tr>').appendTo(".table tbody");
<?php else: ?>
									$(".table tbody").append('<tr><td style="display:none;">'+params2.idprod+'</td><td>'+params2.cantidad+'</td><td>'+params2.codigo+'</td><td>'+params2.descripcion+'</td><td>'+preciouni_f+'</td><td>'+total_f+'</td></tr>').appendTo(".table tbody");
<?php endif ?>
									
								});
								var params3 = {
									idcot : respuesta1,
									email : email,
									descuentototal : descuentototal,
									total : totaltotal,
									observaciones : observaciones
								}
								$.ajax({
									url : 'guardarcotizacion3.php',
									data : params3,
									type : 'GET',
									dataType : 'html',
									success : function(respuesta3) {
										//alert(respuesta3);
									},
									error : function(xhr, status) {
										alert('Disculpe, existió un problema22');
									},
								});
								// Programar reenvíos
								$.ajax({
									url : 'programarreenvios.php',
									data : {id : respuesta1},
									type : 'GET',
									dataType : 'html',
									success : function() {
									},
									error : function(xhr, status) {
										alert('Disculpe, ocurrió un problema tratando de crear reenvíos automáticos');
									},
								});
								alert('Cotizacion realizada con exito!');
							},
							error : function(xhr, status) {
								alert('Disculpe, existió un problema11');
							},
						});	
					}               
				}   
			}else if(revision=="false"){
				alert("Ingrese todos los datos solicitados");
			}	
        });		
    </script>
  </body>
</html>