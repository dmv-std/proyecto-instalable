<?php
	include("config.php");
	$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
	if($conn->connect_errno > 0){
		die('Unable to connect to database [' . $conn->connect_error . ']');
	}
	$conn->set_charset("utf8");
	
	$query = "SELECT * FROM licencias";
	$result = $conn->query($query) or die($conn->error.__LINE__);
	while($row = $result->fetch_assoc()) {
		$licencias[] = $row;
	}
	
	mysqli_close($conn);

//id,nombre,apellido,serial,sitio,fecha_creacion,fecha_expiracion,activa
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Demo - Licencias</title>
</head>
<body>
	<h1>Licencias</h1>
	<?php if (count($licencias)>0): ?>
	<table>
		<thead>
			<tr>
				<th>Id</th>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Serial</th>
				<th>Sitio</th>
				<th>Fecha de creación</th>
				<th>Fecha de expiración</th>
				<th>Activa</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($licencias as $licencia): ?>
			<tr>
				<td><?php echo $licencia['id'] ?></td>	
				<td><?php echo $licencia['nombre'] ?></td>	
				<td><?php echo $licencia['apellido'] ?></td>	
				<td><?php echo $licencia['serial'] ?></td>	
				<td><?php echo $licencia['sitio'] ?></td>	
				<td><?php echo $licencia['fecha_creacion'] ?></td>	
				<td><?php echo $licencia['fecha_expiracion'] ?></td>	
				<td><?php echo $licencia['activa'] ? "Sí" : "No" ?></td>	
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php else: ?>
	<div><em>No hay ninguna licencia creada...</em></div>
	<?php endif ?>
	<form method="POST" action="crear-licencia.php">
		<h1>Crear nueva licencia:</h1>
		<div>
			<label for="nombre">Nombre:</label>
			<input type="text" name="nombre" placeholder="Nombre" required/>
		</div>
		<div>
			<label for="apellido">Apellido:</label>
			<input type="text" name="apellido" placeholder="Apellido" required/>
		</div>
		<div>
			<label for="sitio">Dominio:</label>
			<input type="text" name="sitio" placeholder="Sitio web" required/>
		</div>
		<div>
			<input type="submit" value="Crear nueva licencia..."/>
		</div>
	</form>
</body>
</html>