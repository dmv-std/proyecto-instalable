<?php include ("../config.php");
	
	if ( isset($_GET['borrar']) )
	{
		if ( $_GET['borrar'] != "" ) {
			
			$borradas = explode(",", $_GET['borrar']);

			$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			if($mysqli->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$mysqli->set_charset("utf8");
			
			$error = array();
			
			foreach($borradas as $borrada) {
				
				$results = $mysqli->query("DELETE FROM presupuestos WHERE id = $borrada");
				$results1 = $mysqli->query("DELETE FROM presupuestos_observaciones WHERE id_presupuesto = $borrada");
				$results1 = $mysqli->query("DELETE FROM presupuestos_tareas WHERE id_presupuesto = $borrada");
				
				$results1 = $mysqli->query("SELECT * FROM presupuestos_archivos WHERE id_presupuesto = $borrada");
				while($row = $results1->fetch_assoc()){
					$file = $basepath . "/". $presupuestos_archivos_path . "/" . $row['file'];
					if ( file_exists($file) )
						unlink( $file );
				}
				$results1 = $mysqli->query("DELETE FROM presupuestos_archivos WHERE id_presupuesto = $borrada");
				
				if ( ! $results )
					$error[] = $borrada;
				
			}

			mysqli_close($mysqli);
			
			if(count($error) == 0) {

				$msg = "";
				$msg .= ( (count($borradas)>1) ? "Los presupuestos" : "El presupuesto" );
				$msg .= " (" . implode(", ", $borradas) . ") ";
				$msg .= ( (count($borradas)>1) ? "fueron eliminados" : "fué eliminado" );
				$msg .= " de manera exitosa!";
				echo $msg;

			} else {
				echo "Se produjo un error tratando de eliminar los presupuestos con id: " . implode(", ", $error);
			}
			
		} else {
			echo "No hay ningun presupuesto seleccionado para eliminar";
		}
		
	} else header("Location: listado");
	
?>