<?php
	if (!empty($_FILES) && isset($_POST['idpresupuesto'])) {
		
		$id_presupuesto = $_POST['idpresupuesto'];
		
		include ("../config.php");
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$mysqli->set_charset("utf8");

		$fecha = date('Y-m-d H:i:s');
		
		for ($i=0; $i<count($_FILES['archivos']['name']); $i++) {

			$tempFile = $_FILES['archivos']['tmp_name'][$i];
			$file = $id_presupuesto.'_'.$_FILES['archivos']['name'][$i];
			$targetFile = "$basepath/presupuestos/archivos/$file";

			$mimetype = $_FILES['archivos']['type'][$i];
			$size = $_FILES['archivos']['size'][$i];
			
			if (move_uploaded_file($tempFile,$targetFile)){
				$result = $mysqli->query("INSERT INTO presupuestos_archivos
					(id_presupuesto, file, mimetype, size, fecha)
					values('$id_presupuesto', '$file', '$mimetype', '$size', '$fecha')");
				if(!$result){
					print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
				}
			}
		}
		mysqli_close($mysqli);
	}
?>