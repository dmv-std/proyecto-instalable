<?php

	header('Content-type: application/javascript; charset=utf-8');
	$callback = isset($_GET['callback']) ? $_GET['callback'] : "_generic_callback";

	if (!isset($_GET['license']))
		die("typeof ".$callback."==='function' && ".$callback."(".json_encode(['error' => "Falta la licencia!"]).");");

	include ("../config.php");
	include("$basepath/assets/functions/validar-licencia.php");
	$validar = validarLicencia( $_GET['license'] );
	
	if ($validar['error'])
		$data = ['error' => $validar['error']];

	else if(isset($_GET['archivo'])){
		
		// PHPExcel ha quedado en desuso, pero parece tener un sucesor:
		// https://www.ribosomatic.com/articulos/phpexcel-libreria-php-para-leer-y-escribir-archivos-de-excel/
		
		$archivo = $_GET['archivo'];
		$arch_nombre = preg_replace("/.*\/(.+?)/", '\1', $archivo);
		file_put_contents("$basepath/assets/cotizador2/$arch_nombre", file_get_contents($archivo));
		$archivo = "$basepath/assets/cotizador2/$arch_nombre";

		$rest = substr($archivo, -4);
		if($rest ==".xls"){
			require_once($basepath."/librerias/Classes/PHPExcel/Reader/Excel5.php");
			$objReader = new PHPExcel_Reader_Excel5();			
		}elseif($rest =="xlsx"){
			require_once($basepath."/librerias/Classes/PHPExcel.php");
			$objReader = new PHPExcel_Reader_Excel2007();
		}

		$objPHPExcel = $objReader->load($archivo);
		$objPHPExcel->setActiveSheetIndex(0);
		$i=2;

		$response = array();

		while($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() != ''){
			$response[] = [
				'codigo' => $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue(),
				'descripcion' => $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue(),
				'medida' => $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue(),
				'espesor' => $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue(),
				'packaging' => $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue(),
				'cantidadminima' => $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue(),
				'preciounitario' => number_format($objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue(),2,".",""),
				'cantidades100' => number_format($objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue(),2,".",""),
				'cantidades200' => number_format($objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue(),2,".",""),
				'cantidades500' => number_format($objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue(),2,".",""),
				'cantidades1000' => number_format($objPHPExcel->getActiveSheet()->getCell("M".$i)->getValue(),2,".",""),
				'cantidades5000' => number_format($objPHPExcel->getActiveSheet()->getCell("N".$i)->getValue(),2,".",""),
				'cantidades10000' => number_format($objPHPExcel->getActiveSheet()->getCell("O".$i)->getValue(),2,".",""),
				'rubro' => $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue(),
				'subrubro' => $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue(),
			];
			$i++;
		}
		!unlink($archivo);
		$data = [
			'response'	=> $response,
			'error'		=> '',
		];

	} else $data = ['error' => "Error 400 Bad Request"];

	echo "typeof ".$callback."==='function' && ".$callback."(".json_encode($data).");";
?>