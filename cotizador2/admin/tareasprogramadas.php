<?php if (file_exists("../../config.php"))
        include ("../../config.php");
    else { header("location: ../../instalador"); exit(); }
?>
<?php session_start();
	if(empty($_SESSION['userPersona'])){
		header("location: $basehttp");
	} else {
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $mysqli->set_charset("utf8");
        $query = "SELECT cot2_tareas.*, CONCAT(cot2_cotizacion.nombre, ' ', cot2_cotizacion.apellidos) as nombre, cot2_cotizacion.email,
			cot2_cotizacion.fecha AS fecha_cot, cot2_cotizacion.estatus, cot2_cotizacion.usuario
			FROM cot2_tareas
			LEFT JOIN cot2_cotizacion
			ON cot2_cotizacion.id = cot2_tareas.id_cotizacion
			ORDER BY cot2_tareas.fecha";
        $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
		
		$tareas = array();
		
		while ( $row = $result->fetch_assoc() ) {
			$row['arg'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row['arg']), ENT_NOQUOTES, 'UTF-8');
			$row['arg'] = json_decode($row['arg']);
			$tareas[] = $row;
		}
		
		$dias = ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'];
		$meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
		
		$funciones = [
			'reenviarCotizacion' => 'Reenviar cotización',
			'notificarConcretado' => 'Notificar concretado'
		];
		
		//echo '<!--'; print_r($tareas); echo '-->';
	}

?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"><![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Panel de Control de Tareas Programadas - Cotizador 2 - <?php echo $sitename ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- Open Sans font from Google CDN -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <!-- Pixel Admin's stylesheets -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <link href="assets/stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="assets/stylesheets/themes.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="css/calendario.css">
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
	
	<style type="text/css">
		td input[type=checkbox] {
			margin: auto;
			display: block;
		}
		.table-striped > tbody > tr:nth-child(2n+1).checked > td {
			background: #9684ad;
			border: 1px solid #397f96;
			color: white;
		}
		.table-striped > tbody > tr:nth-child(2n).checked > td {
			background: #aa95c5;
			border: 1px solid #397f96;
			color: white;
		}
		.table-striped tbody tr.checked .gray {
			color: white;
		}
		#borrar-programadas.visible {
			position: fixed;
			bottom: 0;
			right: 0;
			margin: 0px 5px 0 0;
			opacity: 1;
			transition: all .4s ease-in-out;
		}
		#borrar-programadas {
			position: fixed;
			bottom: -15px;
			right: 0;
			margin: 0px 5px 0 0;
			opacity: 0;
			transition: all .4s ease-in-out;
		}
		#borrar-programadas .panel-body {
			padding: 8px 15px;
		}
		.alert.self-remove {
			text-align: center;
			position: fixed;
			bottom: 45px;
			left: 0;
			right: 0;
			margin: 50px 100px;
			z-index: 0;
			opacity: 0;
			transition: all .4s ease-in-out;
		}
		.alert.self-remove.visible {
			bottom: 60px;
			opacity: 1;
			z-index: 1000;
			transition: all .4s ease-in-out;
		}
		.gray {
			color: gray;
		}
	</style>
