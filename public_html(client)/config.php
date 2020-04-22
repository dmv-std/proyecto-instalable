<?php
	// DATABASE INFORMATION
	$dbserver = "localhost";
	$dbname = "ecvi2020";
	$dbuser = "root";
	$dbpass = "armagedon2";
	
	// WEBSITE INFORMATION
	$sitename = "El Imperio de ML S.A.";
	$basepath = "C:/xampp/htdocs/work/evamagic";
	$basehttp = "http://sistema.empresa";

	$assets_url = $basehttp."/assets";
	$styles_url = $basehttp."/assets/stylesheets";
	$css_url = $basehttp."/assets/css";
	$js_url = $basehttp."/assets/js";
	$fontawesome_url = $basehttp."/assets/font-awesome";
	$images_url = $basehttp."/assets/images";

	$pdf_path = $basepath."/assets/pdf";
	$firmas_path = "/assets/firmas";

	// Some resources
	$load_resources_locally = true;

	// Presupuestos
	$presupuestos_archivos_path = "/assets/presupuestos";
	$presupuestos_pdf_logo = "/assets/logos/" . "presupuestos-logo";

	// Cotizador2
	$cotizador2_pdf_logo = "/assets/logos/" . "cotizador2-logo";

	// reCAPTCHA
	$captcha_front_code = "6Lc0AeYUAAAAAObV7HYV0lmDVUmMi1hOnfldKewf";
	$captcha_back_code = "6Lc0AeYUAAAAADc_3ByUCtL6p69e25RY_u_qB6cY";

	// INSTALLED MODULES
	$instalados = "cotizador2|presupuestos";
?>