<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Test</title>
</head>
<body><?php setlocale(LC_ALL, 'es-VE.UTF-8') ?>
	<p><?php echo date('Y-m-d') ?></p>

	<a href="#">Sendmail with pdf attached (emulated only)</a>

    <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js">'+"<"+"/script>"); </script>

	<script type="text/javascript">
		$('a').click(function(e){
			e.preventDefault()
				$.ajax({
                    url : 'crear-pdf-en-servidor.php',
                    data : {id:33},
                    type : 'GET',
                    dataType : 'html',
                    success : function(r) {
						console.log(r)
                    },
                    error : function(xhr, status) {
                        alert('Disculpe, existió un problema');
                    },
                })
		})
	</script>
</body>
</html>