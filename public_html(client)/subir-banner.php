<?php
	if(isset($_POST['action'])) {
		if ($_POST['action'] == "subir" && !empty($_FILES) && isset($_POST['logo'])) {

			include("config.php");

			$logo = $_POST['logo'];

			if (isset($_FILES[$logo])) {
				$bannerpath = $basepath . "/assets/banners";
				$banner_url = $basehttp . "/assets/banners";

				if (!file_exists($bannerpath))
					mkdir($bannerpath);
				
				$explode = explode(".", $_FILES[$logo]['name']);
				$extension = array_pop($explode);

				$tempFile = $_FILES[$logo]['tmp_name'];
				$targetFile = $bannerpath . '/' . $logo . "." . $extension;

				foreach (explode("|", "jpg|jpeg|png|gif") as $ext)
					if (file_exists("$bannerpath/$logo.$ext"))
						unlink("$bannerpath/$logo.$ext");

				if (move_uploaded_file($tempFile,$targetFile)){
					echo $banner_url."/".$logo.".".$extension;
				} else {
					echo "ERROR";
				}
			}

		} else if ($_POST['action'] == "remover" && isset($_POST['logo'])) {
			$logo = $_POST['logo'];
			
			if(file_exists($logo)){
				unlink("$logo");
			}
			echo "DONE";
		}
	}
?>