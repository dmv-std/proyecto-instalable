<?php

include ("../../config.php");

if(isset($_GET['accion'])){
    $accion = $_GET['accion'];
    if($accion=="eliminar"){
        $idrubro = $_GET['id'];
    }elseif($accion=="modificar"){
        $idrubro = $_GET['id'];
        $descripcion = utf8_decode($_GET['descripcion']);
    }elseif($accion=="crear"){
        $descripcion = utf8_decode($_GET['descripcion']);
    }
    if($accion=="crear"){
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $insert_row = $mysqli->query("INSERT INTO cot2_rubros (descripcion) values('$descripcion')");
        if($insert_row){
            $msg = "El rubro fue agregado de manera exitosa!";
            echo $msg;
        }else{
            print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
    }elseif($accion=="modificar"){
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $results = $mysqli->query("UPDATE cot2_rubros SET descripcion = '$descripcion' WHERE id = '$idrubro'");
        if($results){
            $msg = "El rubro fue actualizado de manera exitosa!";
            echo $msg;
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
    }elseif($accion=="eliminar"){
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $results = $mysqli->query("DELETE FROM cot2_rubros WHERE id = '$idrubro'");
        if($results){
            $msg = "El rubro fue eliminado de manera exitosa!";
            echo $msg;
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
    }
}
?>
