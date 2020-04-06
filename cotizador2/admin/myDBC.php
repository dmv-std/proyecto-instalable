<?php
	ini_set('display_errors', 0);
	session_start();
	// My database Class called myDBC
	class myDBC {
		// our mysqli object instance
		public $mysqli = null;

		// Class constructor override
		public function __construct() {

			include_once "../../config.php";
			$this->mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

			if ($this->mysqli->connect_errno) {
				echo "Error MySQLi: ("&nbsp. $this->mysqli->connect_errno.") " . $this->mysqli->connect_error;
				exit();
			}
			$this->mysqli->set_charset("utf8");
		}

		// Class deconstructor override
		public function __destruct() {
			$this->CloseDB();
		}

		// runs a sql query
		public function runQuery($qry) {
			$result = $this->mysqli->query($qry);
			return $result;
		}

		// Close database connection
		public function CloseDB() {
			$this->mysqli->close();
		}

		// Escape the string get ready to insert or update
		public function clearText($text) {
			$text = trim($text);
			return $this->mysqli->real_escape_string($text);
		}

		public function logueo($usuario, $contrasenia){
			include_once "../../config.php";

			//El password obtenido se le aplica el crypt
			//Posteriormente se compara en el query
			////$pass_c = crypt($contrasenia, '_er#.lop');
			////$q = "select * from wp_usersmem where email='$usuario' and password='$pass_c'";
			$q = "select * from cot2_usuarios where user='$usuario' and pass='$contrasenia'";

			$result = $this->mysqli->query($q);
			//Si el resultado obtenido no tiene nada
			//Muestra el error y redirige al index
			if( $result->num_rows == 0){
				echo'<script type="text/javascript">
				alert("Usuario o Contraseña Incorrecta");
				window.location="'.$basehttp.'/cotizador2/admin/login.php"
				</script>';
			}

			//En otro caso
			//En $reg se guarda el resultado de la consulta
			//Al segundo posición de SESION se le asigna el id del usuario
			//Redirige a página logueada
			else{
				$reg = mysqli_fetch_assoc($result);
				$_SESSION['user']=$reg['user'];
				$_SESSION['nombre']=$reg['nombre'];
				$_SESSION['correo']=$reg['correo'];
				$_SESSION['pass']=$reg['pass'];
				$_SESSION['permisos']=$reg['permisos'];
				$_SESSION['id']=$reg['id'];
				header("location: index.php");
			}
		}
		
	}
 
?>