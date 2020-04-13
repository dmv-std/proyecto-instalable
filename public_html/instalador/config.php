<?php

$basepath = $_SERVER['DOCUMENT_ROOT'];

$assets_url = "../assets";
$load_resources_locally = true;

$fases = array(
	'Validar licencia',
	'Comprobación del Sistema',
	'Configuracion del server',
	'Cuenta del admin',
	'activar reCAPTCHA',
	'Elegir módulos',
	'Instalación',
);

$modulosPorDefecto = ["sistema", "fichada"];

$modulos = [
	['etiqueta' => 'cotizador2', 'nombre' => 'Cotizador 2'],
	['etiqueta' => 'presupuestos', 'nombre' => 'Presupuestos'],
];

$writable_dirs = [
	'assets/firmas',
	'assets/pdf',
	'assets/presupuestos',
	'assets/banners',
	'assets/logos',
	'cotizador2/admin/archivos',
];


$current_path=explode('/', $_SERVER['REQUEST_URI']);
array_pop($current_path);
$current_path=implode('/', $current_path);

$system_checks = array(

	'Php '.phpversion()					=> [ explode('.', phpversion())[0] >= 5,
											'Se requiere como mínimo contar con la versión PHP 5' ],

	'Función mail habilitada'			=> [
											isset($_GET['correo']) ? mail('to@mail.com', 'subject', 'mail body', 'From: '.$_GET['correo']) : mail('to@mail.com', 'subject', 'mail body'),
											'La función mail() parece encontrarse deshabilitada en este servidor. Para habilitarla, revise la configuración del archivo php.ini en su server, y asegúrese de que el campo \'disable_functions\' no contenga la función mail. Consulte a los proveedores del host si el problema persiste.',
											!isset($_GET['correo']) ? '<i class="fa fa-exclamation-triangle"></i> No se ha podido comprobar si la función mail se encuentra habilitada<br/>Para hacer una mejor verificación, por favor ingrese la dirección email que desea utilizar como el remitente de sus mensajes (la dirección debe estar activa):<br/><input class="form-control" type="email" placeholder="Correo del remitente"/> <button type="button" class="btn btn-info btn-verificar-correo">Probar</button>' : '<i class="fa fa-exclamation-triangle"></i> La dirección de correo ingresada no es válida, ó la función mail se encuentra inhabilitada para este servidor. Póngase en contacto con los administradores si el problema persiste<br/><br/><button type="button" class="btn btn-info btn-verificar-correo-atras">Ir a atrás</button>' ],

	'Extensión mysqli'					=> [ function_exists('mysqli_connect'),
											'No se ha encontrado la extensión mysqli.' ],

	'Instalación en directorio raíz'	=> [ $current_path == "/instalador",
											'No ha movido los archivos de instalación en el directorio raíz (normalmente public_html). Transfiéralos allí o asigne un subdominio a la ubicación actual y ejecute el instalador desde la url del subdominio para que ésta sea tratada como un directorio raíz.' ],
);



function comprobarPermisosDB($server, $user, $pass) {
	$conn = new mysqli($server, $user, $pass);
	if ($conn->connect_error) {
		$error = "Connection failed: " . $conn->connect_error;
	}
	$conn->query("CREATE DATABASE IF NOT EXISTS check_permissions");
	$res=$conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'check_permissions'");
	if ($res->num_rows==0) {
		$conn->close();
		return false;
	} else {
		$conn->query("DROP DATABASE check_permissions");
		$conn->close();
		return true;
	}
}