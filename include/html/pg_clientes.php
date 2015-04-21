
<p class="title">Clientes &raquo; Listado de clientes</p>
<table>
<caption>Listado completo de clientes</caption>
<thead>
	<tr>
		<th>Nombre completo</th>

		<th colspan="4">Acciones</th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");

	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM clientes WHERE activo = 1 ORDER BY nombre ASC");
	while ($ln = $db->fetchNextObject($result))
	{
		?>
		<tr>
			<th style="text-transform: uppercase;"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?></th>
			<td width="80"><a href="?pg=2e&cl=<?echo $ln->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
			<td width="80"><a href="include/html/box_cliente.php?width=700&height=530&cl=<?echo $ln->id;?>" class="thickbox tboton sombra esqRedondas detalles" title="Detalles de cliente">Detalles</a></td>
			<td width="80"><a href="?pg=2b&cl=<?echo $ln->id;?>" rel="<?echo $ln->id;?>" class="tboton sombra esqRedondas editar">Editar</a></td>
			<td width="80"><a href="include/html/box_cliente_elim.php?width=480&height=250&cl=<?echo $ln->id;?>" class="thickbox tboton sombra esqRedondas eliminar" title="Eliminar cliente">Eliminar</a></td>
		</tr>
		<?php
	}
	$db->close();
	?>
</tbody>

<tfoot>
	<tr>
		<th colspan="5">&nbsp;</th>
	</tr>				
</tfoot>
<table>
