<?php
    if(isset($_GET['id'])){
        $idform = $_GET['id'];
		$cantidad = $_GET['cantidad'];
		$cantidadcolor = isset($_GET['colores']) ? $_GET['colores'] : 0;
        include ("../config.php");
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $query = "SELECT * FROM cot2_productos WHERE id = '$idform'";
        $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
        $row = $result->fetch_assoc();
		$codigo=$row['codigo'];
		$descripcion=$row['descripcion'];
		$precioprod=$row['cantidades100'];
		$colores = 1;
	
		$mysqli2 = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli2->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $query2 = "SELECT * FROM cot2_colores WHERE id = '$colores'";
        $result2 = $mysqli2->query($query2) or die($mysqli2->error.__LINE__);
        $row2 = $result2->fetch_assoc();
		if($cantidad<100){
			$preciocolor=$cantidadcolor*$row2['preciounitario'];
			$precioprod=$preciocolor+$row['preciounitario'];
			$total=$precioprod*$cantidad;
		}elseif($cantidad<200){
			$preciocolor=$cantidadcolor*$row2['cantidades100'];
			$precioprod=$preciocolor+$row['cantidades100'];
			$total=$precioprod*$cantidad;
		}elseif($cantidad<500){
			$preciocolor=$cantidadcolor*$row2['cantidades200'];
			$precioprod=$preciocolor+$row['cantidades200'];
			$total=$precioprod*$cantidad;
		}elseif($cantidad<1000){
			$preciocolor=$cantidadcolor*$row2['cantidades500'];
			$precioprod=$preciocolor+$row['cantidades500'];
			$total=$precioprod*$cantidad;			
		}elseif($cantidad<5000){
			$preciocolor=$cantidadcolor*$row2['cantidades1000'];
			$precioprod=$preciocolor+$row['cantidades1000'];
			$total=$precioprod*$cantidad;			
		}elseif($cantidad<10000){
			$preciocolor=$cantidadcolor*$row2['cantidades5000'];
			$precioprod=$preciocolor+$row['cantidades5000'];
			$total=$precioprod*$cantidad;			
		}else{
			$preciocolor=$cantidadcolor*$row2['cantidades10000'];
			$precioprod=$preciocolor+$row['cantidades10000'];
			$total=$precioprod*$cantidad;
		}
		mysqli_close($mysqli);
		$miArray = array("producto"=>"$precioprod", "total"=>"$total", "codigo"=>"$codigo", "descripcion"=>"$descripcion");
		echo json_encode($miArray);
		
    }
?>