<?php
	
	if (isset($_GET['id']) && isset($_GET['filename']) ) {
		$id = $_GET['id'];
		$filename = $_GET['filename'];
		
		include ("config.php");
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$results = $mysqli->query("UPDATE sist_usuarios SET firma = '' WHERE id = '$id'");
		
		if($results){
			if(file_exists($basepath."/".$firmas_path."/".$filename))
				unlink($basepath."/".$firmas_path."/".$filename);
			echo "SUCCESS";
		}else{
			echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
	} else {
		echo 'Datos incorrectos en la estructura html de la página.
Póngase en contacto con el administrador.';
	}

?>