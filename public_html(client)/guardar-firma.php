<?php
	
	if (isset($_POST['idusuario'])) {
		$id = $_POST['idusuario'];
		
		if ( 0 < $_FILES['firma']['error'] ) {
			$response = array(
				"result" => 'Error: ' . $_FILES['firma']['error']
			);
		}
		else {
			include ("config.php");

			$upload_path = $basepath.'/'.$firmas_path;
			$filename = $id;
			
			$explode = explode(".", $_FILES['firma']['name']);
			$extension = $explode[count($explode) - 1];
			
			include ("config.php");
			
			$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			if($mysqli->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$results = $mysqli->query("UPDATE sist_usuarios SET firma = '$filename.$extension' WHERE id = '$id'");
			
			if($results){
				move_uploaded_file($_FILES['firma']['tmp_name'], "$upload_path/$filename.$extension");
				
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
	} else {
		$response = array(
			"result" => 'Datos incorrectos en la estructura html de la página. Falta la Id del usuario.
Póngase en contacto con el administrador.',
		);
	}
		
	echo json_encode($response);
?>
