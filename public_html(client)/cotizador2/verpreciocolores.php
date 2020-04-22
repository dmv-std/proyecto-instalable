<?php
        $idform = 7;
        include ("../config.php");
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $query = "SELECT * FROM cot2_colores WHERE id = '$idform'";
        $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
        $row = $result->fetch_assoc();
        echo json_encode($row);
        mysqli_close($mysqli);
?>