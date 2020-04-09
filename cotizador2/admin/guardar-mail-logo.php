<?php include ("../../config.php");
	
	// Guardar logo
	if ( isset($_FILES['mail_logo']) && isset($_POST['action']) && $_POST['action']=='store' ) {
		
		if ( 0 < $_FILES['mail_logo']['error'] ) {
			$response = array(
				"result" => 'Error: ' . $_FILES['mail_logo']['error']
			);
		}
		else {
			
			$upload_path = $basepath.'/assets/images/';
			$filename = 'mail_logo';
			
			$explode = explode(".", $_FILES['mail_logo']['name']);
			$extension = $explode[count($explode) - 1];
			
			$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			if($mysqli->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$mysqli->set_charset("utf8");
			
			$results = $mysqli->query("
				UPDATE cot2_configuraciones
				SET mail_logo = '$filename.$extension'
				WHERE id = '1'");
			
			if($results){
				move_uploaded_file($_FILES['mail_logo']['tmp_name'], "$upload_path/$filename.$extension");
				
				$response = array(
					"result" => "SUCCESS",
					"filename" => "$filename.$extension",
				);
			}else{
				$response = array(
					"result" => 'Error : ('. $mysqli->errno .') '. $mysqli->error,
				);
			}
			mysqli_close($mysqli);
		}
			
		echo json_encode($response);
	}
	
	// Eliminar logo
	else if ( isset($_GET['mail_logo']) && isset($_GET['action']) && $_GET['action']=='delete' ) {
		
		$logo = $basepath."/assets/images/".$_GET['mail_logo'];
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		
		$results = $mysqli->query("UPDATE cot2_configuraciones SET mail_logo = '' WHERE id = '1'");
		
		if($results){
			unlink("$logo");
			$response = array(
				"result" => "SUCCESS",
				"filename" => $logo,
			);
		}else{
			$response = array(
				"result" => 'Error : ('. $mysqli->errno .') '. $mysqli->error,
			);
		}
		mysqli_close($mysqli);
	
		echo json_encode($response);
	} else header("Location: configuracion");

?>