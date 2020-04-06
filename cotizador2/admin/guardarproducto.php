<?php include ("../../config.php");
    if(isset($_GET['accion'])){
        $accion = $_GET['accion'];
        if($accion=="eliminar"){
            $idprod = $_GET['idprod'];
			$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }
            $results = $mysqli->query("DELETE FROM cot2_productos WHERE id = '$idprod'");
            if($results){
                $msg = "El producto fue eliminado de manera exitosa!";
                echo $msg;
            }else{
                echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
            }
            mysqli_close($mysqli);
        }elseif($accion=="modificar"){
			$idprod = $_GET['idprod'];
			$codigo = $_GET['codigo'];
			$rubro = $_GET['rubro'];
			$subrubro = $_GET['subrubro'];
			$descripcion = $_GET['descripcion'];
			$medida = $_GET['medida'];
			$espesor = $_GET['espesor'];
			$packaging = $_GET['packaging'];
			$preciounitario = $_GET['preciounitario'];
			$cantidadminima = $_GET['cantidadminima'];
			$cantidades100 = $_GET['cantidades100'];
			$cantidades200 = $_GET['cantidades200'];
			$cantidades500 = $_GET['cantidades500'];
			$cantidades1000 = $_GET['cantidades1000'];
			$cantidades5000 = $_GET['cantidades5000'];
			$cantidades10000 = $_GET['cantidades10000'];
			$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }
            $results = $mysqli->query("UPDATE cot2_productos SET codigo = '$codigo', rubro = '$rubro', subrubro = '$subrubro', descripcion = '$descripcion', medida = '$medida', espesor = '$espesor', packaging = '$packaging', cantidadminima = '$cantidadminima', preciounitario = '$preciounitario', cantidades100 = '$cantidades100', cantidades200 = '$cantidades200', cantidades500 = '$cantidades500', cantidades1000 = '$cantidades1000', cantidades5000 = '$cantidades5000', cantidades10000 = '$cantidades10000' WHERE id = '$idprod'");
            if($results){
                $msg = "El producto fue actualizado de manera exitosa!";
                echo $msg;
            }else{
                echo 'Error : ('. $mysqli->errno .') '. $mysqli->error;
            }
            mysqli_close($mysqli);
        }elseif($accion=="agregar"){
            $codigo = $_GET['codigo'];
			$rubro = $_GET['rubro'];
			$subrubro = $_GET['subrubro'];
			$descripcion = $_GET['descripcion'];
			$medida = $_GET['medida'];
			$espesor = $_GET['espesor'];
			$packaging = $_GET['packaging'];
			$preciounitario = $_GET['preciounitario'];
			$cantidadminima = $_GET['cantidadminima'];
			$cantidades100 = $_GET['cantidades100'];
			$cantidades200 = $_GET['cantidades200'];
			$cantidades500 = $_GET['cantidades500'];
			$cantidades1000 = $_GET['cantidades1000'];
			$cantidades5000 = $_GET['cantidades5000'];
			$cantidades10000 = $_GET['cantidades10000'];
			$mysqli = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
            if($mysqli->connect_errno > 0){
                die('Unable to connect to database [' . $db->connect_error . ']');
            }
            $insert_row = $mysqli->query("INSERT INTO cot2_productos (codigo, rubro, subrubro, descripcion, medida, espesor, packaging, preciounitario, cantidadminima, cantidades100, cantidades200, cantidades500, cantidades1000, cantidades5000, cantidades10000) values('$codigo', '$rubro', '$subrubro', '$descripcion', '$medida', '$espesor', '$packaging', '$preciounitario', '$cantidadminima', '$cantidades100', '$cantidades200', '$cantidades500', '$cantidades1000', '$cantidades5000', '$cantidades10000')");
            if($insert_row){
                $msg = "El producto fue agregado de manera exitosa!";
                echo $msg;
            }else{
                print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
            }
            mysqli_close($mysqli);
        }
    }
?>