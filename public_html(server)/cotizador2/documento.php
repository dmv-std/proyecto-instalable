<style type="text/css">
.logo img{
	max-height: 82px;
}
table{
	border: 3px solid #4d4d4f;
	margin: auto;
	width: 100%;
	border-collapse: collapse;
}
table.header th{
	border-bottom: 3px solid #4d4d4f;
	padding-left: 8px;
}
table.header th.first{
    width: 78px;
	text-transform: uppercase;
}
table.header th.last{
    width: 242px;
	border-right: 3px solid #4d4d4f;
}
table.header th.unique{
	width: 320px;
	text-transform: uppercase;
	text-align: center;
}
table.header thead td.logo{
    width: 320px;
	text-align: center;
	font-size: 12pt;
}
.logo .title{
	font-weight: bold;
	text-transform: uppercase;
	margin: 0;
}
.logo span{
	line-height: 24px;
}
.logo img{
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
	padding: 10px 0 10px 80px;
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
tr.data td {
	width: auto;
	border-right: none;
	border-left: none;	
}
tr.strong td {
	font-weight: bold;
}
tr.data td.fst {
	border-left: 3px solid #4d4d4f;
	padding-left: 8px;
}
tr.data td.lst {
	border-right: 3px solid #4d4d4f;
	padding-right: 8px;
}
.text-right {
	text-align: right;
}
.text-center {
	text-align: center;
}
a{
	text-decoration: none;
}
div.no-logo{
	width: 240px;
	height: 42px;
	background: #4e91a8;
	border: 6px dashed #fff;
	margin-left: -100px;
	margin-top: -10px;
	text-align: center;
	outline: 6px solid #4e91a8;
	font-weight: bold;
	color: #fff;
	padding-top: 22px;
	margin-bottom: 12px;
}
</style>
<page backtop="2mm" backbottom="0mm" backleft="0mm" backright="0mm" style="font-size: 11pt;">
	<table class="header">
		<thead>
			<tr>
				<td rowspan="5" class="logo" style="border:3px solid #4d4d4f">
					<?php if ($logo):?>
						<img src="<?php echo $logo ?>" /><br />
					<?php else:?>
						<div class="no-logo">LOGO</div>
					<?php endif?>
					<h3 class="title"><?php echo $empresa ?></h3><br />
					<span><?php echo $telefonos ?></span><br />
					<span><?php echo $direccion ?></span><br />
					<span><?php echo $email ?></span><br /><br />
				</td>
				<th class="dark unique big" colspan="2">Presupuesto #<?php echo $id ?></th>
			</tr>
			<tr><th class="first big" style="border-right:1px solid #4d4d4f">Fecha</th><th class="last big" style="text-align:center;padding-left:0"><?php echo $fecha ?></th></tr>
			<tr><th class="first dark">Vendedor:</th><th class="last"><?php echo $usuario_nombre ?></th></tr>
			<tr><th class="first">Celular:</th><th class="last"><?php echo $usuario_telefono ?></th></tr>
			<tr><th class="first">Mail:</th><th class="last" style="font-size:9pt"><?php echo $usuario_correo ?></th></tr>
		</thead>
		<tbody>
			<tr><td colspan="3" class="first">Detalle del presupuesto</td></tr>
		</tbody>
	</table>
	<table>
		<tbody>
			<tr class="data">
				<td class="fst text-center">Código</td>
				<td>Descripción</td>
				<td>Cantidad</td>
				<td>Colores</td>
				<td>Precio</td>
				<td class="lst text-right">Total</td>
			</tr>
			<?php $line=1 ?>
			<?php foreach ($detalles as $detalle): ?>
				<?php $line++ ?>
				<tr class="data">
					<td class="fst text-center"><?php echo $detalle['codigo'] ?></td>
					<td><?php echo $detalle['descripcion'] ?></td>
					<td class="text-center"><?php echo $detalle['cantidad'] ?></td>
					<td class="text-center"><?php echo $detalle['colores'] ?></td>
					<td class="text-center">$<?php echo number_format($detalle['precio'], 2, ",", ".") ?></td>
					<td class="lst text-right">$<?php echo number_format($detalle['total'], 2, ",", ".") ?> + IVA</td>
				</tr>
			<?php endforeach ?>
			<tr class="data strong">
				<td class="fst text-right" colspan="5">TOTAL:</td>
				<td class="lst text-right">$<?php echo number_format($total, 2, ",", ".") ?></td>
			</tr>
			<?php $line++ ?>
			<tr class="data strong">
				<td class="fst text-right" colspan="5">TOTAL + IVA:</td>
				<td class="lst text-right">$<?php echo number_format($total * (1 + $iva*0.01), 2, ",", ".") ?></td>
			</tr>
			<?php $line++ ?>
			<?php for ($i=$line; $i<18; $i++): ?>
				<tr>
					<td colspan="6" class="content">
						<span style="color:#fff">.</span>
					</td>
				</tr>
			<?php endfor ?>
			<tr class="data">
				<td colspan="6" class="fst lst" style="width:100%">
					<strong>Cotizado a:</strong>
					<?php echo $nombre ?>
					<?php echo $apellidos ?>
					(<?php echo $telefono ?>) -
					<<em><a href="mailto:<?php echo $email1 ?>"><?php echo $email1 ?></a></em>>
				</td>
			</tr>
			<tr><td colspan="6" class="last" style="border-bottom: 3px solid #4d4d4f"><a href="<?php echo $web ?>"><?php echo $web ?></a></td></tr>
		</tbody>
	</table>
</page>
