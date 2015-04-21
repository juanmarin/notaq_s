<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<style>
	table tbody tr th{text-align: right;}
</style>
<form name="guardar_nota" action="include/php/sys_modelo.php" method="post">
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
	$result = $db->query("SELECT * FROM clientes WHERE id = ".$_GET["cl"]." LIMIT 0, 1");
	while ($ln = $db->fetchNextObject($result))
	{
		$cliente = $ln->id;
		?>
		<input type="hidden" name="action" value="nota_nueva" />
		<input type="hidden" name="cl" value="<?echo $cliente; ?>" />
		<tr>
			<th width="195">Nota para: </th><td><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="nota" cols="37" rows="10"></textarea></td>
		</tr>
		<?php
	}
	?>
</tbody>

<tfoot>
	<tr>
		<th colspan="4"><input type="submit" name="guardar_nota" value="Guardar" /></th>
	</tr>				
</tfoot>
<table>
</form>