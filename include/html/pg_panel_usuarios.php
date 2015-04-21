<p class="title">Panel &raquo; Listado de usuarios</p>
<table>
<caption>Listado completo de usuarios</caption>
<thead>
	<tr>
		<th>Nombre completo</th>
		<th colspan="3">Acciones</th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");

	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM mymvcdb_users");
	while ($ln = $db->fetchNextObject($result))
	{
		?>
		<tr>
			<th style="text-transform: uppercase;"><?echo $ln->nombre;?></th>
			<td width="80"><a href="include/html/box_usuarios.php?width=700&height=350&u=<?echo $ln->userID;?>" class="thickbox tboton sombra esqRedondas detalles" title="Detalles de usuario">Detalles</a></td>
			<td width="80"><a href="include/html/box_usuario_elim.php?width=480&height=250&usr=<?echo $ln->userID;?>" class="thickbox tboton sombra esqRedondas eliminar" title="Eliminar usuario">Eliminar</a></td>
		</tr>
		<?php
	}
	$db->close();
	?>
</tbody>

<tfoot>
	<tr>
		<th colspan="4">&nbsp;</th>
	</tr>				
</tfoot>
<table>
