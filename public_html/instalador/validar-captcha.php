<?php
if (isset($_POST['back-code'])) {
	require_once "../recaptchalib.php";

	$secret = $_POST['back-code'];

	$response = null;
	$reCaptcha = new ReCaptcha($secret);

	if ($_POST["g-recaptcha-response"]) {
		$response = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
		);
	}

	if ($response != null && $response->success) {

		if (file_exists("config.json"))
			$config_data = json_decode(file_get_contents("config.json"), true);
		else
			$config_data = [];

		$config_data['captcha_front_code']	= $_POST['front-code'];
		$config_data['captcha_back_code']	= $_POST['back-code'];

		file_put_contents("config.json", json_encode($config_data));

		echo "SUCCESS";
		
	} else {
		echo "ERROR";
	}
}