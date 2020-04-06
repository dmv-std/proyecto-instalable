<?php

	if (isset($_POST['host']) && isset($_POST['db']) && isset($_POST['user']) 
		&& isset($_POST['pass']) && isset($_POST['sitename']))
	{

		$sitename = $_POST['sitename'];
		$path = $_SERVER['DOCUMENT_ROOT'];
		$basepath = substr($path, -1) == "/" ? substr($path, 0, strlen($path)-1) : $path;
		$http = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
		$basehttp = "$http://".$_SERVER['SERVER_NAME'];
		
		if (file_exists("config.json"))
			$config_data = json_decode(file_get_contents("config.json"), true);
		else
			$config_data = [];

		$dbserver=$_POST['host']; $dbuser=$_POST['user']; $dbpass=$_POST['pass']; $dbname=$_POST['db']; 

		$conn = new mysqli($dbserver, $dbuser, $dbpass);

		if (!$conn->connect_error) {

			$config_data['site-name']	= $sitename;
			$config_data['basepath']	= $basepath;
			$config_data['basehttp']	= $basehttp;
			$config_data['db-server']	= $dbserver;
			$config_data['db-name']		= $dbname;
			$config_data['db-user']		= $dbuser;
			$config_data['db-pass']		= $dbpass;

			file_put_contents("config.json", json_encode($config_data));

			echo "SUCCESS";
		} else {
			echo "error";
		}
		$conn->close();
	}