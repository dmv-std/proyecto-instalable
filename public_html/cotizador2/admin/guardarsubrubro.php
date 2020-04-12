<?php
include ("../../config.php");
if(isset($_GET['accion'])){
    $accion = $_GET['accion'];
    if($accion=="eliminar"){
        $idsubrubro = $_GET['id'];
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $results = $mysqli->query("DELETE FROM cot2_subrubros WHERE id = '$idsubrubro'");
        if($results){
            $msg = "El subrubro fue eliminado de manera exitosa!";
            echo $msg;
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
    }elseif($accion=="modificar"){
        $idsubrubro = $_GET['id'];
        $descripcion = utf8_decode($_GET['descripcion']);
        $rubro = $_GET['rubrox'];
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $results = $mysqli->query("UPDATE cot2_subrubros SET descripcion = '$descripcion', idrubro = '$rubro' WHERE id = '$idsubrubro'");
        if($results){
            $msg = "El subrubro fue actualizado de manera exitosa!";
            echo $msg;
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
    }elseif($accion=="crear"){
        $descripcion = utf8_decode($_GET['descripcion']);
        $rubro = $_GET['rubrox'];
		$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $insert_row = $mysqli->query("INSERT INTO cot2_subrubros (descripcion, idrubro) values('$descripcion','$rubro')");
        if($insert_row){
            $msg = "El subrubro fue agregado de manera exitosa!";
            echo $msg;
        }else{
            print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
    }
}
?>
