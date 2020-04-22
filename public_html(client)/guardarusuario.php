<?php

    include ("config.php");
    
    if(isset($_GET['accion'])){
        $accion = $_GET['accion'];
        if($accion=="eliminar"){
            $idusuario = $_GET['idusuario'];
        }elseif($accion=="modificar"){
            $idusuario = $_GET['idusuario'];
            $nombre = $_GET['nombre'];
            $apellido = $_GET['apellido'];
            $correo = $_GET['correo'];
            $telefono = $_GET['telefono'];
            $user = $_GET['user'];
            $pass = $_GET['pass'];
            $permisos = $_GET['permisos'];
			$id_empleado = $_GET['id_empleado'];
        }elseif($accion=="agregar"){
            $nombre = $_GET['nombre'];
            $apellido = $_GET['apellido'];
            $correo = $_GET['correo'];
            $telefono = $_GET['telefono'];
            $user = $_GET['user'];
            $pass = $_GET['pass'];
            $permisos = $_GET['permisos'];
			$id_empleado = $_GET['id_empleado'];
        }
        if($accion=="agregar"){
            $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            $pass = md5($pass);

            $insert_row = $mysqli->query("INSERT INTO sist_usuarios (nombre, user, pass, permisos, email, apellido, id_empleado, telefono) values('$nombre', '$user', '$pass', '$permisos', '$correo', '$apellido', '$id_empleado', '$telefono')");
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
            if($pass != ""){
                $pass = md5($pass);
                $results = $mysqli->query("UPDATE sist_usuarios SET nombre = '$nombre', apellido = '$apellido', email = '$correo', user = '$user', pass = '$pass', permisos = '$permisos', id_empleado = '$id_empleado', telefono = '$telefono' WHERE id = '$idusuario'");
            }else{
                $results = $mysqli->query("UPDATE sist_usuarios SET nombre = '$nombre', apellido = '$apellido', email = '$correo', user = '$user', permisos = '$permisos', id_empleado = '$id_empleado', telefono = '$telefono' WHERE id = '$idusuario'");
            }
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
            $results = $mysqli->query("DELETE FROM sist_usuarios WHERE id = '$idusuario'");
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
