<?php
set_time_limit(3000);
ini_set("memory_limit","50M");
	$rutaarch = 'catmax.xlsx';
	require_once("../Classes/PHPExcel.php");
	$objReader = new PHPExcel_Reader_Excel2007();
	$objPHPExcel = $objReader->load($rutaarch);
	$objPHPExcel->setActiveSheetIndex(0);
	$i=1;
	require_once ("../../config.php");
	
	header("Content-Type: text/html;charset=utf-8");
	echo "<html>";
	echo "<head>";
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/> ";
	echo "</head>";
	echo "<body>";

	echo " INICIO<br />";
	$moneda = 2;
	while($i <= 2316){
		$num = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
		$marca = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
		$codigo = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
		$totalvalor = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
		$refe01 = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
		$refe02 = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
		$pais = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
		$foto = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
		
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($mysqli->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$insert_row = $mysqli->query("INSERT INTO cot2_catalytics (cat_code,cat_ref1,cat_ref2,cat_value,cur_id,ctr_id,bnd_id) values ('$codigo','$refe01','$refe02','$totalvalor','$moneda','$pais','$marca')");
		if($insert_row){
			$idinsertado = $mysqli->insert_id;
			$insert_row2 = $mysqli->query("INSERT INTO cot2_catalytics_images (cat_id,cai_path) VALUES('$idinsertado','$foto')");
			if($insert_row2){
				echo "INSERTADO ".$idinsertado."<br />";
			}else{
			    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
			}
			$i++;
		}else{
			print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
		}
		mysqli_close($mysqli);
	}

	
	echo "FIN<br />";
	echo "</body>";
	echo "</html>";
?>