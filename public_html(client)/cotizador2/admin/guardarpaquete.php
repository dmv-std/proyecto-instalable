<?php 

if(isset($_GET['data']) && isset($_GET['file'])){
	include ("../../config.php");
		
	$archivo = $basepath.'/'.$cotizador2_archivos_path.'/'.$_GET['file'];
	$data = json_decode($_GET['data'], true);

	$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	foreach ($data as $elem) {
		foreach ($elem as $label => $variable)
			$GLOBALS[$label] = $variable;

		$rubro = traerrubro($rubro);
		$subrubro = traersubrubro($subrubro, $rubro);

		$result = $mysqli->query("SELECT * FROM cot2_productos WHERE codigo = '$codigo'");
		$row_cnt = $result->num_rows;
		if($row_cnt<1){
			$insert_row = $mysqli->query("INSERT INTO cot2_productos (codigo, descripcion, rubro, subrubro, medida, espesor, packaging, preciounitario, cantidades100, cantidades200, cantidades500, cantidades1000, cantidades5000, cantidades10000, cantidadminima) values ('$codigo', '$descripcion', '$rubro', '$subrubro', '$medida', '$espesor', '$packaging', '$preciounitario', '$cantidades100', '$cantidades200', '$cantidades500', '$cantidades1000', '$cantidades5000', '$cantidades10000', '$cantidadminima')");
			if(!$insert_row){
				print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
			}
		} else {
			$update_row = $mysqli->query("UPDATE cot2_productos SET descripcion='$descripcion',rubro='$rubro',subrubro='$subrubro',medida='$medida',espesor='$espesor',packaging='$packaging',preciounitario='$preciounitario',cantidades100='$cantidades100',cantidades200='$cantidades200',cantidades500='$cantidades500',cantidades1000='$cantidades1000',cantidades5000='$cantidades5000',cantidades10000='$cantidades10000',cantidadminima='$cantidadminima'WHERE cot2_productos.codigo = '$codigo'");
			if(!$update_row){
				print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
			}
		}
	}
	mysqli_close($mysqli);

	if (!unlink($archivo)){
		echo 'no se pudo borrar el archivo :'.$archivo;
	}else{	
		echo "Productos Agregados de manera exitosa!";
	}
}

function traerrubro($descripcion){
	include ("../../config.php");
	$mysqlix = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if (mysqli_connect_errno()) {
		die("Conexión fallida: ".mysqli_connect_error());
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
		die("Conexión fallida: ".mysqli_connect_error());
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
?>