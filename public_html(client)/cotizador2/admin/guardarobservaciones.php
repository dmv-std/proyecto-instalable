<?php
     session_start();
    include ("../../config.php");
    
    if(isset($_GET['idcotizacion'])){
        $idcotizacion = $_GET['idcotizacion'];
        $id_user = isset($_GET['iduser']) ? $_GET['iduser'] : 0;
        $observacion2 = utf8_decode($_GET['observacion']);
        $fecha = date('Y-m-d H:i:s');
        $tipo = utf8_decode($_GET['tipo']);
        $observacion = $_SESSION['nombre'].": ".$observacion2;
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