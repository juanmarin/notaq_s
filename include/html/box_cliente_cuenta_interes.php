<?php
header('Content-Type: text/html; charset=iso-8859-1');

require_once("../php/sys_db.class.php");
require_once("../conf/Config_con.php");
require_once("../php/fun_global.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	
$sql = "SELECT * FROM cuentas WHERE id = ".$_GET["c"];
$res = $db->query($sql);
$c = $db->fetchNextObject($res);
#$cant = ($c->interes / 100) * $c->total;
$cant = quitaInteres($c->interes, $c->total);
?>
<table>
	<caption></caption>
	<tbody>
		<tr>
			<th colspan="2">Pagar solo el interés del abono y recorrer las fechas de pago.</th>
		</tr>
		<tr>
			<th>Cantidad</th>
			<td>$ <?php echo moneda($cant); ?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="2">
			<form name="interes" method="post" action="include/php/sys_modelo.php">
				<input type="hidden" name="action" value="cuenta_solo_interes" />
				<input type="hidden" name="c" value="<?php echo $c->id; ?>" />
				<input type="hidden" name="cl" value="<?php echo $c->cliente; ?>" />
				<input type="hidden" name="cant" value="<?php echo $cant; ?>" />
				<input type="submit" value="Pagar" />
			</form>
			</th>
		</tr>
	</tfoot>
</table>