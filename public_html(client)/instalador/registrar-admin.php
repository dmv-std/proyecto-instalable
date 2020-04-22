<?php

	if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['correo'])
		&& isset($_POST['user']) && isset($_POST['pass']))
	{
		if (file_exists("config.json"))
			$config_data = json_decode(file_get_contents("config.json"), true);
		else
			$config_data = [];

		$config_data['nombre']		= $_POST['nombre'];
		$config_data['apellido']	= $_POST['apellido'];
		$config_data['correo']		= $_POST['correo'];
		$config_data['user']		= $_POST['user'];
		$config_data['pass']		= $_POST['pass'];

		file_put_contents("config.json", json_encode($config_data));

		echo "SUCCESS";
	}