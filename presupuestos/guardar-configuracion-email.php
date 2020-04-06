<?php
    include ("../config.php");
    
    if(isset($_GET['emailremitente'])&&isset($_GET['emailconf'])&&isset($_GET['asuntoemail'])
		&&isset($_GET['mensajeprincipal'])&&isset($_GET['mensajefinal'])&&isset($_GET['mensajeintermedio'])&&isset($_GET['mail_logo']))
	{
        $emailremitente = $_GET['emailremitente'];
        $emailconf = $_GET['emailconf'];
        $asuntoemail = $_GET['asuntoemail'];
        $mensajeprincipal = $_GET['mensajeprincipal'];
        $mensajeintermedio = $_GET['mensajeintermedio'];
        $mensajefinal = $_GET['mensajefinal'];
        $mail_logo = $_GET['mail_logo'];
		
        $mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno > 0){
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
        $mysqli->set_charset("utf8");
        
		$results = $mysqli->query("UPDATE presupuestos_configuracion
			SET emailremitente = '$emailremitente',
				emailconf = '$emailconf',
				asuntoemail = '$asuntoemail',
				mensajeprincipal = '$mensajeprincipal',
				mensajeintermedio = '$mensajeintermedio',
				mensajefinal = '$mensajefinal',
				mail_logo = '$mail_logo'
			WHERE id = '1'");
        
		if($results){
            $msg = "Las Configuraciones fueron actualizadas de manera exitosa!";
            echo $msg;
        }else{
            echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
        }
        mysqli_close($mysqli);
    }
?>