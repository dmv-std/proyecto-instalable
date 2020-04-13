<style type="text/css">
img.logo{
	max-height: 80px;
}
div.header{
	width: 359px;
	height: 254px;
	position: absolute;
	top: 3px;
	left: 3px;
	font-size: 12pt;
}
.header h3{
	margin-bottom: 0px;
}
table{
	border: 3px solid #4d4d4f;
	margin: auto;
	width: 100%;
	border-collapse: collapse;
}
th{
	border-bottom: 3px solid #4d4d4f;
	padding-left: 8px;
}
th.first{
    width: 78px;
	text-transform: uppercase;
}
th.last{
    width: 242px;
	border-right: 3px solid #4d4d4f;
}
th.unique{
	width: 320px;
	text-transform: uppercase;
	text-align: center;
}
thead td.logo{
	border: 3px solid #4d4d4f;
	width: 320px;
	height: 242px;
}
td.logo .title{
	font-weight: bold;
	text-transform: uppercase;
	margin: 0;
}
td.logo span{
	line-height: 24px;
}
td.logo img{
	margin-top: 8px;
	margin-bottom: 8px;
	width: 80%;
}
*{color:#4d4d4f}
.dark{
	background: #4d4d4f;
	color: #fff;
}
.big{
	font-size: 14pt;
}
tbody td{
	/*text-align: center;*/
	width: 100%;
	border-bottom: 1px solid #4d4d4f;
	border-left: 3px solid #4d4d4f;
	border-right: 3px solid #4d4d4f;
	padding: 10px 0;
}
td.content{
	padding: 10px 0 10px 40px;
}
td.first, td.last{
	text-align: center;
	text-transform: uppercase;
	background: #8dd8f8;
	font-weight: bold;
	font-size: 13pt;
	padding: 10px 0;
}
td.content-left{
	text-align: left;
	width: 50%;
	padding: 10px 0 10px 40px;
	border-right: none;
}
td.content-right{
	text-align: right;
	width: 20%;
	padding: 10px 40px 10px 0;
	border-left: none;
}
td.content-left-long{
	text-align: left;
	width: 100px;
	padding: 10px 0 10px 40px;
	border-right: none;
	margin-bottom: 1px;
}
td.content-right-long{
	text-align: right;
	width: 10%;
	padding: 10px 40px 10px 0;
	border-left: none;
	margin-bottom: 1px;
	background: #eee;
}
div.data {
	position: absolute;
	top: 298px;
}
table.info, table.info tbody, table.info tr{
	width: 1070px;
}
table.info td.content{
	width: 70%;
	margin-bottom: 1px;
}
table.info td{
	border-left: none;
	border-right: none;
}
.condiciones {
	padding: 10px 40px;
	/*background: #fcb1b1;*/
	background: #fff;
	border-left: 3px solid #4d4d4f;
	border-right: 3px solid #4d4d4f;
}
.cliente{
	padding: 6px 40px;
	background: #fcb1b1;
	background: #fff;
	border-left: 3px solid #4d4d4f;
	border-right: 3px solid #4d4d4f;
	position: absolute;
	bottom: 80px;
	width: 670px;
	height: 40px;
}
.cliente p{
	position: absolute;
}
.footer{
	text-transform: none !important;
}
.footer a{
	text-decoration: none;
}
div.header table tr td{
	height: 212px;
	text-align: center;
	vertical-align: middle;
	border: none !important;
}
div.logo{
	width: 240px;
	height: 50px;
	background: #4e91a8;
	border: 6px dashed #fff;
	margin-left: -100px;
	margin-top: -45px;
	text-align: center;
	outline: 6px solid #4e91a8;
	font-weight: bold;
	color: #fff;
	padding-top: 30px;
}
</style>
<page backtop="2mm" backbottom="0mm" backleft="0mm" backright="0mm" style="font-size: 11pt;">
	<table class="general">
		<thead>
			<tr>
				<td rowspan="5" class="logo"></td>
				<th class="dark unique big" colspan="2">Presupuesto #<?php echo $id ?></th>
			</tr>
			<tr><th class="first big" style="border-right:1px solid #4d4d4f">Fecha</th><th class="last big" style="text-align:center;padding-left:0"><?php echo $fecha ?></th></tr>
			<tr><th class="first dark">Vendedor:</th><th class="last"><?php echo $usuario_nombre ?></th></tr>
			<tr><th class="first">Celular:</th><th class="last"><?php echo $usuario_telefono ?></th></tr>
			<tr><th colspan="2" class="last">MAIL: <?php echo $usuario_correo ?></th></tr>
		</thead>
		<tbody>
			<tr><td colspan="3" class="first">Detalle del presupuesto</td></tr>
			<?php for ($linea=0; $linea<19; $linea++): ?>
				<tr>
					<td colspan="3" class="content">
						<span style="color:#fff">.</span>
					</td>
				</tr>
			<?php endfor ?>
			<tr><td colspan="3" class="footer last" style="border-bottom: 3px solid #4d4d4f"><a href="<?php echo $web ?>"><?php echo $web ?></a></td></tr>
		</tbody>
	</table>
	<div class="header">
		<table>
			<tbody>
				<tr>
					<td>
						<?php if($logo): ?>
						<img src="<?php echo $logo ?>" class="logo" /><br />
						<?php else: ?>
						<div class="logo">LOGO</div>
						<?php endif?>
						<h3 class="title"><?php echo $empresa ?></h3><br />
						<span><?php echo $telefonos ?></span><br />
						<span><?php echo $direccion ?></span><br />
						<span><?php echo $email ?></span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="data">
		<table class="info">
			<tbody>
				<?php foreach ($detalles as $linea): ?>
					<tr>
						<?php if ($linea != "") { ?>
							<td colspan="3" class="content">
								<?php echo $linea ?>
							</td>
						<?php } else { ?>
							<td colspan="3" class="content">
								<span style="color:#fff">.</span>
							</td>
						<?php } ?>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<div class="condiciones">
			<p><?php echo $condiciones ?></p>
		</div>
	</div>
	<div class="cliente">
		<p>
			<strong>Cotizado a:</strong>
			<?php echo $cliente ?>
			(<?php echo $telefono ?>)
			- <<em><?php echo $correo ?></em>>
		</p>
	</div>
</page>
