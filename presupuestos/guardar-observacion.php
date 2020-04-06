<?php
    session_start();
    if(isset($_GET['id_presupuesto'])){
		include ("../config.php");
		
        $id_presupuesto = $_GET['id_presupuesto'];
        $observacion = $_GET['observacion'];
		
        $fecha = date('Y-m-d H:i:s');
        $tipo = $_GET['tipo'] ? $_GET['tipo'] : "OBSERVACION";
		//$usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "CLIENTE";
        $observacion = $_SESSION['nombrePersona'].": ".$observacion;
		$id_user = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
		$mysqli->set_charset("utf8");
        $results = $mysqli->query("INSERT INTO presupuestos_observaciones (id_presupuesto, observacion, fecha, tipo, id_user) values('$id_presupuesto', '$observacion', '$fecha', '$tipo', '$id_user')");
        if($results){
            $msg = "La observacion fue agregada de manera exitosa!";
            echo $msg;
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
    }
?>