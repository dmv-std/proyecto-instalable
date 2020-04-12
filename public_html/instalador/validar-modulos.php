<?php

	if (isset($_POST['modulos'])) {

		$modulos = explode("|", $_POST['modulos']);

		if (file_exists("config.json"))
			$config_data = json_decode(file_get_contents("config.json"), true);
		else
			$config_data = [];

		$config_data["modulos"] = $modulos;
		file_put_contents("config.json", json_encode($config_data));

		echo "DONE";
	}