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
$cant = $c->cantidad;
$plazo = $c->tiempo;
$tot_c_interes = $c->cantidad * (( ($c->interes * $c->tiempo)  / 100 ) + 1 );
$npagos = getPagos($tot_c_interes, $plazo, $c->tipo_pago);
?>
<table>
	<caption></caption>
	<tbody>
		<tr>
			<th colspan="2">Reimprimir informacion del prestamo actual</th>
		</tr>
		<tr>
			<th>Fecha del Prestamo</th>
			<td> <?php echo $c->fecha; ?></td>
		</tr>
		<tr>
			<th>Cantidad</th>
			<td>$ <?php echo moneda($cant); ?></td>
		</tr>
		<tr>
			<th>Plazo</th>
			<td><?php echo $plazo; ?> Meses</td>
		</tr>
		<tr>
			<th>Total con Interes</th>
			<td>$ <?php echo $tot_c_interes; ?></td>
		</tr>
		<tr>
			<th><?php echo $npagos; ?>  Pagos de </th>
			<td>$ <?php echo $c->pago; ?></td>
		</tr>
		</tr>
		<tr>
			<th>Tipo de Pago</th>
			<td>$ <?php echo $c->tipo_pago; ?></td>
		</tr>
		<tr>
			<th>Dias de Pago</th>
			<td>$ <?php echo $c->dias_pago; ?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="2">
			<form name="interes" method="post" action="include/php/sys_modelo.php">
				<input type="hidden" name="action" value="reimprimir_prestamo" />
				<input type="hidden" name="c" value="<?php echo $c->id; ?>" />
				<input type="hidden" name="cuenta" value="<?php echo $c->id; ?>" />
				<input type="hidden" name="cl" value="<?php echo $c->cliente; ?>" />
				<input type="hidden" name="dia" value="<?php echo $c->dias_pago; ?>" />
				<input type="hidden" name="tipo_pago" value="<?php echo $c->tipo_pago; ?>" />
				<input type="hidden" name="cantidad" value="<?php echo $cant; ?>" />
				<input type="hidden" name="dias_pago" value="<?php echo $c->dias_pago; ?>" />
				<input type="submit" value="Reimprimir Info Prestamo" />
			</form>
			</th>
		</tr>
	</tfoot>
</table>
