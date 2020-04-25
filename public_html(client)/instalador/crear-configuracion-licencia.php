<?php
	if (isset($_POST['server']) && isset($_POST['serial'])) {

		$server = $_POST['server'];
		$serial = $_POST['serial'];

		if (file_exists("config.json"))
			$config_data = json_decode(file_get_contents("config.json"), true);
		else
			$config_data = [];

		$config_data['license-server']	= $server;
		$config_data['license-serial']	= $serial;

		file_put_contents("config.json", json_encode($config_data));

		echo "DONE";
	}
?>