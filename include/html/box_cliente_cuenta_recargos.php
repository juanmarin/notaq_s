<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<style>
	table tbody tr th{text-align: right;}
</style>
<form method="post" name="frm_saldar" action="include/php/sys_modelo.php">
<table style="width:100%;">
<caption></caption>
<thead>
	<tr>
		<th colspan="2"></th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);

	$sql = "SELECT SUM(monto) as monto FROM recargos WHERE estado=0 AND cuenta = ".$_GET["c"];
	$res = mysql_query($sql);
	if(mysql_num_rows($res) > 0){
		$rec = mysql_fetch_array($res);
		$recargos = $rec[0];
		echo '<tr><th>Recargos</th><td><input type="text" name="recargo" value="'.$recargos.'" readonly="readonly" /></td></tr>';
	}
	?>
	<input type="hidden" name="recargo" value="<?php echo $recargos;?>" />
	<input type="hidden" name="c" value="<?php echo $_GET["c"];?>" />
	<input type="hidden" name="cl" value="<?php echo $_GET["cl"];?>" />
	<input type="hidden" name="action" value="recargos" />
	<tr>
		<th colspan="2" style="text-align: center;">Autorización</th>
	</tr>
	<tr>
		<th>Usuario:</th>
		<th><input type="text" name="usuario" /></th>
	</tr>
	<tr>
		<th>Contraseña:</th>
		<th><input type="password" name="autorizacion" /></th>
	</tr>
	<tr>
		<th colspan="2" style="text-align: center;">OPCIONES</th>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="2" style="padding:10px;">
			<input type="submit" name="rec_pagar" value="Pagar" />
			<input type="submit" name="rec_condonar" value="Condonar" />
			<input type="submit" name="rec_pagos" value="Añadir a cuenta" />
		</th>
	</tr>		
</tfoot>
</table>
</form>