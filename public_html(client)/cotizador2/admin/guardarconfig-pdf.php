<?php include ("../../config.php");
	
	// Guardar logo
	if ( isset($_FILES['logo']) && isset($_POST['action']) && $_POST['action']=='store' ) {
		
		if ( 0 < $_FILES['logo']['error'] ) {
			$response = array(
				"result" => 'Error: ' . $_FILES['logo']['error']
			);
		}
		else {
			
			$cotizador2_pdf_logo = explode("/", $cotizador2_pdf_logo);
			$filename = array_pop($cotizador2_pdf_logo);
			$cotizador2_pdf_logo = implode("/", $cotizador2_pdf_logo);

			$upload_path = $basepath.$cotizador2_pdf_logo;
			
			if (!file_exists($upload_path))
				mkdir($upload_path);
			
			$explode = explode(".", $_FILES['logo']['name']);
			$extension = $explode[count($explode) - 1];
			
			$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			if($mysqli->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$mysqli->set_charset("utf8");
			
			$results = $mysqli->query("
				UPDATE cot2_configuraciones
				SET logo = '$filename.$extension'
				WHERE id = '1'");
			
			if($results){
				move_uploaded_file($_FILES['logo']['tmp_name'], "$upload_path/$filename.$extension");
				
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
	else if ( isset($_GET['logo']) && isset($_GET['action']) && $_GET['action']=='delete' ) {
		
		$cotizador2_pdf_logo = explode("/", $cotizador2_pdf_logo);
		array_pop($cotizador2_pdf_logo);
		$cotizador2_pdf_logo = implode("/", $cotizador2_pdf_logo);

		$logo = $basepath."/".$cotizador2_pdf_logo."/".$_GET['logo'];
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		
		$results = $mysqli->query("UPDATE cot2_configuraciones SET logo = '' WHERE id = '1'");
		
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
	}
	
	// Guardar datos básicos de la empresa
	else if ( isset($_GET['empresa']) && isset($_GET['direccion']) && isset($_GET['telefonos']) && isset($_GET['web']) && isset($_GET['email']) && isset($_GET['titulo_pdf']) ){
		
		$empresa = $_GET['empresa'];
		$direccion = $_GET['direccion'];
		$telefonos = $_GET['telefonos'];
		$web = $_GET['web'];
		$email = $_GET['email'];
		$titulo_pdf = $_GET['titulo_pdf'];

		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");
		
		$results = $mysqli->query("UPDATE cot2_configuraciones
			SET empresa = '$empresa',
				direccion = '$direccion',
				telefonos = '$telefonos',
				web = '$web',
				email = '$email',
				`titulo-pdf` = '$titulo_pdf'
			WHERE id = 1");

		if($results) {
			echo "La configuración se ha guardado con éxito!";
		}else{
			echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
		
	} else header("Location: configuracion");

?>