</head>
<body class="theme-frost no-main-menu">
	<script>var pxInit = [];</script>

	<div id="main-wrapper">
    <?php include("header.php");?>
	</div>

    <div id="content-wrapper">
        <div class="page-header">

            <div class="row">
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Tareas Programadas</h1>
            </div>
        </div> <!-- / .page-header -->

       
		<div class="row">
			<div class="panel">
				<div class="panel-heading">
					<span class="panel-title">Lista de tareas pendientes</span>
				</div>
				<!--&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-md" data-toggle="modal" data-target="#myModal">
					<button class="btn btn-primary">AGREGAR NUEVA ENTIDAD</button>
				</a>-->
				
				<?php if ( isset($_GET['msg']) ): ?>
				<div class="alert alert-success success self-remove"><?php echo $_GET['msg'] ?></div>
				<?php endif; ?>
				
				<script>
                    pxInit.push(function () {
						$('#jq-datatables-example').dataTable({
							"order": [[ 1, "asc" ]]
						});
                        $('#jq-datatables-example_wrapper .table-caption').text('TAREAS PROGRAMADAS');
                        $('#jq-datatables-example_wrapper .dataTables_filter input').attr('placeholder', 'Buscar...');
                    });
                </script>

				<div class="panel-body">
					<div class="table-primary">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="jq-datatables-example">
							<thead>
								<tr>
								<th>#</th>
								<th>FECHA</th>
								<th>FUNCIÓN</th>
								<th>ENVÍO</th>
								<th>NOMBRE</th>
								<th>ESTATUS</th>
								<th>COTIZACIÓN</th>
								<th><input type="checkbox" id="select-all-cb"></th>
                                </tr>
							</thead>
							<tbody>
								<?php foreach($tareas as $tarea) { ?>
                                <tr data-id="<?php echo $tarea['id'] ?>">
									<td><?php echo $tarea['id'] ?></td>
									<td>
										<span class="small gray"><?php echo date('Y-m-d', strtotime( $tarea['fecha'] )) ?></span>
										(<em>
											<?php echo $dias[date('w', strtotime( $tarea['fecha'] ))].' '.date('j/', strtotime( $tarea['fecha'] )).$meses[date('n', strtotime( $tarea['fecha'] ))-1].date('/Y', strtotime( $tarea['fecha'] )) ?>
										</em>)
									</td>
									<td><?php echo $funciones[$tarea['funcion']] ?></td>
									<td><?php echo 'Correo N.'.($tarea['arg'][0]->indice+1).'' ?></td>
									<td><?php echo $tarea['nombre'] ?></td>
									<td><?php echo $tarea['estatus'] ?></td>
									<td><a href="cotizacion.php?id=<?php echo $tarea['id_cotizacion'] ?>" class="btn-detalles col-xs-12 btn btn-info">VER COTIZACION</a></td>
									<td style="vertical-align:middle"><input type="checkbox" data-id="<?php echo $tarea['id'] ?>"></td>
                                </tr>
								<?php } ?>
							</tbody>
								
						</table>
					</div>

				</div>


			</div>
		</div> <!-- / #content-wrapper -->
        <div id="main-menu-bg"></div>
    </div> <!-- / #main-wrapper -->
	
	<div id="borrar-programadas">
		<div class="panel">
			<div class="panel-body">
				<button class="btn btn-danger"><i class="fa fa-trash-o"></i> Borrar seleccionadas</button>
			</div>
		</div>
	</div>

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
    <script src="assets/javascripts/pixel-admin.js"></script>


    <script type="text/javascript">
		pxInit.push(function () {
            // Javascript code here
        });
        window.PixelAdmin.start(pxInit);
		
		jQuery(document).ready(function() {
			
			$(".table-striped").find("input[type=checkbox]").parent().on( "click", function() {
				
				var $checkbox = $(this).find("input[type=checkbox]");
				var checked = $checkbox.prop( "checked" );
				
				$(this).parent().toggleClass( "checked" );
				$checkbox.prop( "checked", $(this).parent().hasClass("checked") );
				
				if ( $checkbox.parent().parent().attr("role") ) {
					
					var $tr = $(this).parent().parent().parent().find("tbody").find("tr");
					var $checkboxes = $tr.find("input[type=checkbox]");
					
					$tr.toggleClass( "checked", $(this).parent().hasClass("checked") );
					$checkboxes.prop( "checked", $(this).parent().hasClass("checked") );
					
				}
				else {
					
					var $tr = $(this).parent().parent().parent().find("thead").find("tr");
					var $checkboxes = $tr.find("input[type=checkbox]");
					
					if ( allEmpty( $(".table-striped").find("tbody").find("tr"), "checked" ) ) {
						$tr.toggleClass( "checked", false );
						$checkboxes.prop( "checked", false );
					} else {
						$tr.toggleClass( "checked", true );
						$checkboxes.prop( "checked", true );
					}
					
				}
				
				if ( allEmpty( $(".table-striped").find("tbody").find("tr"), "checked" ) )
					$("#borrar-programadas").removeClass( "visible" );
				else
					$("#borrar-programadas").addClass( "visible" );
				
			});
			
			/*$(".paginate_button a").click(function() {
				$("#borrar-programadas").removeClass( "visible" );
			});*/
			
			$("#borrar-programadas button").click(function() {
				var toDelete = [];
				
				$.each( $(".table-striped").find("tbody").find("tr"), function() {
					if ( $(this).hasClass("checked") )
						toDelete.push( $(this).data("id") );
				});
				
				console.log(toDelete);
				
				$.ajax({
					url : 'borrarprogramadas.php',
					data : {
						borrar: toDelete.join(","),
					},
					type : 'GET',
					dataType : 'html',
					success : function(respuesta) {
						
						var url = "tareasprogramadas.php?msg="+respuesta; 
						var url = "tareas-"+respuesta; 
						$(location).attr('href',url);
						
					},
					error : function(xhr, status) {
						alert('Disculpe, existió un problema');
					},
				});

			});
			
			<?php if ( isset($_GET['msg']) ): ?>
			setTimeout(function(){
				$(".alert.self-remove").addClass("visible");
				
				setTimeout(function(){
					$(".alert.self-remove").removeClass("visible");
				}, 4000);

			}, 100);
			<?php endif; ?>
			
		});
		
		var allEmpty = function( data, class_name ) {
			var len = data.length;
			for (var i=0; i<len; i++)
			{
				if ( $(data[i]).hasClass(class_name) ) {
					return false;
				} else if ( i == (len-1) )
					return true;
			}
		};
	</script>

</body>
</html>