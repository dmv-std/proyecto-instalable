<?php
    if(isset($_GET['id'])){
        $idusuario = $_GET['id'];
        include ("config.php");
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        /* check connection */
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
		$mysqli->set_charset("utf8");
        $query = "SELECT * FROM sist_usuarios WHERE id = '$idusuario' ORDER BY id ASC";
        $result = $mysqli->query($query) or die($mysqli->error.__LINE__);
        $row = $result->fetch_assoc();
		$usuario = $row;
		
		$usuario['empleado'] = "";
		
		if ($usuario['id_empleado'] != 0) {
			$id_empleado = $usuario['id_empleado'];
						
			$query = "SELECT * FROM fich_empleados WHERE id = '$id_empleado'";
			$result = $mysqli->query($query) or die($mysqli->error.__LINE__);
			$row = $result->fetch_assoc();
			$empleado = $row;
			
			$usuario['empleado'] = (count($empleado)>0) ? $empleado['nombre'] : "";
		}

		// Cerrando la conexión con la base de datos
		mysqli_close($mysqli);
		
        echo json_encode($usuario);
    }
?>
