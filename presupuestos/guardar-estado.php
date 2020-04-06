<?php session_start();
    include ("../config.php");
    if(isset($_GET['estado'])&&isset($_GET['idpresupuesto'])) {
		
        $estado = $_GET['estado'];
        $idpresupuesto = $_GET['idpresupuesto'];
		//$fecha = date('Y-m-d H:i:s');
        //$tipo = "CAMBIO DE ESTATUS";
		//$observacion = "El usuario ".$_SESSION['nombrePersona']." cambio el estatus de la cotizacion a $estado";
		//$id_user = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		$result = $mysqli->query("SELECT * FROM presupuestos WHERE id = '$idpresupuesto'") or die($mysqli->error.__LINE__);
		$presupuesto = $result->fetch_assoc();
		
		mysqli_close($mysqli);
		
		// Cambiar estatus solo si éste no tenía previamente el valor de "CONCRETADO" - una vez la cotización haya sido concretada, el estatus no podrá ser cambiado nuevamente.
        if( $presupuesto["estado"] != "CONCRETADO" ) {
			
            $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }
			$mysqli->set_charset("utf8");
            $results = $mysqli->query("UPDATE presupuestos SET estado = '$estado' WHERE id = '$idpresupuesto'");
			
			if($results){
				
                echo "El estatus del presupuesto fue actualizado de manera exitosa!";
				
				/*$mysqlix = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
				if($mysqlix->connect_errno > 0){
					die('Unable to connect to database [' . $db->connect_error . ']');
				}
				$mysqlix->set_charset("utf8");
				$resultsx = $mysqlix->query("INSERT INTO presupuestos_observaciones (id_presupuesto, observacion, fecha, tipo, id_user) values('$idcotizacion', '$observacion', '$fecha', '$tipo', '$id_user')");
				if($resultsx){
					$msg = "El estatus del presupuesto fue actualizado de manera exitosa!";
                echo $msg;
				}else{
					echo 'Error : ('. $mysqlix->errno .') '. $mysqlix->error;
				}*/
				
				/*if ( $estado == "CONCRETADO" )
				{
					// Programar envíos automáticos de correos de confirmación de estatus CONCRETADO
					$resultsx = $mysqlix->query("SELECT * FROM presupuestos_configuracion WHERE id = '1'") or die($mysqli->error.__LINE__);
					$row = $resultsx->fetch_assoc();
					$row['correos_concretado_json'] = htmlentities(preg_replace("[\n|\r|\n\r]","###", $row['correos_concretado_json']),ENT_NOQUOTES, 'UTF-8');
					$correos = json_decode($row['correos_concretado_json']);

					$fecha_reenvios = array();

					foreach ($correos as $key => $correo) {

						if ($correo->habilitado) {

							$fecha = date('Y-m-d', strtotime('+'.$correo->dias.' days'));

							$arg = '[{"indice": "' . $key . '"}]';

							$results = $mysqli->query("INSERT INTO presupuestos_tareas (fecha, funcion, arg, id_presupuesto, id_accion)
								values('$fecha', 'notificarConcretado', '$arg', '$idpresupuesto', '0')");

							if($results) {
								//echo "INSERT INTO programadas (fecha, funcion, arg, id_act_asc, idaccion) values('$fecha', 'notificarConcretado', '$arg', '$idcotizacion', '0')\r\n";
								$fecha_reenvios[] = date("D (d/M/Y)", strtotime($fecha));
							} else {
								echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
							}
						}
					}
					if ( count($fecha_reenvios)>0 ) {
						$observacion = "Se ha programado una NOTIFICACION DE CONCRETADO para el presupuesto ($idpresupuesto) para los siguientes días: " . implode(", ", $fecha_reenvios) . ".";
					} else {
						$observacion = "Ocurrió un error inesperado de conexión con la base de datos y los reenvíos no pudieron ser programados.";
					}
					$fecha = date('Y-m-d H:i:s');
					$results_obs = $mysqlix->query("INSERT INTO presupuestos_observaciones (id_presupuesto, observacion, fecha, tipo)
						values('$idpresupuesto', '$observacion', '$fecha', 'PROGRAMAR NOTIFICACION CONCRETADO')");
					
					mysqli_close($mysqlix);
				}*/
				
            }else{
                echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
            }
            mysqli_close($mysqli);
			
        } else echo "No se puede cambiar el estado una vez haya sido CONCRETADO";
    }
?>