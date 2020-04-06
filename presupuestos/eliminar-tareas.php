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
				
				$results = $mysqli->query("DELETE FROM presupuestos_tareas WHERE id = $borrada");
				
				if ( ! $results )
					$error[] = $borrada;
				
			}

			mysqli_close($mysqli);
			
			if( ! (count($error) > 0) ) {

				$msg = "";
				$msg .= ( (count($borradas)>1) ? "Las tareas" : "La tarea" );
				$msg .= " (" . implode(", ", $borradas) . ") ";
				$msg .= ( (count($borradas)>1) ? "fueron eliminadas" : "fué eliminada" );
				$msg .= " de manera exitosa!";
				echo $msg;

			} else {
				echo "Se produjo un error tratando de borrar las tareas con id: " . implode(", ", $error);
			}
			
		} else {
			echo "No hay ninguna tarea seleccionada para borrar";
		}
		
	} else header("Location: tareas");


?>