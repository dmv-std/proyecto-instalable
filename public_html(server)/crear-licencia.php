<?php
	if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['sitio'])) {

		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$sitio = $_POST['sitio'];
		$fecha_creacion = date('Y-m-d');
		$fecha_expiracion = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));

		$serial = "";
		for($i=0; $i<6; $i++)
			$serial .= substr(md5(microtime()), rand(0,26), 5)."-";
		$serial = rtrim($serial, "-");

		include("config.php");
		$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
		if($conn->connect_errno > 0){
			die('Unable to connect to database [' . $conn->connect_error . ']');
		}
		$conn->set_charset("utf8");

		$result = $conn->query("SELECT * FROM licencias WHERE sitio='$sitio'") or die($conn->error.__LINE__);
		if ($result->num_rows==0) {
			$stmt = $conn->prepare("INSERT INTO `licencias` (`nombre`, `apellido`, `serial`, `sitio`, `fecha_creacion`, `fecha_expiracion`, `activa`) VALUES (?, ?, '$serial', ?, '$fecha_creacion', '$fecha_expiracion', '1')");
		} else {
			$stmt = $conn->prepare("UPDATE `licencias` SET `nombre` = ?, `apellido` = ?, `serial` = '$serial', `fecha_creacion` = '$fecha_creacion', `fecha_expiracion` = '$fecha_expiracion', `activa` = '1' WHERE `licencias`.`sitio` = ?");
		}
		$stmt->bind_param("sss", $nombre, $apellido, $sitio);
		$stmt->execute() or die($stmt->error." - php line: ".__LINE__);
		$stmt->close();
		
		mysqli_close($conn);

		header("location: licencias-demo.php"); // If you need a success message to be displayed, you can use $_SESSION to show it, and then destroy that $_SESSION var, instead of using $_GET and ?msg=success+message
	}
?>