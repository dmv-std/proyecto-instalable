<?php session_start();

    include ("config.php");
    
    if(isset($_GET['accion'])){
        $accion = $_GET['accion'];
        if($accion=="eliminar"){
            $idenlace = $_GET['idenlace'];
        }elseif($accion=="modificar"){
            $idenlace = $_GET['idenlace'];
            $enlace = $_GET['enlace'];
            $titulo = $_GET['titulo'];
			$comentario = $_GET['comentario'];
			$orden = $_GET['orden'];
			$tipo = $_GET['tipo'];
			$color = $_GET['color'];
			
        }elseif($accion=="agregar"){
            $enlace = $_GET['enlace'];
            $titulo = $_GET['titulo'];
			$orden = $_GET['orden'];
			$comentario = $_GET['comentario'];
			$color = $_GET['color'];
			$tipo = $_GET['tipo'];
			$usuario = $_SESSION['idPersona'];
        }
        if($accion=="agregar"){
            $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }
            $mysqli->set_charset("utf8");
            $insert_row = $mysqli->query("INSERT INTO sist_enlaces (enlace, titulo, comentario, orden, color, tipo, usuario) values('$enlace', '$titulo', '$comentario', '$orden', '$color', '$tipo', '$usuario')");
            if($insert_row){
                $msg = "El enlace fue agregado de manera exitosa!";
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
            $mysqli->set_charset("utf8");
            $results = $mysqli->query("UPDATE sist_enlaces SET enlace = '$enlace', titulo = '$titulo', comentario = '$comentario', orden = '$orden', color = '$color', tipo = '$tipo' WHERE id = '$idenlace'");
            if($results){
                $msg = "El enlace fue actualizado de manera exitosa!";
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
            $results = $mysqli->query("DELETE FROM sist_enlaces WHERE id = '$idenlace'");
            if($results){
                $msg = "El enlace fue eliminado de manera exitosa!";
                echo $msg;
            }else{
                echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
            }
            mysqli_close($mysqli);
        }
    }
?>