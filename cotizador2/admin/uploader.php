<?php
	if (!empty($_FILES)) {
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$file_name = $_FILES['Filedata']['name'];	
		$targetPath = $_SERVER['DOCUMENT_ROOT'] . "/cotizador2/admin/archivos" . '/';		//$_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
		$targetFile =  str_replace('//','/',$targetPath) . $file_name;	
		$folder ='archivos/';
		$rutaarch = 'archivos/' . $file_name;
		if (move_uploaded_file($tempFile,$targetFile)){
			echo "<input type='hidden' name='rutaarch' id='rutaarch' value='".$rutaarch."' />";
			echo "<div class=\"alert alert-info\">ARCHIVO CARGADO CON EXITO!</div>";
		} else {
			echo "<div class=\"alert alert-danger\">ERROR AL CARGAR EL ARCHIVO!</div>";
		}
	}
?>