<p class="title">Panel &raquo; Planes de prestamos</p>
<table>
<caption>Planes de prestamos</caption>
<thead>
	<tr>
		<th>Cantidad</th>
		<th>Num. Pagos</th>
		<th>Abonos</th>
		<th>Tipo Pago</th>
		<th colspan="0">Acciones</th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");

	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM prestamos");
	while ($ln = $db->fetchNextObject($result))
	{
		switch($ln->tipoPagos)
			{
				case 1:
					$ln->tipoPagos = "Semanal";
				break;
			}
			
		?>
		<tr>
			<th width="80"><center><?echo "$ ".$ln->cantidad;?><center/></th>
			<th width="90"><center><?echo $ln->numPagos;?><center/></th>
			<th width="90"><center><?echo "$ ".$ln->montoPagos;?><center/></th>
			<th width="90"><center><?echo $ln->tipoPagos;?><center/></th>
			<td width="90"><center><a href="include/html/box_usuarios.php?width=700&height=350&u=<?echo $ln->id;?>" class="thickbox tboton sombra esqRedondas detalles" title="Detalles de usuario">Detalles</a><center/></td>
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
