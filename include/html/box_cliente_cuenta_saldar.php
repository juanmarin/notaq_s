<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<?php require_once "../php/fun_global.php"; ?>
<style>
	table tbody tr th{text-align: right;}
</style>
<form method="post" name="frm_saldar" action="include/php/sys_modelo.php">
<input type="hidden" name="c" value="<?php echo $_GET["c"];?>" />
<input type="hidden" name="action" value="cuenta_saldar" />
<table>
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
	$result = $db->query("SELECT * FROM cuentas WHERE id = ".$_GET["c"]);
	$ln = $db->fetchNextObject($result);
	$cliente = $ln->cliente;
	$saldo = $ln->total;
	?>
	<tr>
		<th>Saldo de cuenta:</th>
		<td><input type="text" name="cant" value="<?php moneda($saldo);?>" readonly="readonly" /></td>
	</tr>
	<?php
	$sql = "SELECT SUM(monto) as monto FROM recargos WHERE estado = 0 AND cuenta = ".$_GET["c"];
	$res = mysql_query($sql);
	if(mysql_num_rows($res) > 0){
		$rec = mysql_fetch_array($res);
		$recargos = $rec[0];
		echo '<tr><th>Recargos</th><td><input type="text" name="recargo" value="';
		moneda($recargos);
		echo '" readonly="readonly" /></td></tr>';
	}
	?>
	<tr>
		<th>Monto a pagar</th>
		<td><input type="text" name="total" value="<?php moneda($saldo + $recargos);?>" readonly /></td>
	</tr>
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
</tbody>
<tfoot>
	<tr>
		<th colspan="2" style="padding:10px;">
		<input type="hidden" name="cl" value="<?php echo $cliente;?>" />
		<input type="submit" name="frm_saldar" value="Pagar" />
		</th>
	</tr>		
</tfoot>
<table>
</form>
