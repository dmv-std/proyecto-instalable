<?php
    session_start();
    if(empty($_SESSION['nombrePersona'])){
        header("location: login");
    } else {
        if(isset($_GET['id'])){
            $idusuario = $_GET['id'];
            include ("config.php");
            $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            /* check connection */
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }
            $query = "SELECT * FROM sist_usuarios WHERE id = '$idusuario' ORDER BY id ASC";
            $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
            $row = $result->fetch_assoc();
            echo json_encode($row);
            mysqli_close($mysqli);
        }
    }
?>
