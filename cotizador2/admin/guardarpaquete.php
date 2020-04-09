<?php 
	include ("../../config.php");
	if(isset($_GET['archivo'])){
		
		// PHPExcel ha quedado en desuso, pero parece tener un sucesor:
		// https://www.ribosomatic.com/articulos/phpexcel-libreria-php-para-leer-y-escribir-archivos-de-excel/
		
		$archivo = $_GET['archivo'];
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
		// $datos = array();
		function traerrubro($descripcion){
			include ("../../config.php");
			$mysqlix = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			if (mysqli_connect_errno()) {
				printf("Conexión fallida: %s\n", mysqli_connect_error());
				exit();
			}
			if ($result = $mysqlix->query("SELECT * FROM cot2_rubros WHERE descripcion = '$descripcion'")) {
				$row_cnt = $result->num_rows;
				if($row_cnt<1){
					$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
					if($mysqli->connect_errno > 0){
						die('Unable to connect to database [' . $db->connect_error . ']');
					}
					$insert_row = $mysqli->query("INSERT INTO cot2_rubros (descripcion) values('$descripcion')");
					if($insert_row){
						return $mysqli->insert_id;
					}else{
						print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
					}
					mysqli_close($mysqli);
				}else{
					$row = $result->fetch_assoc();
					return $row['id'];
				}
				$result->close();
			}
			$mysqlix->close();
		} 
		function traersubrubro($descripcion, $rubro){
			include ("../../config.php");
			$mysqlix = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			if (mysqli_connect_errno()) {
				printf("Conexión fallida: %s\n", mysqli_connect_error());
				exit();
			}
			if ($result = $mysqlix->query("SELECT * FROM cot2_subrubros WHERE descripcion = '$descripcion' AND idrubro = '$rubro'")) {
				$row_cnt = $result->num_rows;
				if($row_cnt<1){
					$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
					if($mysqli->connect_errno > 0){
						die('Unable to connect to database [' . $db->connect_error . ']');
					}
					$insert_row = $mysqli->query("INSERT INTO cot2_subrubros (descripcion, idrubro) values('$descripcion', '$rubro')");
					if($insert_row){
						return $mysqli->insert_id;
					}else{
						print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
					}
					mysqli_close($mysqli);
				}else{
					$row = $result->fetch_assoc();
					return $row['id'];
				}
				$result->close();
			}
			$mysqlix->close();
		}
		while($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue() != ''){	
			$codigo = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
			$descripcion = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
			$medida = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
			$espesor = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
			$packaging = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
			$cantidadminima = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
			$preciounitario = number_format($objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue(),2,".","");
			$cantidades100 = number_format($objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue(),2,".","");
			$cantidades200 = number_format($objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue(),2,".","");
			$cantidades500 = number_format($objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue(),2,".","");
			$cantidades1000 = number_format($objPHPExcel->getActiveSheet()->getCell("M".$i)->getValue(),2,".","");
			$cantidades5000 = number_format($objPHPExcel->getActiveSheet()->getCell("N".$i)->getValue(),2,".","");
			$cantidades10000 = number_format($objPHPExcel->getActiveSheet()->getCell("O".$i)->getValue(),2,".","");
			$rubro1 = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
			$rubro = traerrubro($rubro1);
			$subrubro1 = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
			$subrubro = traersubrubro($subrubro1,$rubro);
			
			$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
			if($mysqli->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
			}
			$result = $mysqli->query("SELECT * FROM cot2_productos WHERE codigo = '$codigo'");
			$row_cnt = $result->num_rows;
			if($row_cnt<1){
				$insert_row = $mysqli->query("INSERT INTO cot2_productos (codigo, descripcion, rubro, subrubro, medida, espesor, packaging, preciounitario, cantidades100, cantidades200, cantidades500, cantidades1000, cantidades5000, cantidades10000, cantidadminima) values ('$codigo', '$descripcion', '$rubro', '$subrubro', '$medida', '$espesor', '$packaging', '$preciounitario', '$cantidades100', '$cantidades200', '$cantidades500', '$cantidades1000', '$cantidades5000', '$cantidades10000', '$cantidadminima')");
				if($insert_row){
					$i++;
				}else{
					print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
				}
			} else {
				$update_row = $mysqli->query("UPDATE cot2_productos SET descripcion='$descripcion',rubro='$rubro',subrubro='$subrubro',medida='$medida',espesor='$espesor',packaging='$packaging',preciounitario='$preciounitario',cantidades100='$cantidades100',cantidades200='$cantidades200',cantidades500='$cantidades500',cantidades1000='$cantidades1000',cantidades5000='$cantidades5000',cantidades10000='$cantidades10000',cantidadminima='$cantidadminima'WHERE cot2_productos.codigo = '$codigo'");
				if($update_row){
					$i++;
				}else{
					print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
				}
			}
            mysqli_close($mysqli);
			
		}
		if (!unlink($archivo)){
			echo 'no se pudo borrar el archivo :'.$archivo;
		}else{	
			echo "Productos Agregados de manera exitosa!";
		}
	}
?>