<?php if (file_exists("../../config.php"))
        include ("../../config.php");
    else { header("location: ../../instalador"); exit(); }
?>
<?php session_start();
    if($_SESSION['cotizador2'] != 1){
        header ("Location: $basehttp");
    }
    if(empty($_SESSION['userPersona']) || $_SESSION['permisosPersona'] == "no" ){
        header("location: $basehttp");
    } elseif($_SESSION['permisosPersona']=="admin") {
        $mysqlix = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqlix->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $mysqlix->set_charset("utf8");
        $queryx = "SELECT * FROM cot2_colores WHERE id = '1' ORDER BY id ASC";
        $resultx = $mysqlix->query($queryx) or die($mysqlix->error.__LINE__);
        $rowx = $resultx->fetch_assoc();
        mysqli_close($mysqlix);
		
        $mysqli2 = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli2->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $mysqli2->set_charset("utf8");
        $query2 = "SELECT * FROM cot2_configuraciones WHERE id = '1' ORDER BY id ASC";
        $result2 = $mysqli2->query($query2) or die($mysqli2->error.__LINE__);
        $row2 = $result2->fetch_assoc();
		
        $remitentenombre = $row2['reenvio_remitente_nombre'];
        $remitenteemail = $row2['reenvio_remitente_email'];
		if ($row2['reenvio_datos_json'] != ""){
			$row2['reenvio_datos_json'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row2['reenvio_datos_json']), ENT_NOQUOTES, 'UTF-8');
			$correos = json_decode($row2['reenvio_datos_json']);
			$reenvio_datos_json = $row2['reenvio_datos_json'];
		} else {
			$correos = [];
			$reenvio_datos_json = "[]";
		}
		$mail_logo = $row2['mail_logo'];
		
		// Configuración básica
		$empresa = $row2['empresa'];
		$direccion = preg_replace("/<br>/", "\n", $row2['direccion']);
		$telefonos = $row2['telefonos'];
		$web = $row2['web'];
		$email = $row2['email'];
		$logo = $row2['logo'];
        
        $cotizador2_pdf_logo = explode("/", $cotizador2_pdf_logo);
        array_pop($cotizador2_pdf_logo);
        $cotizador2_pdf_logo = implode("/", $cotizador2_pdf_logo);

        $logo_url = $basehttp.$cotizador2_pdf_logo."/".$logo;

        $titulo_pdf = $row2['titulo-pdf'];
        $titulo_pdf = explode("|", $titulo_pdf);
        $cases = array_pop($titulo_pdf);
        $titulo_pdf = implode("|", $titulo_pdf);

        mysqli_close($mysqli2);
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
	<script src="https://kit.fontawesome.com/b8c47e2cca.js" crossorigin="anonymous"></script><!-- Font Awesome kit latest -->
    <!--[if lt IE 9]>
    <script src="<?php echo $js_url ?>/ie.min.js"></script>
    <![endif]-->
	<style type="text/css">
	textarea#direccion {
		width: 100%;
		resize: none;
		height: 70px;
	}
    input[name="lettertype"] {
        margin-right: 12px;
    }
	
	 /* The switch - the box around the slider */
	.switch {
	  position: relative;
	  display: inline-block;
	  width: 60px;
	  height: 34px;
	}

	/* Hide default HTML checkbox */
	.switch input {
	  opacity: 0;
	  width: 0;
	  height: 0;
	}

	/* The slider */
	.slider {
	  position: absolute;
	  cursor: pointer;
	  top: 0;
	  left: 0;
	  right: 0;
	  bottom: 0;
	  background-color: #ccc;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	.slider:before {
	  position: absolute;
	  content: "";
	  height: 26px;
	  width: 26px;
	  left: 4px;
	  bottom: 4px;
	  background-color: white;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	input:checked + .slider {
	  background-color: #2196F3;
	}

	input:focus + .slider {
	  box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
	  -webkit-transform: translateX(26px);
	  -ms-transform: translateX(26px);
	  transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
	  border-radius: 34px;
	}

	.slider.round:before {
	  border-radius: 50%;
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
                    <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;CONFIGURACIONES</h1>
                </div>
            </div> <!-- / .page-header -->
            <!-- ELIMINAR RUBRO -->
            <div id="modalformelirubro" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">ELIMINAR RUBRO</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">Desea eliminar esta rubro?</div>
                            <form class="form-horizontal" id="jq-validation-form">
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Rubro</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="descripcionelirubro" name="descripcionelirubro" placeholder="Rubro">
                                        <input type="hidden" class="form-control" id="idelirubro" name="idelirubro">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                            <button type="button" id="btn-eliminarrubro" class="btn btn-danger">SI, LO QUIERO ELIMINAR</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ELIMINAR SUBRUBRO -->
            <div id="modalformelisubrubro" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">ELIMINAR SUBRUBRO</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">Desea eliminar esta Subrubro?</div>
                            <form class="form-horizontal" id="jq-validation-form">
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Subrubro</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="descripcionelisubrubro" name="descripcionelisubrubro" placeholder="Subrubro">
                                        <input type="hidden" class="form-control" id="idelisubrubro" name="idelisubrubro">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                            <button type="button" id="btn-eliminarsubrubro" class="btn btn-danger">SI, LO QUIERO ELIMINAR</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MODIFICAR RUBRO -->
            <div id="modalformmodrubro" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">MODIFICAR RUBRO</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="jq-validation-form">
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Rubro</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="descripcionmodrubro" name="descripcionmodrubro" placeholder="Rubro">
                                        <input type="hidden" class="form-control" id="idmodrubro" name="idmodrubro">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                            <button type="button" id="btn-guardarmodrubro" class="btn btn-danger">GUARDAR RUBRO</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MODIFICAR SUBRUBRO -->
            <div id="modalformmodsubrubro" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">MODIFICAR SUBRUBRO</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="jq-validation-form">
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Subrubro</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="descripcionmodsubrubro" name="descripcionmodsubrubro" placeholder="Subrubro">
                                        <input type="hidden" class="form-control" id="idmodsubrubro" name="idmodsubrubro">
                                    </div>
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Rubro</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="rubromodsubrubro"></select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                            <button type="button" id="btn-guardarmodsubrubro" class="btn btn-danger">GUARDAR SUBRUBRO</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- AGREGAR RUBRO -->
            <div id="modalformaddrubro" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">AGREGAR RUBRO</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="jq-validation-form">
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Rubro</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="descripcionaddrubro" name="descripcionaddrubro" placeholder="Rubro">
                                        <input type="hidden" class="form-control" id="idaddrubro" name="idaddrubro">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                            <button type="button" id="btn-guardarrubro" class="btn btn-danger">GUARDAR RUBRO</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- AGREGAR SUBRUBRO -->
            <div id="modalformaddsubrubro" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">AGREGAR SUBRUBRO</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" id="jq-validation-form">
                                <div class="form-group">
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Subrubro</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="descripcionaddsubrubro" name="descripcionaddsubrubro" placeholder="Subrubro">
                                    </div>
                                    <label for="jq-validation-required" class="col-sm-3 control-label">Rubro</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="rubroaddsubrubro"></select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                            <button type="button" id="btn-guardarsubrubro" class="btn btn-danger">GUARDAR SUBRUBRO</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                if(isset($_GET['msg'])){
                    $mensaje = $_GET['msg'];
                    echo "<div class='alert alert-success'>".$mensaje."</div>";
                }
            ?>
            <div class="col-md-6">                
                <script>
                    init.push(function () {
                        $('#jq-datatables-entidad').dataTable({
                            "order": [[ 10, "desc" ]]
                        } );
                        $('#jq-datatables-example_wrapper .table-caption').text('RUBROS');
                        $('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
                    });
                </script>
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">RUBROS<div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-sm btn-info" id="btn-agregarrubros" >AGREGAR RUBRO</button></div><br /></span>
                    </div>
                    <div class="panel-body">
                        
                        <div class="table-primary">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-entidad">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DESCRIPCION</th>
                                    <th>ACCIONES</th>
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
                                $query = "SELECT * FROM cot2_rubros ORDER BY id ASC";
                                $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
                                while($row = $result->fetch_assoc()) {
                                    $idform = $row['id'];
                                    echo "<tr data-id=\"".$idform."\">";
                                        echo "<td>".$row['id']."</td>";
                                        echo "<td>".$row['descripcion']."</td>";
                                        echo "<td><button  class=\"btn-modificarrubros col-xs-6 btn\">MODIFICAR</button><button  class=\"btn-eliminarrubros col-xs-6 btn\">ELIMINAR</button></td>";
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
                
                <script>
                    init.push(function () {
                        $('#jq-datatables-motivos').dataTable({
                            "order": [[ 10, "desc" ]]
                        } );
                        $('#jq-datatables-example_wrapper .table-caption').text('MOTIVOS');
                        $('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
                    });
                </script>
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">SUBRUBROS<div class="pull-right col-xs-12 col-sm-auto"><button class="btn btn-sm btn-info" id="btn-agregarsubrubro" >AGREGAR SUBRUBRO</button></div></span>
                    </div>
                    <div class="panel-body">
                        <div class="table-primary">
                            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-motivos">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DESCRIPCION</th>
                                    <th>RUBRO</th>
                                    <th>ACCIONES</th>
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
                                $query = "SELECT cot2_subrubros.*, cot2_rubros.descripcion AS rubro FROM cot2_subrubros LEFT JOIN cot2_rubros ON cot2_rubros.id=cot2_subrubros.idrubro ORDER BY id DESC";
                                $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
                                while($row = $result->fetch_assoc()) {
                                    $idform = $row['id'];
                                    echo "<tr data-id=\"".$idform."\">";
                                        echo "<td>".$row['id']."</td>";
                                        echo "<td>".$row['descripcion']."</td>";
                                        echo "<td>".$row['rubro']."</td>";
                                        echo "<td><button  class=\"btn-modificarsubrubros col-xs-6 btn\">MODIFICAR</button><button  class=\"btn-eliminarsubrubros col-xs-6 btn\">ELIMINAR</button></td>";
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
            <div class="col-md-6"> 
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">PRECIOS DE IMPRESION</span>
                    </div>
                    <div class="panel-body">
					<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Menos de 100:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="preciounitario" name="preciounitario" value="<?php echo $rowx['preciounitario']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Cantidades + 100:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="cantidades100" name="cantidades100" value="<?php echo $rowx['cantidades100']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Cantidades + 200:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="cantidades200" name="cantidades200" value="<?php echo $rowx['cantidades200']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Cantidades + 500:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="cantidades500" name="cantidades500" value="<?php echo $rowx['cantidades500']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Cantidades + 1,000:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="cantidades1000" name="cantidades1000" value="<?php echo $rowx['cantidades1000']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Cantidades 5,000:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="cantidades5000" name="cantidades5000" value="<?php echo $rowx['cantidades5000']; ?>">
                            </div>
                        </div>
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Cantidades + 10,000:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="cantidades10000" name="cantidades10000" value="<?php echo $rowx['cantidades10000']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="pull-right col-xs-12 col-sm-auto">
                                <button class="btn btn-md btn-info" id="btn-guardarcolores" >GUARDAR DATOS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="col-md-6"> 
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">DESCUENTO GLOBAL</span>
                    </div>
                    <div class="panel-body">
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Descuento:</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="descuento" name="descuento" value="<?php echo $row2['descuento']; ?>">
                            </div>
							<div class="col-sm-1">Activar</div>
							<div class="col-sm-1">
								<input type="checkbox" name="activardescuento" id="activardescuento" value="1" <?php if ($row2['activardescuento']==1){echo "checked";}?>>
							</div>
														
                        </div>
                        <div class="form-group">
                            <div class="pull-right col-xs-12 col-sm-auto">
                                <button class="btn btn-md btn-info" id="btn-descuento" >GUARDAR DESCUENTO</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">CONFIGURAR REENVÍOS AUTOMÁTICOS</span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group form-horizontal">
                            <label class="col-sm-3 control-label">Nombre del Remitente:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="remitente-nombre" name="remitente-nombre" value="<?php echo $remitentenombre ?>" placeholder="Nombre del remitente" required>
                            </div>
                        </div>
                        <div class="form-group form-horizontal">
                            <label class="col-sm-3 control-label">Correo del Remitente:</label>
                            <div class="col-sm-7">
                                <input type="email" class="form-control" id="remitente-email" name="remitente-email" value="<?php echo $remitenteemail ?>" placeholder="Correo del remitente" required>
                            </div>
                        </div>
                        <?php if (count($correos) > 0) { ?>

                            <?php foreach ($correos as $i => $correo) { ?>

                                <div id="email-field<?php echo $i+1; ?>">
                                    <div class="form-group form-horizontal mt-lg">
                                        <label class="col-sm-3 control-label">Correo <?php echo $i+1; ?>:</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="asunto<?php echo $i+1; ?>" name="asunto<?php echo $i+1; ?>" value="<?php echo $correo->asunto; ?>" placeholder="Asunto del correo">
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" name="correo<?php echo $i+1; ?>-checkbox" id="correo<?php echo $i+1; ?>-checkbox" <?php echo $correo->habilitado ? 'checked' : ''; ?>>
                                        </div>
                                        <div class="col-sm-1">Activar</div>
                                    </div>
                                    <div class="form-group form-horizontal">
                                        <div class="col-sm-7 col-md-offset-3">
                                            <textarea class="form-control" id="cuerpo<?php echo $i+1; ?>" rows="6" name="cuerpo<?php echo $i+1; ?>" placeholder="Cuerpo del correo"><?php echo preg_replace("/###/","\r\n", $correo->correo) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-horizontal">
                                        <div class="col-sm-7 col-md-offset-3">
                                            <input type="number" class="form-control" id="dias<?php echo $i+1; ?>" name="dias<?php echo $i+1; ?>" min="0" value="<?php echo $correo->dias ?>" placeholder="Dias para el reenvío">
                                        </div>
                                        <div class="col-sm-2">Dias</div>
                                    </div>
                                </div>

                            <?php } ?>

                        <?php } else { ?>

                            <div id="email-field1">
                                <div class="form-group form-horizontal mt-lg">
                                    <label class="col-sm-3 control-label">Correo 1:</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="asunto1" name="asunto1" value="" placeholder="Asunto del correo">
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="correo1-checkbox" id="correo1-checkbox" value="1">
                                    </div>
                                    <div class="col-sm-1">Activar</div>
                                </div>
                                <div class="form-group form-horizontal">
                                    <div class="col-sm-7 col-md-offset-3">
                                        <textarea class="form-control" id="cuerpo1" rows="6" name="cuerpo1" placeholder="Cuerpo del correo"></textarea>
                                    </div>
                                </div>
                                <div class="form-group form-horizontal">
                                    <div class="col-sm-7 col-md-offset-3">
                                        <input type="number" class="form-control" id="dias1" name="dias1" min="1" value="" placeholder="Dias para el reenvío">
                                    </div>
                                    <div class="col-sm-2">Dias</div>
                                </div>
                            </div>

                        <?php } ?>

                        <div class="form-group form-horizontal">
                            <button id="btn-agregarcorreos" class="btn btn-secondary col-md-offset-2">Agregar</button>
                            <?php if (count($correos) > 1) { ?>
                                <button class="btn btn-secondary" id="btn-quitarcorreos">Quitar</button>
                            <?php } ?>
                        </div>
						
						<div class="form-group">
                            <div class="pull-right col-xs-12 col-sm-auto">
								<button id="btn-guardarreenvio" class="btn btn-md btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="col-md-6" id="datos-doc-pdf">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">DATOS GENERALES PARA EL DOCUMENTO</span>
                    </div>
                    <div class="panel-body">
						<form class="form-horizontal" id="jq-validation-form" novalidate="novalidate">
							<div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Nombre del archivo PDF:<br/><i class="fa fa-question-circle btn-formato-info" data-toggle="tooltip" data-placement="bottom" title="+nombre+: cliente-nombre, +apellido+: cliente-apellido, +fecha+: fecha-cotizacion formato DD-MM-YYYY" style="cursor:pointer"></i></label>
                                <div class="col-sm-7">
                                    <p>
                                        <?php $empty_preview="(Ingrese un título)" ?>
                                        <em id="titulo-pdf-preview"><?php echo $empty_preview?></em><br/>
                                        <label for="upper">XXXXX</label>
                                        <input type="radio" name="lettertype" value="upper" id="upper">
                                        <label for="lower">xxxxx</label>
                                        <input type="radio" name="lettertype" value="lower" id="lower">
                                        <label for="capital">Xxxxx</label>
                                        <input type="radio" name="lettertype" value="capital" id="capital">
                                    </p>
                                    <input required type="text" class="form-control" id="titulo-pdf" name="titulo-pdf" placeholder="Nombre del archivo PDF" value="<?php echo $titulo_pdf ?>">
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Logo:</label>
                                <div class="col-sm-7" style="position:relative">
									<button class="btn btn-primary btn-file-upload" style="margin-right:15px">Subir</button>
									<span class="sin-logo" style="<?php echo $logo?'display:none':'' ?>"><em>Ninguno</em></span>
									<span class="logo" style="<?php echo $logo?'':'display:none' ?>">
										<img style="height:31px" src="<?php echo $logo ? "$logo_url" : "" ?>" />
										<a class="btn-remover-logo" style="font-size:1.5rem;color:#ae6767" href="#" data-toggle="tooltip" data-placement="bottom" title="Remover Logo"><i class="fa fa-trash-alt"></i></a>
									</span>
									<input type="file" style="display:none" id="logo" name="logo" />
									<input type="hidden" id="action" name="action" value="store" />
                                </div>
                            </div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Empresa:</label>
								<div class="col-sm-7">
									<input required type="text" class="form-control" id="empresa" name="empresa" placeholder="Nombre de la empresa" value="<?php echo $empresa ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Dirección:</label>
								<div class="col-sm-7">
									<textarea required class="form-control" id="direccion" name="direccion" placeholder="Dirección"><?php echo $direccion ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Teléfonos:</label>
								<div class="col-sm-7">
									<input required type="text" class="form-control" id="telefonos" name="telefonos" placeholder="Teléfonos" value="<?php echo $telefonos ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Página web:</label>
								<div class="col-sm-7">
									<input required type="text" class="form-control" id="web" name="web" placeholder="Página web" value="<?php echo $web ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="jq-validation-required" class="col-sm-3 control-label">Correo electrónico:</label>
								<div class="col-sm-7">
									<input required type="text" class="form-control" id="email" name="email" placeholder="Correo electrónico" value="<?php echo $email ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="pull-right col-xs-12 col-sm-auto">
									<button type="button" id="btn-guardar-configuracion-basica" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
								</div>
							</div>
						</form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">OTRAS CONFIGURACIONES</span>
                    </div>
                    <div class="panel-body">
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Habilitar Impresión</label>
                            <div class="col-sm-7"><?php // https://www.w3schools.com/howto/howto_css_switch.asp ?>
								<label class="switch">
									<input type="checkbox" id="habilitar_impresion" name="habilitar_impresion" <?php echo $row2['habilitar_impresion']?"checked":"" ?>>
									<span class="slider round"></span>
								</label>
							</div>
                        </div>
                        <div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Mensaje
                                Cotizador:</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" rows="5" id="mensajecotizador" name="mensajecotizador"><?php echo $row2['mensajecotizador']; ?></textarea>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Mensaje
                                Cotizacion:</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" rows="3" id="mensajeprincipal" name="mensajeprincipal"><?php echo $row2['mensajeprincipal']; ?></textarea>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Correo de Contacto:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="emailconf" value="<?php echo $row2['emailconf']; ?>" name="emailconf" />
                            </div>
                        </div>
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Asunto de Correo:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="asuntoemail" value="<?php echo $row2['asuntoemail']; ?>" name="asuntoemail" />
                            </div>
                        </div>
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Firma de correo:</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" rows="5" id="mensajefinal" name="mensajefinal"><?php echo $row2['mensajefinal']; ?></textarea>
                            </div>
                        </div>
						<?php if (false): // No funcionó incrustar una imagen en el mail cuya url apunta a evamagic ?>
						<form id="form_mail_logo">
							<div class="form-group">
                                <label for="jq-validation-required" class="col-sm-3 control-label">Logo para el correo:</label>
                                <div class="col-sm-7" style="position:relative">
									<button class="btn btn-primary btn-file-upload2" style="margin-right:15px">Subir</button>
									<span class="sin-logo2" style="<?php echo $mail_logo?'display:none':'' ?>"><em>Ninguno</em></span>
									<span class="logo2" style="<?php echo $mail_logo?'':'display:none' ?>">
										<img style="height:31px" src="<?php echo $mail_logo ? "$images_url/$mail_logo" : "" ?>" />
										<a class="btn-remover-logo2" style="font-size:1.5rem;color:#ae6767" href="#" data-toggle="tooltip" data-placement="bottom" title="Remover Logo"><i class="fa fa-trash-alt"></i></a>
									</span>
									<input type="file" style="display:none" id="mail_logo" name="mail_logo" />
									<input type="hidden" id="action" name="action" value="store" />
                                </div>
                            </div>
						</form>
						<?php endif ?>
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">Logo para el correo:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="mail_logo_url" value="<?php echo $row2['mail_logo']; ?>" name="mail_logo_url" />
                            </div>
                        </div>
						<div class="form-group">
                            <label for="jq-validation-required" class="col-sm-3 control-label">IVA (%):</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" id="iva" value="<?php echo $row2['iva']; ?>" name="iva" min="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="pull-right col-xs-12 col-sm-auto">
                                <button class="btn btn-md btn-info" id="btn-guardarconfig" >GUARDAR DATOS</button>
                            </div>
                        </div>
                    </div>
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
    <script src="<?php echo $js_url ?>/bootstrap.min.js"></script>
    <script src="<?php echo $js_url ?>/pixel-admin.min.js"></script>
     <script type="text/javascript">
        init.push(function () {
            // Javascript code here
        })
        window.PixelAdmin.start(init);

        Array.prototype.last = function(){
            return this[this.length - 1];
            //return this.slice(-1); // alternative!
        };

        $(".btn-modificarsubrubros").on( "click", function() {
            tr = $(this).closest("tr");
            var modsubrubro = tr.data("id");
            $.ajax({
                url : 'versubrubro.php',
                data : { 'id' : modsubrubro },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);
                    $("#idmodsubrubro").val(obj.id);
                    $("#descripcionmodsubrubro").val(obj.descripcion);
                    $.ajax({
                        url : '../verrubros.php',
                        type : 'GET',
                        dataType : 'json',
                        success : function(respuesta) {
                            $("#rubromodsubrubro").empty();
                            $.each(respuesta, function(k,v){
                                if(obj.idrubro==k){
                                    $("<option value='"+k+"' selected>"+v+"</option>").appendTo("#rubromodsubrubro");
                                }else{
                                    $("<option value='"+k+"'>"+v+"</option>").appendTo("#rubromodsubrubro");
                                }
                            });
                        },
                        error : function(xhr, status) {
                            alert('Disculpe, existió un problema');
                        },
                    });
                    $("#modalformmodsubrubro").modal("show");
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
            
        });
        $("#btn-guardarmodsubrubro").on( "click", function() {
            var params = {
                accion : 'modificar',
                id : $("#idmodsubrubro").val(),
                descripcion : $("#descripcionmodsubrubro").val(),
                rubrox : $("#rubromodsubrubro").val()
            }
            $.ajax({
                url : 'guardarsubrubro.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta;
                    url = "configuracion-"+respuesta;
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
        $("#btn-agregarsubrubro").on( "click", function() {
            $.ajax({
                url : '../verrubros.php',
                type : 'GET',
                dataType : 'json',
                success : function(respuesta) {
                    $("#rubroaddsubrubro").empty();
                    $.each(respuesta, function(k,v){
                        $("<option value='"+k+"'>"+v+"</option>").appendTo("#rubroaddsubrubro");
                    });
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
            $("#modalformaddsubrubro").modal("show");
        });
        $("#btn-guardarsubrubro").on( "click", function() {
            var params = {
                accion : 'crear',
                descripcion : $("#descripcionaddsubrubro").val(),
                rubrox : $("#rubroaddsubrubro").val()
            }
            $.ajax({
                url : 'guardarsubrubro.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta;
                    url = "configuracion-"+respuesta;
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
        $("#btn-eliminarsubrubro").on( "click", function() {
            var params = {
                accion : 'eliminar',
                id : $("#idelisubrubro").val()
            }
            $.ajax({
                url : 'guardarsubrubro.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta;
                    url = "configuracion-"+respuesta;
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
        $(".btn-eliminarsubrubros").on( "click", function() {
            tr = $(this).closest("tr");
            var elisubrubro = tr.data("id");
            $.ajax({
                url : 'versubrubro.php',
                data : { 'id' : elisubrubro },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);
                    $("#idelisubrubro").val(obj.id);
                    $("#descripcionelisubrubro").val(obj.descripcion);
                    $("#modalformelisubrubro").modal("show");
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
            
        });

        //RUBROS
        $("#btn-agregarrubros").on( "click", function() {
            $("#modalformaddrubro").modal("show");
        });

        $("#btn-guardarrubro").on( "click", function() {
            var params = {
                accion : 'crear',
                descripcion : $("#descripcionaddrubro").val()
            }
            $.ajax({
                url : 'guardarrubro.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta;
                    url = "configuracion-"+respuesta;
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
        $(".btn-modificarrubros").on( "click", function() {
            tr = $(this).closest("tr");
            var modrubro = tr.data("id");
            $.ajax({
                url : 'verrubro.php',
                data : { 'id' : modrubro },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);
                    $("#idmodrubro").val(obj.id);
                    $("#descripcionmodrubro").val(obj.descripcion);
                    $("#modalformmodrubro").modal("show");
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });

        });
        $(".btn-eliminarrubros").on( "click", function() {
            tr = $(this).closest("tr");
            var modrubro = tr.data("id");
            $.ajax({
                url : 'verrubro.php',
                data : { 'id' : modrubro },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var obj = jQuery.parseJSON(respuesta);
                    $("#idelirubro").val(obj.id);
                    $("#descripcionelirubro").val(obj.descripcion);
                    $("#modalformelirubro").modal("show");
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
            
        });


        $("#btn-guardarmodrubro").on( "click", function() {
            var params = { 
                accion : 'modificar',
                id : $("#idmodrubro").val(),
                descripcion : $("#descripcionmodrubro").val(),
            }
            $.ajax({
                url : 'guardarrubro.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta; 
                    url = "configuracion-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });           
        });

        $("#btn-eliminarrubro").on( "click", function() {
            var params = { 
                accion : 'eliminar',
                id : $("#idelirubro").val()
            }
            $.ajax({
                url : 'guardarrubro.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta; 
                    url = "configuracion-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });           
        });
		$("#btn-guardarcolores").on( "click", function() {
            var params = {
				preciounitario : $("#preciounitario").val(),
                cantidades100 : $("#cantidades100").val(),
				cantidades200 : $("#cantidades200").val(),
				cantidades500 : $("#cantidades500").val(),
				cantidades1000 : $("#cantidades1000").val(),
				cantidades5000 : $("#cantidades5000").val(),
				cantidades10000 : $("#cantidades10000").val()
            }
            $.ajax({
                url : 'guardarcolores.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta; 
                    url = "configuracion-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
		$("#btn-descuento").on( "click", function() {
			if ($('#activardescuento').is(':checked')) {
				var activardesc = 1;
			}else{
				var activardesc = 0;
			}
			var params = {
				descuento : $("#descuento").val(),
                activardescuento : activardesc
            }
            $.ajax({
                url : 'guardardescuento.php',
                data : params,
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta; 
                    var url = "configuracion-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
		$("#btn-guardarconfig").on( "click", function() {
			var mensajecotizador = $("#mensajecotizador").val();
			mensajecotizador = mensajecotizador.replace(/\r?\n/g, "<br>");
			var mensajefinal = $("#mensajefinal").val();
			mensajefinal = mensajefinal.replace(/\r?\n/g, "<br>");
			var mensajeprincipal = $("#mensajeprincipal").val();
			mensajeprincipal = mensajeprincipal.replace(/\r?\n/g, "<br>");
			var params = {
				mensajecotizador : mensajecotizador,
				mensajefinal : mensajefinal,
				mensajeprincipal : mensajeprincipal,
				asuntoemail : $("#asuntoemail").val(),
				emailconf : $("#emailconf").val(),
				habilitar_impresion : $('#habilitar_impresion').is(":checked") ? 1 : 0,
				iva : $("#iva").val(),
				mail_logo : $("#mail_logo_url").val(),
			}
			if (params.iva < 0) {
				alert("Error: el valor ingresado para el campo IVA (%) no puede ser negativo!");
				return;
			}
			$.ajax({
				url : 'guardarconfig.php',
				data : params,
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta; 
                    url = "configuracion-"+respuesta; 
					$(location).attr('href',url);
				},
					error : function(xhr, status) {
					alert('Disculpe, existió un problema');
				},
			});
		});
		
		var j = <?php echo (count($correos)==0) ? 1 : count($correos) ?>;
		
		var habilitarCampos = function(i, prefijo, flag) {
            $("#"+prefijo+"asunto"+i).prop("disabled", !flag).prop("required", flag);
            $("#"+prefijo+"cuerpo"+i).prop("disabled", !flag).prop("required", flag);
            $("#"+prefijo+"dias"+i).prop("disabled", !flag).prop("required", flag);
		};

        for (var i = 1; i <= j; i++) {
			habilitarCampos( i, "", $("#correo"+i+"-checkbox").prop("checked") );

            $("#correo"+i+"-checkbox").on( "change", {value: i}, function(e) {
				habilitarCampos( e.data.value, "", this.checked );
            });
        };

        if (j > 1) {
            $("#btn-quitarcorreos").on( "click", function(e) {
                e.preventDefault();
                $("#email-field"+j).remove();
                j--;
                if (j == 1) {
                    $(this).remove();
                }
            });
        }

        $("#btn-agregarcorreos").on( "click", function(e) {

            e.preventDefault();

            j++;

            $(this).parent().before(
                $("<div>").css("display", "none").attr("id", "email-field"+j).append(
                    $("<div>").addClass("form-group form-horizontal mt-lg").append(
                        $("<label>").addClass("col-sm-3 control-label").append( "Correo "+j+":" )
                    ).append(
                        $("<div>").addClass("col-sm-7").append(
                            $("<input>").attr("type", "text").addClass("form-control").attr("id", "asunto" + j)
                                .attr("name", "asunto" + j).attr("placeholder", "Asunto del correo")
                        )
                    ).append(
                        $("<div>").addClass("col-sm-1").append(
                            $("<input>").attr("type", "checkbox").attr("name", "correo"+j+"-checkbox")
                                .attr("id", "correo"+j+"-checkbox").prop("checked", true)
                        )
                    ).append(
                        $("<div>").addClass("col-sm-1").append( "Activar" )
                    )
                ).append(
                    $("<div>").addClass("form-group form-horizontal").append(
                        $("<div>").addClass("col-sm-7 col-md-offset-3").append(
                            $("<textarea>").addClass("form-control").attr("id", "cuerpo"+j)
                                .attr("rows", "6").attr("name", "cuerpo"+j).attr("placeholder", "Cuerpo del correo")
                        )
                    )
                ).append(
                    $("<div>").addClass("form-group form-horizontal").append(
                        $("<div>").addClass("col-sm-7 col-md-offset-3").append(
                            $("<input>").attr("type", "number").addClass("form-control").attr("id", "dias"+j)
                                .attr("name", "dias"+j).attr("min", "1").attr("placeholder", "Dias para el reenvío")
                        )
                    ).append(
                        $("<div>").addClass("col-sm-2").append( "Dias" )
                    )
                )
            );

            $("#asunto"+j).prop("required", true);
            $("#cuerpo"+j).prop("required", true);
            $("#dias"+j).prop("required", true);

            $("#correo"+j+"-checkbox").on( "change", {value: j}, function(e) {
				habilitarCampos( e.data.value, "", this.checked );
            });

            $("#email-field"+j).slideDown();

            if (j <= 2) {
                $(this).parent().append(
                    $("<button>").addClass("btn btn-secondary").attr("id", "btn-quitarcorreos").append( "Quitar" )
                );
                $("#btn-quitarcorreos").on( "click", function(e) {
                    e.preventDefault();
                    $("#email-field"+j).remove();
                    j--;
                    if (j == 1) {
                        $(this).remove();
                    }
                });
            }
        });


        $("#btn-guardarreenvio").on( "click", function(e) {

            // Validar
            for (var i = 1; i <= j; i++) {

                if ( !$("#remitente-nombre").val() ) return;
                if ( !$("#remitente-email").val() ) return;

                if ($("#correo"+i+"-checkbox").prop("checked")) {

                    if (!$("#asunto"+i).val()) return;
                    if (!$("#cuerpo"+i).val()) return;
                    if (!$("#dias"+i).val()) return;
                }
            }

            e.preventDefault();

            $("#btn-guardarreenvio").prop("disabled", true).text( "Guardando..." );
            $("#btn-reenvio-cancelar").prop("disabled", true);
            $("#btn-agregarcorreos").prop("disabled", true);
            $("#btn-quitarcorreos").prop("disabled", true);

            $("#remitente-nombre").prop("disabled", true);
            $("#remitente-email").prop("disabled", true);

            json_obj = [];

            for (var i = 1; i <= j; i++) {

                $("#correo"+i+"-checkbox").prop("disabled", true);
                $("#asunto"+i).prop("disabled", true);
                $("#cuerpo"+i).prop("disabled", true);
                $("#dias"+i).prop("disabled", true);

                json_obj.push({
                    habilitado: $("#correo"+i+"-checkbox").prop("checked"),
                    asunto: $("#asunto"+i).val(),
                    correo: $("#cuerpo"+i).val(),
                    dias: $("#dias"+i).val()
                });
            };

            $.ajax({
                url : 'guardarconfreenvio.php',
                data : {
                    remitentenombre: $("#remitente-nombre").val(),
                    remitenteemail: $("#remitente-email").val(),
                    correos: JSON.stringify(json_obj)
                },
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
                    var url = "configuraciones.php?msg="+respuesta; 
                    url = "configuracion-"+respuesta; 
                    $(location).attr('href',url);
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
        });
		
		$("#btn-guardar-configuracion-basica").on( "click", function() {
            let cases = $('input[name=lettertype]:checked').val() ? $('input[name=lettertype]:checked').val().charAt(0) : "lower";
            let titulo_pdf = $("#titulo-pdf-preview").text().split('.').last() == "pdf" ? $("#titulo-pdf-preview").text().replace(/\.pdf$/, "") + '|' + cases : '';
			$.ajax({
				url : 'guardarconfig-pdf.php',
				data : {
					empresa: $("#empresa").val(),
					direccion: $("#direccion").val().replace(/\n/g, "<br>"),
					telefonos: $("#telefonos").val(),
					web: $("#web").val(),
                    email: $("#email").val(),
                    titulo_pdf: titulo_pdf,
				},
				type : 'GET',
				dataType : 'html',
				success : function(respuesta) {
                    let url = "configuraciones.php?msg="+respuesta; 
                    url = "configuracion-"+respuesta; 
                    $(location).attr('href', url);
				},
				error : function(xhr, status) {
					alert('Ocurrió un problema. Asegúrese de contar con conexión a la red y compruebe si el problema persiste.');
				},
			});
		});
		
		$(".btn-file-upload").on( "click", function(e) {
			e.preventDefault();
			$("#logo").click();
		});
		
		$("#logo").change(function() {
			var file = this.files[0];
			if (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/jpg") {
				$('.btn-file-upload').prop('disabled', 'true').text("Subiendo...");
				
				$.ajax({
					url: "guardarconfig-pdf.php",
					xhr: function () { // custom xhr (is the best)

						var xhr = new XMLHttpRequest();
						var total = 0;

						// Get the total size of files
						$.each(document.getElementById('logo').files, function (i, file) {
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
					data: new FormData(document.querySelector("#datos-doc-pdf form")),
					success: function (respuesta) {
						let rsp = jQuery.parseJSON(respuesta);
						if (rsp.result != "SUCCESS") {
							alert( rsp.result );
						} else {
							$('.btn-file-upload').prop('disabled', '').text("Subir");
							$('.sin-logo').hide();
							$('.logo').show();
							$('.logo img').attr("src", "<?php echo $basehttp.$cotizador2_pdf_logo?>/" + rsp.filename);
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + ', ' + thrownError + '\n');
					}
				});
			} else {
				alert('ERROR: solo son admitidos los formatos jpeg, jpg y png.');
				return false;
			}
		});
		
		$('.btn-remover-logo').on( "click", function(e) {
			e.preventDefault()
			$.ajax({
                url : 'guardarconfig-pdf.php',
                data : {
					logo: $('.logo img').attr('src').split('/').last(),
					action: "delete",
				},
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
					let rsp = jQuery.parseJSON(respuesta);
					if (rsp.result != "SUCCESS") {
						alert(respuesta);
					}else{
						$('.sin-logo').show();
						$('.logo').hide();
						$('.logo img').attr("src", "");
					}
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
		});
		
		<?php if (false): // No funcionó incrustar una imagen en el mail cuya url apunta a evamagic ?>
		$(".btn-file-upload2").on( "click", function(e) {
			e.preventDefault();
			$("#mail_logo").click();
		});
		
		$("#mail_logo").change(function() {
			var file = this.files[0];
			if (file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/jpg") {
				$('.btn-file-upload2').prop('disabled', 'true').text("Subiendo...");
				
				$.ajax({
					url: "guardar-mail-logo.php",
					xhr: function () { // custom xhr (is the best)

						var xhr = new XMLHttpRequest();
						var total = 0;

						// Get the total size of files
						$.each(document.getElementById('mail_logo').files, function (i, file) {
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
					data: new FormData(document.querySelector("#form_mail_logo")),
					success: function (respuesta) {
						let rsp = jQuery.parseJSON(respuesta);
						if (rsp.result != "SUCCESS") {
							alert( rsp.result );
						} else {
							$('.btn-file-upload2').prop('disabled', '').text("Subir");
							$('.sin-logo2').hide();
							$('.logo2').show();
							$('.logo2 img').attr("src", "<?php echo $images_url?>/" + rsp.filename);
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + ', ' + thrownError + '\n');
					}
				});
			} else {
				alert('ERROR: solo son admitidos los formatos jpeg, jpg y png.');
				return false;
			}
		});
		
		$('.btn-remover-logo2').on( "click", function(e) {
			e.preventDefault()
			$.ajax({
                url : 'guardar-mail-logo.php',
                data : {
					mail_logo: $('.logo2 img').attr('src').split('/').last(),
					action: "delete",
				},
                type : 'GET',
                dataType : 'html',
                success : function(respuesta) {
					let rsp = jQuery.parseJSON(respuesta);
					if (rsp.result != "SUCCESS") {
						alert(respuesta);
					}else{
						$('.sin-logo2').show();
						$('.logo2').hide();
						$('.logo2 img').attr("src", "");
					}
                },
                error : function(xhr, status) {
                    alert('Disculpe, existió un problema');
                },
            });
		});
		<?php endif ?>

        $('#titulo-pdf').on('change paste keyup', updatePdfTitlePreview)
        $('input[name=lettertype]').on('change', updatePdfTitlePreview)

        function updatePdfTitlePreview(){
            var cases = $('input[name=lettertype]:checked').val()
            var text = $('#titulo-pdf').val().replace(/\s/g, "-")
            var preview = [];

            text.split('-').forEach((elem) => {
                var item;
                var exceptions = "+nombre+|+apellido+|+fecha+";
                switch(cases) {
                    case "upper":               item = elem.toUpperCase();                              break;
                    default: case "lower":      item = elem.toLowerCase();                              break;
                    case "capital": item = elem.charAt(0).toUpperCase() + elem.slice(1).toLowerCase();  break;
                }
                item = item.replace(new RegExp('('+exceptions.replace(/\+/g, "")+')', 'gi'), function(match) {
                    return match.toLowerCase()
                })
                preview.push(item)
            })

            if (text != '')
                $('#titulo-pdf-preview').text(preview.join('-')+'.pdf')
            else $('#titulo-pdf-preview').text("<?php echo $empty_preview ?>")
        }

        var radio = "<?php echo $cases ?>";
        if (radio == "u")
            $('input[value=upper]').attr('checked', true)
        else if (radio == "c")
            $('input[value=capital]').attr('checked', true)
        else if (radio == "l" || radio == "")
            $('input[value=lower]').attr('checked', true)

        updatePdfTitlePreview()
		
		$('[data-toggle="tooltip"]').tooltip()
    </script>
</body>
</html>
<?php }else{
    header("location: $basehttp/cotizador/admin");
} ?>