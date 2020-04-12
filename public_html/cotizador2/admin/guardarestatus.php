<?php
    include ("../../config.php");
    if(isset($_GET['estatus'])){
        session_start();
        
        $estatus = $_GET['estatus'];
        $idcotizacion = $_GET['idcotizacion'];
		$fecha = date('Y-m-d H:i:s');
        $tipo = "CAMBIO DE ESTATUS";
		$observacion = "El usuario ".$_SESSION['nombrePersona']." cambio el estatus de la cotizacion a CONCRETADO";
		
        if($estatus=="CONCRETADO"){
            $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }
            $results = $mysqli->query("UPDATE cot2_cotizacion SET estatus = '$estatus' WHERE id = '$idcotizacion'");
            if($results){
				$mysqlix = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
				if($mysqlix->connect_errno > 0){
					die('Unable to connect to database [' . $db->connect_error . ']');
				}
				$resultsx = $mysqlix->query("INSERT INTO cot2_observaciones (idcotizacion, observacion, fecha, tipo) values('$idcotizacion', '$observacion', '$fecha', '$tipo')");
				if($resultsx){
					$msg = "El estatus de la cotizacion fue actualizado de manera exitosa!";
                echo $msg;
				}else{
					echo 'Error : ('. $mysqlix->errno .') '. $mysqlix->error;
				}
				mysqli_close($mysqlix);
            }else{
                echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
            }
            mysqli_close($mysqli);
        }
    }
?>
