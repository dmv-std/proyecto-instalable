<?php if (file_exists("config.php"))
        include("config.php");
    else { header("location: instalador"); exit(); }
?>
<?php session_start();
    if(empty($_SESSION['userPersona']) || $_SESSION['permisosPersona'] == "no" ){
        header("location: ".$basehttp);
    } elseif($_SESSION['permisosPersona']=="admin") {
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname); // conectandose a la base de datos 'fichada'
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		
		// Obtener lista de empleados
		$query = "SELECT * FROM fich_empleados";
		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		$empleados=array();
		while($row = $result->fetch_assoc()) {
			$row1 = $row;
			$empleados[$row1['id']] = $row1;
		}
		
		// Cerrando la conexión con la base de datos
		mysqli_close($mysqli);
		
        $instalados = explode("|", $modules);
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
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Usuarios</h1>
				<div class="col-xs-12 col-sm-8">
                    <div class="row">
                        <hr class="visible-xs no-grid-gutter-h">
                        <!-- "Create project" button, width=auto on desktops -->
                        <div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-lg btn-info" id="btn-nuevo-user" >AGREGAR USUARIO</button></div>

                    </div>
                </div>
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
                                    <input required type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del usuario">
                                    <input type="hidden" class="form-control" id="idusuario" name="idusuario">    
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Apellido:</label>
                                <div class="col-sm-7">
                                    <input required type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellidos del usuario">    
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Usuario:</label>
                                <div class="col-sm-7">
                                    <input required type="text" class="form-control" id="user" name="user" placeholder="Usuario para acceso">                                          
                                </div>
                            </div>
							<div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Id Empleado:</label>
                                <div class="col-sm-7 input-group" style="padding:0 11px">
									<input type="text" class="form-control" id="empleado" name="empleado" placeholder="Ninguno" disabled />
									<input type="hidden" id="id-empleado" name="id-empleado" />
									<span class="input-group-btn">
										<button class="btn btn-default btn-cambiar-id-empleado" type="button">Cambiar</button>
									</span>
                                </div>
							</div><!-- /input-group -->
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Correo:</label>
                                <div class="col-sm-7">
                                    <input required type="email" class="form-control" id="correo" name="correo" placeholder="correo del usuario">                                          
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Password:</label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="pass" name="pass">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Confirmar password:</label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="passconfirm" name="passconfirm">
                                </div>
                            </div>
                            <div class="form-group campo-de-firmas">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Firma:</label>
                                <div class="col-sm-7" style="position:relative">
									<button class="btn btn-primary btn-file-upload" style="margin-right:15px">Subir</button>
									<span class="sin-firma"><em>Sin Firma</em></span>
									<span class="firma">
										<img style="height:31px" src="" />
										<a class="btn-remover-firma" style="font-size:1.5rem;color:#ae6767" href="#" data-toggle="tooltip" data-placement="bottom" title="Remover Firma"><i class="fa fa-trash-alt"></i></a>
									</span>
                                    <input type="file" style="display:none" id="firma" name="firma">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Permisos:</label>
                                <div class="col-sm-7">
                                    <select required class="form-control" name="permisos" id="permisos">
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="button" id="btn-guardar-user" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
		<div id="modal-empleados" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title" id="myModalLabel">Lista de Empleados</h4>
					</div>
					<div class="modal-body">
						<div class="row search">
							<div class="col-lg-11">
								<div class="input-group">
									<input type="text" class="form-control" id="filtro-empleados" placeholder="Buscar...">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
									</span>
								</div><!-- /input-group -->
							</div><!-- /.col-lg-6 -->
						</div>
						<div class="row" style="margin-top: 50px;">
							<?php foreach ($empleados as $empleado): ?>
								<div class="col-md-4 employee-card">
									<a href="#" data-id="<?php echo $empleado['id'] ?>">
										<div>
											<div class="name"><?php echo $empleado['nombre'] ?></div>
											<div class="email"><?php echo $empleado['correo'] ?></div>
											<div><?php echo $empleado['n_tarjeta'] ? $empleado['n_tarjeta'] : 'Sin tarjeta' ?></div>
											<div><?php echo $departamentos[$empleado['id_departamento']]['nombre'] ?></div>
											<div><?php echo $cargos[$empleado['id_cargo']]['nombre'] ?></div>
										</div>
										<i class="fa fa-address-card"></i>
									</a>
								</div>
							<?php endforeach ?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
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
        <div id="modaleditarsistemasusuario" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Modificar Accesos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">La edicion de los accesos a los diferentes subsistema previene el uso de los sistemas bloqueados en los usuarios, las acciones dentro de los mismos dependen directamente del rol asigando al usuario.
                        </div>
                        <div class="table-primary">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tableeditarsistemasusuario">
                                <thead>
                                <tr>
                                    <th class="text-center">SISTEMA</th>
                                    <th class="text-center">ESTADO</th>                                    
                                </tr>
                                </thead>
                                <tbody>
                                    <?php if (in_array("calendario", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">Calendario</td>
                                        <td class="text-center"><button data-sistema="calendario" class="col-xs-12 btn btn-default clicktoogleonoffsistema calendario">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (true): ?>
                                    <tr>
                                        <td class="text-center">Chat</td>
                                        <td class="text-center"><button data-sistema="chat" class="col-xs-12 btn btn-default clicktoogleonoffsistema chat">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("cotizador", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">Cotizador</td>
                                        <td class="text-center"><button data-sistema="cotizador" class="col-xs-12 btn btn-default clicktoogleonoffsistema cotizador">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("crm", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">CRM</td>
                                        <td class="text-center"><button data-sistema="crm" class="col-xs-12 btn btn-default clicktoogleonoffsistema crm">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("formularios", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">Formularios</td>
                                        <td class="text-center"><button data-sistema="formularios" class="col-xs-12 btn btn-default clicktoogleonoffsistema formularios">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("listas", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">Listas</td>
                                        <td class="text-center"><button data-sistema="listas" class="col-xs-12 btn btn-default clicktoogleonoffsistema listas">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("out", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">OUT</td>
                                        <td class="text-center"><button data-sistema="outs" class="col-xs-12 btn btn-default clicktoogleonoffsistema outs">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("pagos", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">Pagos</td>
                                        <td class="text-center"><button data-sistema="pagos" class="col-xs-12 btn btn-default clicktoogleonoffsistema pagos">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("produccion", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">Produccion</td>
                                        <td class="text-center"><button data-sistema="produccion" class="col-xs-12 btn btn-default clicktoogleonoffsistema produccion">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("respuestas", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">Respuestas</td>
                                        <td class="text-center"><button data-sistema="respuestas" class="col-xs-12 btn btn-default clicktoogleonoffsistema respuestas">SIN ACCION</button></td>
                                    </tr>                                
                                    <?php endif ?>
                                    <?php if (in_array("stock", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">Stock</td>
                                        <td class="text-center"><button data-sistema="stock" class="col-xs-12 btn btn-default clicktoogleonoffsistema stock">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                    <?php if (in_array("rrhh", $instalados)): ?>
                                    <tr>
                                        <td class="text-center">RR. HH.</td>
                                        <td class="text-center"><button data-sistema="rrhh" class="col-xs-12 btn btn-default clicktoogleonoffsistema rrhh">SIN ACCION</button></td>
                                    </tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                    </div>
                </div>
            </div>
        </div>
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
									<th>#</th>
									<th class="text-center">NOMBRE</th>
									<th class="text-center">USUARIO</th>
									<th class="text-center">ROLES</th>
									<th class="text-center">ACCIONES</th>                                    
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
								$query = "SELECT * FROM sist_usuarios ORDER BY id ASC";
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
									echo "<td><button style='border-radius: 0px !important;width: 33%;' class=\"btn-modificar-user col-xs-12 btn btn-info\">MODIFICAR</button><button style='width: 33%;border-radius: 0px !important;' class=\"btn-sistemas-user col-xs-12 btn btn-warning\">SISTEMAS</button><button style='width: 33%;border-radius: 0px !important;' class=\"btn-eliminar-user col-xs-12 btn btn-danger\">ELIMINAR</button></td>";
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
    <script src="<?php echo $js_url?>/bootstrap.min.js"></script>
    <script src="<?php echo $js_url?>/pixel-admin.min.js"></script>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

    <script type="text/javascript">

    var idUserSistemasModificaAccesos = "";

        var validator = $( "#jq-validation-form" ).validate({
          rules: {
            nombre: {
              required: true
            },
            apellido: {
              required: true
            },
            correo: {
              required: true,
              email: true
            },
            user: {
              required: true
            },
            permisos: {
              required: true
            }
          }
        });

        init.push(function () {
            // Javascript code here
        })
        window.PixelAdmin.start(init);

        $('.clicktoogleonoffsistema').on("click" , function(){

            var sistema = $(this).data('sistema');
            var idUsuario = idUserSistemasModificaAccesos;

            $.ajax({
                url : 'guardar-permisos.php',
                data : {sistema: sistema , idUsuario: idUsuario},
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);

                    if(obj[sistema] == 1){
                        $('.'+ sistema +'').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.'+ sistema +'').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }     
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });

        });

        $(".btn-sistemas-user").on("click" , function(){

            tr = $(this).closest("tr");
            var sistemasUser = tr.data("id");

            idUserSistemasModificaAccesos = sistemasUser;

            $.ajax({
                url : 'verpermisos.php',
                data : { 'id' : sistemasUser },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
               
                    var obj = jQuery.parseJSON(respuesta);

                    if(obj.calendario == 1){
                        $('.calendario').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.calendario').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.chat == 1){
                        $('.chat').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.chat').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.cotizador == 1){
                        $('.cotizador').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.cotizador').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.crm == 1){
                        $('.crm').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.crm').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.formularios == 1){
                        $('.formularios').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.formularios').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.listas == 1){
                        $('.listas').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.listas').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.outs == 1){
                        $('.outs').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.outs').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.pagos == 1){
                        $('.pagos').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.pagos').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.produccion == 1){
                        $('.produccion').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.produccion').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.respuestas == 1){
                        $('.respuestas').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.respuestas').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.stock == 1){
                        $('.stock').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.stock').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    if(obj.rrhh == 1){
                        $('.rrhh').removeClass('btn-default').removeClass('btn-danger').addClass('btn-success').text('ENCENDIDO');
                    }else{
                        $('.rrhh').removeClass('btn-success').removeClass('btn-default').addClass('btn-danger').text('APAGADO');
                    }

                    $('#modaleditarsistemasusuario').modal("show");     
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });            

        });
        
        $(".btn-modificar-user").on( "click", function() {

            validator.resetForm();

            tr = $(this).closest("tr");
            var moduser = tr.data("id");
            $.ajax({
                url : 'verusuario.php',
                data : { 'id' : moduser },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    $("#nombre").val("");
                    $("#user").val("");
					$("#id-empleado").val("");
                    $("#pass").val("");
                    $("#apellido").val("");
                    $("#correo").val("");
                    var obj = jQuery.parseJSON(respuesta);
                    $("#idusuario").val(obj.id);
                    $("#nombre").val(obj.nombre);
                    $("#apellido").val(obj.apellido);
                    $("#correo").val(obj.email);
                    $("#user").val(obj.user);
                    $("#permisos").empty();
					$("#id-empleado").val(obj.id_empleado);
					$("#empleado").val( obj.id_empleado != 0 ? obj.id_empleado + " : " + obj.empleado : "" );
					$(".campo-de-firmas").show();
					if (obj.firma != ""){
						$('.sin-firma').hide();
						$('.firma').show();
						$('.firma img').attr("src", "rrhh/firmas/" + obj.firma);
					} else {
						$('.sin-firma').show();
						$('.firma').hide();
						$('.firma img').attr("src", "");
					}
                    $('#permisos').html(
                        '<option value="admin">ADMINISTRADOR</option>'+
                        '<option value="usuario">USUARIO</option>'+
                        '<option value="externo">EXTERNO</option>');
                    $('option[value='+obj.permisos+']').prop('selected', true);

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
            
            validator.resetForm();

            $("#nombre").val("");
            $("#user").val("");
            $("#id-empleado").val("");
			$("#empleado").val( "" );
            $("#pass").val("");
            $("#apellido").val("");
            $("#correo").val("");
            $("#permisos").empty();
 			$('.sin-firma').show();
			$('.firma').hide();
			$('.firma img').attr("src", "");
			$(".campo-de-firmas").hide();
           $("<option value='admin'>ADMINISTRADOR</option>").appendTo("#permisos");
            $("<option value='usuario'>USUARIO</option>").appendTo("#permisos");
            $("<option value='externo'>EXTERNO</option>").appendTo("#permisos");
            $(".modal-title-user").text("Agregar Nuevo Usuario");
            $("#modaleditarusuario").modal("show");
            $("#modalusuarios").modal("hide");

        });
        $("#btn-guardar-user").on("click", function(e) {

			var id_empleado = $("#id-empleado").val();
			id_empleado = id_empleado != "" ? id_empleado : 0;
			
            if(validator.form()){
                var tipomodal = $(".modal-title-user").text();
                if (tipomodal === "Agregar Nuevo Usuario"){
                    var tipomodal1 = 'agregar';
                    var params = { 
                        accion : tipomodal1,
                        nombre : $("#nombre").val(),
                        apellido : $("#apellido").val(),
                        correo : $("#correo").val(),
                        user : $("#user").val(),
                        pass : $("#pass").val(),
						id_empleado : id_empleado,
                        permisos : $("#permisos").val()
                    }
                    if($("#pass").val() == $("#passconfirm").val()){
                        $.ajax({
                            url : 'guardarusuario.php',
                            data : params,
                            type : 'GET',
                            dataType : 'html',
                            success : function(respuesta) {
                                var url = "usuarios.php?msg="+respuesta; 
                                url = "usuarios-"+respuesta; 
                                $(location).attr('href',url);
                            },
                            error : function(xhr, status) {
                                alert('Disculpe, existió un problema');
                            },
                        });
                    }else{
                        alert('Disculpe, existió un problema confirmando su password.');
                    }                
                }else if(tipomodal === "Modificar Usuario"){
                    var tipomodal1 = 'modificar';
                    var params = { 
                        accion : tipomodal1,
                        idusuario : $("#idusuario").val(),
                        nombre : $("#nombre").val(),
                        apellido : $("#apellido").val(),
                        correo : $("#correo").val(),
                        user : $("#user").val(),
                        pass : $("#pass").val(),
						id_empleado : id_empleado,
                        permisos : $("#permisos").val()
                    }
                    if ($("#pass").val() == $("#passconfirm").val()){
                        $.ajax({
                            url : 'guardarusuario.php',
                            data : params,
                            type : 'GET',
                            dataType : 'html',
                            success : function(respuesta) {
                                var url = "usuarios.php?msg="+respuesta; 
                                url = "usuarios-"+respuesta; 
                                $(location).attr('href',url);
                            },
                            error : function(xhr, status) {
                                alert('Disculpe, existió un problema');
                            },
                        });
                    }else{
                        alert('Disculpe, existió un problema confirmando su password.');
                    }
                }    
            }else{
                alert('Disculpe, existió un problema de validacion.');
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
                    var url = "usuarios.php?msg="+respuesta; 
                    url = "usuarios-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
		$(".btn-cambiar-id-empleado").on( "click", function() {
            $("#modal-empleados").modal("show");
		});
		$(".employee-card>a").on( "click", function(e) {
			e.preventDefault();
			var name = $(this).find(".name").text(),
				id = $(this).data("id");
			$("#modal-empleados").modal("hide");
			$("#empleado").val( id + " : " + name );
			$("#id-empleado").val( id );
        });
		$(".btn-file-upload").on( "click", function(e) {
			e.preventDefault();
			$("#firma").click();
		});
		$("#firma").change(function() {
			var file = this.files[0];
			if (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/jpg") {
				$('.btn-file-upload').prop('disabled', 'true').text("Subiendo...");
				
				console.log(new FormData(document.querySelector("#modaleditarusuario form")))
				$.ajax({
					url: "guardar-firma.php",
					xhr: function () { // custom xhr (is the best)

						var xhr = new XMLHttpRequest();
						var total = 0;

						// Get the total size of files
						$.each(document.getElementById('firma').files, function (i, file) {
							total += file.size;
						});

						// Called when upload progress changes. xhr2
						xhr.upload.addEventListener("progress", function (evt) {
							// show progress like example
							var loaded = (evt.loaded / total).toFixed(2) * 100; // percent

							//$('.btn-file-upload').text("Subiendo... " + loaded + "%");
						}, false);

						return xhr;
					},
					type: 'POST',
					processData: false,
					contentType: false,
					data: new FormData(document.querySelector("#modaleditarusuario form")),
					success: function (respuesta) {
						let rsp = jQuery.parseJSON(respuesta);
						if (rsp.result != "SUCCESS") {
							alert( rsp.result );
						} else {
							$('.btn-file-upload').prop('disabled', '').text("Subir");
							$('.sin-firma').hide();
							$('.firma').show();
							$('.firma img').attr("src", "rrhh/firmas/" + rsp.filename);
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + ', ' + thrownError + '\n');
						$('#imagePreviewRow').find('.col-md-3').last().remove();
					}
				});
				$('#btn-upload-images').attr('disabled', false);
			} else {
				alert('ERROR: solo son admitidos los formatos jpeg, jpg y png.');
				return false;
			}
		});
		$('.btn-remover-firma').on( "click", function() {
			$.ajax({
                url : 'remover-firma.php',
                data : {
					id: $("#idusuario").val(),
					filename: $('.firma img').attr('src'),
				},
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    if (respuesta != "SUCCESS"){
						alert(respuesta);
					}else{
						$('.sin-firma').show();
						$('.firma').hide();
						$('.firma img').attr("src", "");
					}
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
		});
		
		$("#filtro-empleados").on( "change paste keyup", function() {console.log("changed!")
			let search = $(this).val().toLowerCase();
			if (search == "") {
				$(".employee-card").show();
			} else {
				$.each( $(".employee-card"), function( key, value ) {
					let name = $(value).find(".name").text().toLowerCase();
					if (name.includes(search)){
						$(value).show();
					} else {
						$(value).hide();
					}
				});
			}
		});
		
		$('[data-toggle="tooltip"]').tooltip()
    </script>

</body>
</html>
<?php }else{
    header("location: $basehttp");
} ?>