<?php 
	header('Content-Type: text/html; charset=iso-8859-1'); 
?>
<style>
	table tbody tr th td {text-align: center;}
</style>
<form action="include/php/sys_modelo.php" method="post">
<table>
<caption>Condonar Recargos</caption>
<thead>
	<tr>
		<th align="center">#</th>
		<th align="center">Fecha</th>
		<th align="center">D. Venc.</th>
		<th align="center">Monto</th>
		<th align="center">Acciones</th>
	</tr>
</thead>
<tbody>
<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM recargos WHERE estado = 0 AND cuenta = ".$_GET["cta"]);
	echo $db->query;
	$i = 0;
	while ($ln = $db->fetchNextObject($result)){
		$i++;
?>

	<tr>
		<td align="center"><?php echo $i; ?></td>
		<td align="center"><?php echo date("d-m-Y", strtotime($ln->pago)); ?></td>
        <td align="center"><?php echo $ln->dias_atraso; ?></td>
        <td align="center"><?php echo "$".number_format($ln->monto, 2); ?></td>
        <td align="center"><input type="checkbox" name="ids[]" value="<?= $ln->id; ?>"></td>
	</tr>
	<?php
	}
	?>
</tbody>

<tfoot>
	<tr>
		<th colspan="6">
		<input type="hidden" name="cte" value="<?php echo $_GET['cte']; ?>" />
		<input type="hidden" name="cta" value="<?php echo $_GET['cta']; ?>" />
		<input type="hidden" name="pago" value="<?php echo $ln->pago; ?>" />
		<input type="hidden" name="action" value="pago_elimina" />
		<input type="submit" name="eliminar_cuenta" value="Condonar Recargo(s)" />
		</th>
	</tr>				
</tfoot>
<table>
</form>
