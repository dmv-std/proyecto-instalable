<?php
    session_start();
    if(empty($_SESSION['nombrePersona'])){
        header("location: login");
    } else {
        include ("config.php");
        
        $sistema = $_GET['sistema'];
        $idUsuario = $_GET['idUsuario'];

        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $query = "SELECT * FROM sist_usuarios WHERE id = '$idUsuario' ORDER BY id ASC";
        $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
        $row = $result->fetch_assoc();

        if($row[$sistema] == "1"){
            $results = $mysqli->query("UPDATE sist_usuarios SET ".$sistema." = 0 WHERE id = '$idUsuario'"); 
        }else{
            $results = $mysqli->query("UPDATE sist_usuarios SET ".$sistema." = 1 WHERE id = '$idUsuario'");
        } 

        $query2 = "SELECT * FROM sist_usuarios WHERE id = '$idUsuario' ORDER BY id ASC";
        $result2 = $mysqli->query($query2) or die($mysqli->error.__LINE__);
        $row2 = $result2->fetch_assoc();
        echo json_encode($row2);
        
        mysqli_close($mysqli);
     }
?>
