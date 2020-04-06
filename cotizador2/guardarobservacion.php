<?php
    session_start();
    include ("../config.php");
    
    if(isset($_GET['idcot'])){
        $idcotizacion = $_GET['idcot'];
        $observacion2 = utf8_decode($_GET['observaciones']);
				
        $fecha = date('Y-m-d H:i:s');
        $tipo = "OBSERVACION";
		if(isset($_SESSION['nombre'])){
			$usuario = $_SESSION['nombre'];
		}else{
			$usuario = "CLIENTE";
		}
        $observacion = $_SESSION['nombrePersona'].": ".$observacion2;
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $results = $mysqli->query("INSERT INTO cot2_observaciones (idcotizacion, observacion, fecha, tipo) values('$idcotizacion', '$observacion', '$fecha', '$tipo')");
        if($results){
            $msg = "La observacion fue agregada de manera exitosa!";
            echo $msg;
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
        
    }
?>