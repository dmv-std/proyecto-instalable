<?php
	if (!empty($_FILES)) {
		include("../../config.php");

		$tempFile = $_FILES['Filedata']['tmp_name'];
		$file_name = $_FILES['Filedata']['name'];
		$targetPath = $basepath."/".$cotizador2_archivos_path;
		$targetFile = $targetPath.'/'.$file_name;
		
		$rutaarch = $basehttp.'/'.$cotizador2_archivos_path.'/'.$file_name;
		
		if (!file_exists($targetPath))
			mkdir($targetPath);
		
		if (move_uploaded_file($tempFile,$targetFile)){
			echo "<input type='hidden' name='rutaarch' id='rutaarch' value='".$rutaarch."' />";
			echo "<div class=\"alert alert-info\">ARCHIVO CARGADO CON EXITO!</div>";
		} else {
			echo "<div class=\"alert alert-danger\">ERROR AL CARGAR EL ARCHIVO!</div>";
		}
	}
?>