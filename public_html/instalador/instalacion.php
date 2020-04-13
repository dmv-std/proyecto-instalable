<?php

if (isset($_POST['step'])) {

	$paso = $_POST['step'];
	$pasos = 0;
	$estados = [];
	$error = "";

	require_once("config.php");
	require_once("db-modulos.php");
	
	if (!leerYValidarConfig($config_data, $error)) {
		echo json_encode([
			"paso" => $paso,
			"total" => $pasos,
			"estado" => "Iniciando instalación...",
			"error" => $error,
		]);
		return;
	}

	$server = $config_data['db-server'];
	$user = $config_data['db-user'];
	$pass = $config_data['db-pass'];
	$db = $config_data['db-name'];

	// Inicializando queries
	if ($paso == ++$pasos) {
		$queries = [];
		foreach ($modulosPorDefecto as $modulo) {
			foreach (explode(";", $db_modulos[$modulo]) as $query) {
				if (trim($query)!=="") {
					preg_match('/CREATE TABLE `(\w+?)`/', $query, $matches);
					array_push($queries, [
						"state" => $matches ? "Creando tablas ($modulo): ".$matches[1]."..." : "Inicializando tablas ($modulo)...",
						"query" => $query,
					]);
				}
			}
		}
		foreach ($modulos as $modulo) {
			if (in_array($modulo['etiqueta'], $config_data['modulos'])) {
				foreach (explode(";", $db_modulos[$modulo['etiqueta']]) as $query) {
					if (trim($query)!=="") {
						preg_match('/CREATE TABLE `(\w+?)`/', $query, $matches);
						array_push($queries, [
							"state" => $matches ? "Creando tablas (".$modulo['etiqueta']."): ".$matches[1]."..." : "Inicializando tablas (".$modulo['etiqueta'].")...",
							"query" => $query,
						]);
					}
				}
			}
		}
		$config_data['queries'] = $queries;
		file_put_contents("config.json", json_encode($config_data));
	}
	array_push($estados, "Inicializando lista de consultas...");

	// Creando base de datos (si no existe)
	if ($paso == ++$pasos) {
		$conn = new mysqli($server, $user, $pass);
		if ($conn->connect_error) {
			$error = "Connection failed: " . $conn->connect_error;
		}
		if(!$conn->query("CREATE DATABASE IF NOT EXISTS ".$config_data['db-name']."")) {
			$error = "Error creating database: " . $conn->error;
		}
		$conn->close();
	}
	array_push($estados, "Creando base de datos...");

	// Es en el paso 2 cuando la base de datos es creada, antes de eso no se puede abrir/cerrar conexión...
	$init_db = $paso>=2 ? true : false;

	$error = validarBaseDeDatos($server, $user, $pass, $config_data['db-name']);
	if ($error) $init_db = false;

	// Abriendo conexión con base de datos previamente inicializada.
	if ($init_db) {
		$conn = new mysqli($server, $user, $pass, $db);
		if($conn->connect_error){
			die('Unable to connect to database [' . $conn->connect_error . ']');
		}
	}

	// Creando tablas
	if (isset($config_data['queries'])) {
		foreach ($config_data['queries'] as $query) {
			if ($init_db && $paso == $pasos+1) {
				if (!$conn->query($query["query"])) {
					$error = "Error creating table: " . $conn->error;
				}
			}
			$pasos++;
			array_push($estados, $query["state"]);
		}
	}

	// Crear archivo config.php
	if ($paso == ++$pasos) {
		$basepath = $config_data['basepath'];
		file_put_contents("$basepath/config.php", generarArchivoConfig($config_data));
	}
	array_push($estados, "Creando archivo config.php... ");

	// Crear archivo .htaccess
	if ($paso == ++$pasos) {
		$basepath = $config_data['basepath'];
		ob_start();
		include "htaccess.php";
		$htaccess = ob_get_clean();
		file_put_contents("$basepath/.htaccess", $htaccess);
	}
	array_push($estados, "Creando archivo .htaccess ... ");

	// Crear cuenta de usuario administrador
	if ($paso == ++$pasos) {
		$config_data['pass'] = md5($config_data['pass']);
		$permisos="admin"; $id_empleado=0; $idCal=3; $rolCal=2; $rolPag=1; $rolPro=2;
		$stmt = $conn->prepare("INSERT INTO sist_usuarios (nombre, user, pass, permisos, email, apellido, id_empleado,
				idCal, rolCal, rolPag, rolPro) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssssssiiiii", $config_data['nombre'], $config_data['user'], $config_data['pass'],
			$permisos, $config_data['correo'], $config_data['apellido'],
			$id_empleado, $idCal, $rolCal, $rolPag, $rolPro);
		$stmt->execute();
		$stmt->close();

		unlink( "config.json" );
	}
	array_push($estados, "Creando cuenta de usuario administrador... ");

	if ($init_db)
		$conn->close();

	echo json_encode([
		"paso" => $paso,
		"total" => $pasos,
		"estado" => $estados[$paso-1],
		"error" => $error,
	]);
}



