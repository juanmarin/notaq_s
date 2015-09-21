<?php
@session_start();
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
?>
<p class="title">Portada &raquo; Listado Corte Caja</p>
<table>
<caption>CORTES DE CAJA</caption>
<thead>
	<tr>
		<th>FECHA</th>
		<th>SUP.</th>
		<th>COB.</th>
		<th>PAGO</th>
		<th>ABONO</th>
		<th>RECARGO</th>
		<th>TOTAL</th>
		<th>ACCIONES</th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	
	$sql = "SELECT * FROM corte_caja";
	$res = $db->query($sql);
	$num_rows = mysql_num_rows($res);
	while($r = $db->fetchNextObject($res)){
		?>
		<tr>
			<td style="text-align:center"><?= date("d-m-Y", strtotime($r->created_at));?></td>
			<td style="text-align:center"><?= $r->recibido_x;?></td>	
			<td style="text-align:center"><?= $r->cobrador;?></td>
			<td style="text-align:center">$<?= number_format($r->totpagos,2);?></td>	
			<td style="text-align:center">$<?= number_format($r->totabonos,2);?></td>
			<td style="text-align:center">$<?= number_format($r->totrecargos,2);?></td>
			<td style="text-align:center">$<?= number_format($r->totglobal,2);?></td>
			<?php
				$created = str_replace(" ","_",$r->created_at);
			?>
			<td><a href="include/fpdf/reportes/c_caja_<?=$r->cobrador."_".$created;?>.pdf" target="_blank" style="color:000">DESCARGAR</a></td>
		</tr>
		<?php
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th colspan="4"></th>
	</tr>
</tfoot>
</table>
