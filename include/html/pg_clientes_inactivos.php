<p class="title">Portada &raquo; Listado de Clientes Inactivos</p>
<table>
<caption>CLIENTES INACTIVOS</caption>
<thead>
	<tr>
		<th>ID</th>
		<th>CLIENTE</th>
		<th colspan="2">ACCIONES</th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT * FROM clientes WHERE NOT EXISTS (SELECT * FROM cuentas WHERE clientes.id = cuentas.cliente AND cuentas.estado = 0)";
	$res = $db->query($sql);
	$num_rows = mysql_num_rows($res);
	while($r = $db->fetchNextObject($res)){
		?>
		<tr>
			<td style="text-align:center"><?= $r->id;?></td>	
			<td style="text-align:center"><?= $r->nombre ." ". $r->apellidop ." " .$r->apellidom;?></td>
			<td>&nbsp;</td>
			<td width="80"><a href="?pg=2e&cl=<?= $r->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
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
