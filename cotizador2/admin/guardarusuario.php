<?php

    include ("../../config.php");
    
    if(isset($_GET['accion'])){
        $accion = $_GET['accion'];
        if($accion=="eliminar"){
            $idusuario = $_GET['idusuario'];
        }elseif($accion=="modificar"){
            $idusuario = $_GET['idusuario'];
            $nombre = utf8_decode($_GET['nombre']);
            $user = $_GET['user'];
            $pass = $_GET['pass'];
            $correo = $_GET['correo'];
            $permisos = $_GET['permisos'];
        }elseif($accion=="agregar"){
            $nombre = utf8_decode($_GET['nombre']);
            $user = $_GET['user'];
            $pass = $_GET['pass'];
            $correo = $_GET['correo'];
            $permisos = $_GET['permisos'];
        }
        if($accion=="agregar"){
            $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }
            $insert_row = $mysqli->query("INSERT INTO cot2_usuarios (nombre, user, pass, correo, permisos) values('$nombre', '$user', '$pass', '$correo', '$permisos')");
            if($insert_row){
                $msg = "El usuario fue agregado de manera exitosa!";
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
            $results = $mysqli->query("UPDATE cot2_usuarios SET nombre = '$nombre', user = '$user', pass = '$pass', correo = '$correo', permisos = '$permisos' WHERE id = '$idusuario'");
            if($results){
                $msg = "El usuario fue actualizado de manera exitosa!";
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
            $results = $mysqli->query("DELETE FROM cot2_usuarios WHERE id = '$idusuario'");
            if($results){
                $msg = "El usuario fue eliminado de manera exitosa!";
                echo $msg;
            }else{
                echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
            }
            mysqli_close($mysqli);
        }
    }
?>