function generarArchivoConfig($config_data) {
$archivo = '<?php
	// DATABASE INFORMATION
	$dbserver = "' . $config_data['db-server'] . '";
	$dbname = "' . $config_data['db-name'] . '";
	$dbuser = "' . $config_data['db-user'] . '";
	$dbpass = "' . $config_data['db-pass'] . '";
	
	// WEBSITE INFORMATION
	$sitename = "' . $config_data['site-name'] . '";
	$basepath = "' . $config_data['basepath'] . '";
	$basehttp = "' . $config_data['basehttp'] . '";
	
	$assets_url = $basehttp."/assets";
	$styles_url = $basehttp."/assets/stylesheets";
	$css_url = $basehttp."/assets/css";
	$js_url = $basehttp."/assets/js";
	$fontawesome_url = $basehttp."/assets/font-awesome";
	$images_url = $basehttp."/assets/images";
	
	$pdf_path = $basepath."/assets/pdf";
	$firmas_path = "/assets/firmas";
	
	// Some resources
	$load_resources_locally = false;
	
	'.(in_array("presupuestos", $config_data['modulos'])?'// Presupuestos
	$presupuestos_archivos_path = "/assets/presupuestos";
	$presupuestos_pdf_logo = "/assets/logos/" . "presupuestos-logo";
	
	':'').(in_array("cotizador2", $config_data['modulos'])?'// Cotizador2
	$cotizador2_pdf_logo = "/assets/logos/" . "cotizador2-logo";
	
	':'').'// reCAPTCHA
	$captcha_front_code = "' .  $config_data['captcha_front_code'] . '";
	$captcha_back_code = "' .  $config_data['captcha_back_code'] . '";
	
	// INSTALLED MODULES
	$modules = "'.implode("|", $config_data['modulos']).'";
?>';
	return $archivo;
}



function leerYValidarConfig(&$config_data, &$error) {
	if (file_exists("config.json"))
		$config_data = json_decode(file_get_contents("config.json"), true);
	else {
		$error = "<strong>ERROR:</strong> archivo <code>/instalador/config.json</code> no encontrado.";
		return false;
	}

	// Validando
	$checks = ['db-server', 'db-user', 'db-pass',' db-name',
				'modulos', 'basepath',
				'nombre', 'user', 'pass', 'correo', 'apellido'];
	foreach ($checks as $check) {
		if (!isset($config_data[$check]) || !$config_data[$check]) {
			$error = "<strong>ERROR:</strong> falta uno o más datos requeridos para la instalación.
			<br/>reinicie el instalador y asegúrese de completar todas las etapas (Click <a href=\"/instalador\">aquí</a>).";
		}
	}
	return true;
}


function validarBaseDeDatos($server, $user, $pass, $db) {
	$conn = new mysqli($server, $user, $pass);
	$res=$conn->query("SELECT SCHEMA_NAME
							FROM INFORMATION_SCHEMA.SCHEMATA
							WHERE SCHEMA_NAME = '$db'");
	if ($res->num_rows==0) {
		$error = "<strong>Ha ocurrido un error:</strong>
		la base de datos <code>$db</code> no ha podido ser creada automáticamente
		por falta de permisos.<br/><br/>Deberá crearla manualmente desde PhpMyAdmin.
		Cuando esté listo, refresque esta página.<br/>Recuerde que los datos previamente ingresados de
		usuario y contraseña (base de datos) tienen que seguir siendo los mismos, si no es asi,
		reinicie el instalador (Click <a href=\"/instalador\">aquí</a>).";
	} else
		$error = "";
	$conn->close();
	return $error;
}