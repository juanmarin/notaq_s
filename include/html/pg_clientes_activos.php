<?php
	session_start();
	$UserName = $_SESSION["USERNAME"];
	echo $UserName;
?>
<p class="title">Clientes &raquo; Listado de clientes activos</p>
<table>
<caption>CUENTAS ABIERTAS</caption>
<thead>
	<tr>
		<th>ID</th>
		<th>CLIENTE</th>
		<th>CARGOS</th>
		<th></th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, cuentas.cantidad FROM clientes, cuentas WHERE clientes.id = cuentas.cliente AND cuentas.estado = 0 ORDER BY clientes.nombre ASC";
	$res = $db->query($sql);
	$num_rows = mysql_num_rows($res);
	while($r = $db->fetchNextObject($res)){
		?>
		<tr>
			<td style="text-align:center"> <?echo $r->id;?></td>
			<td style="text-align:center"><?echo $r->nombre . " ". $r->apellidop ." " .$r->apellidom;?></td>
			<td style="text-align:center">$ <?echo $r->cantidad;?></td>
			<td width="80"><a href="?pg=2e&cl=<?echo $r->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
		</tr>
		<?
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th colspan="4"></th>
	</tr>
</tfoot>
</table>
