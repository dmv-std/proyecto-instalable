<?php header('Content-type: application/javascript; charset=utf-8');


$callback = isset($_GET['callback']) ? $_GET['callback'] : "";

if (!$callback || preg_match('/\W/', $callback)) {

	//header('HTTP/1.1 400 Bad Request');
	$error = "HTTP/1.1 400 Bad Request";
	$actived=''; $expire=''; $expired=false;

} else if ( isset($_GET['license']) ) {
	include("config.php");
	
	$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "not-valid";
	preg_match ("/https?:\/\/([^\/]+?)\//", $referer, $matches);
	
	$sitio = isset($matches[1]) ? $matches[1] : '<not-valid>';
	$serial = $_GET['license'];
	
	// Check license first
	$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($conn->connect_errno > 0){
		die('Unable to connect to database [' . $conn->connect_error . ']');
	}
	$conn->set_charset("utf8");
	$result = $conn->query("SELECT * FROM licencias WHERE serial='$serial' AND sitio LIKE '%$sitio%'") or die($conn->error.__LINE__);
	if ($result->num_rows == 0) {
		$error = "La licencia suministrada no es válida";
		$actived=''; $expire=''; $expired=false;
	} else {
		$row = $result->fetch_assoc();
		if (date('Y-m-d')>$row['fecha_expiracion']) {
			$error = "Su licencia ha expirado";
			$expire = date('d/m/Y', strtotime($row['fecha_expiracion']));
			$actived = $row['activa']?true:false;
			$expired = true;
		}
		else if (!$row['activa']) {
			$error = "Su licencia es válida pero se encuentra inactiva. Consulte a su proveedor";
			$expire = date('d/m/Y', strtotime($row['fecha_expiracion']));
			$actived = $row['activa']?true:false;
			$expired = false;
		} else
			$error = "";
			$expire = date('d/m/Y', strtotime($row['fecha_expiracion']));
			$actived = $row['activa']?true:false;
			$expired = false;
	}
} else {
	$error = "HTTP/1.1 400 Bad Request";
}

echo "typeof ".$callback."==='function' && ".$callback."(".json_encode([
	"error" => $error,
	"validated" => !$error ? true : false,
	"actived" => $actived,
	"expireDate" => $expire,
	"expired" => $expired,
]).");";

?>