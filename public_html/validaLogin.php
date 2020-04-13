<?php
// coge la librería recaptcha
require_once "recaptchalib.php";
require_once "config.php";

// tu clave secreta
$secret = $captcha_back_code;
 
// respuesta vacía
$response = null;
 
// comprueba la clave secreta
$reCaptcha = new ReCaptcha($secret);

// si se detecta la respuesta como enviada
if ($_POST["g-recaptcha-response"]) {
$response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

if ($response != null && $response->success) {

	session_start();

	function getUserIP()
	{
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }

	    return $ip;
	}

	$ipRemote = getUserIP();
	$details = json_decode(file_get_contents("http://ipinfo.io/{$ipRemote}"));

	if(isset($_POST['username'])) $usuario = $_POST['username'];
	if(isset($_POST['password'])) $pass = str_replace(md5(date("d")), "", $_POST['password']);

	$conexion = new mysqli();
	$conexion->connect($dbserver, $dbuser, $dbpass, $dbname);
	if($conexion->connect_error){
		die("No hubo conexión: ".$conexion->connect_error);
	}
	 
	$sentenciaSQL = "SELECT * FROM sist_usuarios where user='$usuario' AND pass='$pass' ";
	 
	$resultados = $conexion->query($sentenciaSQL);

	if($resultados->num_rows>0){
		
		$fecha = date('Y-m-d H:i:s');
		$ip = $details->ip;
		$hostname = isset($details->hostname) ? $details->hostname : '';
		$city = isset($details->city) ? $details->city : '';
		$region = isset($details->region) ? $details->region : '';
		$country = isset($details->country) ? $details->country : '';
		$loc = isset($details->loc) ? $details->loc : '';
		$org = isset($details->org) ? $details->org : '';

		$guardarRegistroenacceso = "INSERT INTO sist_usersloginacceso (username, fecha, acceso, ip, hostname, city, region, country, loc, org) values('$usuario', '$fecha', '1', '$ip', '$hostname', '$city', '$region', '$country', '$loc', '$org')";	
		$conexion->query($guardarRegistroenacceso);

		while($registros = $resultados->fetch_array()){
		
			$iduser = $registros['id'];
			$_SESSION['idPersona'] = $iduser;
	        $_SESSION['id'] = $iduser;
			
			$nombre = $registros['nombre'];
			$_SESSION['nombrePersona'] = $nombre;
	        $_SESSION['nombre'] = $nombre;
			
			$usuario = $registros['user'];
			$_SESSION['userPersona'] = $usuario;
			
			$usuario = $registros['email'];
			$_SESSION['emailPersona'] = $usuario;
	        $_SESSION['email'] = $usuario;

			$idCal = $registros['idCal'];
			$_SESSION['idCalPersona'] = $idCal;
	        $_SESSION['idCal'] = $idCal;
			
			$permisos = $registros['permisos'];			
			$_SESSION['permisosPersona'] = $permisos;
	        $_SESSION['permisos'] = $permisos;

			$rolCal = $registros['rolCal'];			
			$_SESSION['rolCalPersona'] = $rolCal;
	        $_SESSION['rolCal'] = $rolCal;

			$rolPag = $registros['rolPag'];			
			$_SESSION['rolPagPersona'] = $rolPag;
	        $_SESSION['rolPag'] = $rolPag;

			$rolPro = $registros['rolPro'];			
			$_SESSION['rolProPersona'] = $rolPro;
	        $_SESSION['rolPro'] = $rolPro;

	        $_SESSION['calendario'] = $registros['calendario'];
	        $_SESSION['chat'] = $registros['chat'];
	        $_SESSION['cotizador'] = $registros['cotizador'];
	        $_SESSION['crm'] = $registros['crm'];
	        $_SESSION['formularios'] = $registros['formularios'];
	        $_SESSION['listas'] = $registros['listas'];
	        $_SESSION['outs'] = $registros['outs'];
	        $_SESSION['pagos'] = $registros['pagos'];
	        $_SESSION['produccion'] = $registros['produccion'];
	        $_SESSION['presupuestos'] = $registros['presupuestos'];
	        $_SESSION['respuestas'] = $registros['respuestas'];
	        $_SESSION['stock'] = $registros['stock'];
	        $_SESSION['certificados'] = $registros['certificados'];	
	        $_SESSION['cotizador2'] = $registros['cotizador2'];	
	        $_SESSION['rrhh'] = $registros['rrhh'];	
	                     
			header ("Location: $basehttp");
		
		}

	}else{
		$fecha = date('Y-m-d H:i:s');
		$ip = $details->ip;
		$hostname = isset($details->hostname) ? $details->hostname : '';
		$city = isset($details->city) ? $details->city : '';
		$region = isset($details->region) ? $details->city : '';
		$country = isset($details->country) ? $details->country : '';
		$loc = isset($details->loc) ? $details->loc : '';
		$org = isset($details->org) ? $details->org : '';

		$guardarRegistroenacceso = "INSERT INTO sist_usersloginacceso (username, fecha, acceso, ip, hostname, city, region, country, loc, org) values('$usuario', '$fecha', '0', '$ip', '$hostname', '$city', '$region', '$country', '$loc', '$org')";	
		$conexion->query($guardarRegistroenacceso);
	    header ("Location: login");
	}
}else{

	$conexion = new mysqli();
	$conexion->connect($dbserver, $dbuser, $dbpass, $dbname);
	if($conexion->connect_error){
		die("No hubo conexión: ".$conexion->connect_error);
	}

	function getUserIP()
	{
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }

	    return $ip;
	}

	$ipRemote = getUserIP();
	$details = json_decode(file_get_contents("http://ipinfo.io/{$ipRemote}"));

	if(isset($_POST['username'])) $usuario = $_POST['username'];
	if(isset($_POST['password'])) $pass = str_replace(md5(date("d")), "", $_POST['password']);

	$fecha = date('Y-m-d H:i:s');
	$ip = $details->ip;
	$hostname = isset($details->hostname) ? $details->hostname : '';
	$city = isset($details->hostname) ? $details->city : '';
	$region = isset($details->hostname) ? $details->region : '';
	$country = isset($details->hostname) ? $details->country : '';
	$loc = isset($details->hostname) ? $details->loc : '';
	$org = isset($details->hostname) ? $details->org : '';

	$guardarRegistroenacceso = "INSERT INTO sist_usersloginacceso (username, fecha, acceso, ip, hostname, city, region, country, loc, org) values('$usuario', '$fecha', '0', '$ip', '$hostname', '$city', '$region', '$country', '$loc', '$org')";	
	$conexion->query($guardarRegistroenacceso);
    header ("Location: login");
}
?>