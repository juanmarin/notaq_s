<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<? require_once "../php/fun_global.php"; ?>
<style>
	table tbody tr th{text-align: right;}
</style>
<form method="post" name="frm_pagar" action="include/php/sys_modelo.php">
<input type="hidden" name="c" value="<?php echo $_GET["c"];?>" />
<input type="hidden" name="action" value="cuenta_pagar" />
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
	## INFORMACION DE LA CUENTA
	$sql = "SELECT * FROM cuentas WHERE id = ".$_GET["c"];
	$res = $db->query($sql);
	$c = $db->fetchNextObject($res); 
	$cliente = $c->cliente;
	$saldo = $c->total;
	$cuenta = $c->id;
	$cantidad = $c->cantidad;
	$interes = $c->interes;
	$meses = $c->tiempo;
	$pago = $c->pago;
	$total = $cantidad * ((($interes * $meses) / 100) + 1);
	# INFORMACION DE RECARGOS EN LA CUENTA 
	$sql = "SELECT SUM(monto) as monto FROM recargos WHERE cuenta = ".$cuenta;
	$res = $db->query($sql);
	$r = $db->fetchNextObject($res);
	$recargos = $r->monto;
	?>
	<tr>
		<th>Saldo actual de la cuenta:</th>
		<td width="70" class="tdmoneda"><?php moneda($saldo);?></td>
	</tr>
	<tr>
		<th>Recargos generados:</th>
		<td class="tdmoneda"><?php moneda($recargos);?></td>
	</tr>
	<tr>
		<th>Estado de la cuenta:</th>
		<td class="tdmoneda"><?php moneda($recargos + $saldo); ?></td>
	</tr>
	<tr>
		<th>Abonar a la cuenta:</th>
		<td>
			<input type="text" name="abono" id="abono" size="8" value="<?php echo $pago; ?>" class="tdmoneda">
		</td>
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