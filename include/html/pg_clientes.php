<?php
@session_start();
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
?>
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
	if($_SESSION["U_NIVEL"] == 0){
		$sql = "SELECT * FROM clientes WHERE activo = 1 ORDER BY nombre ASC";
	} else {
		$sql = "SELECT * FROM clientes WHERE activo = 1 AND c_cobrador = '".$_SESSION["USERNAME"]."' ORDER BY nombre ASC";
	}
	$result = $db->query($sql);
	while ($ln = $db->fetchNextObject($result))
	{
		?>
		<tr>
			<th style="text-transform: uppercase;"><?= $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?></th>
			<td width="80"><a href="?pg=2e&cl=<?= $ln->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
			<td width="80"><a href="include/html/box_cliente.php?width=700&height=530&cl=<?= $ln->id;?>" class="thickbox tboton sombra esqRedondas detalles" title="Detalles de cliente">Detalles</a></td>
			<td width="80"><a href="?pg=2b&cl=<?= $ln->id;?>" rel="<?= $ln->id;?>" class="tboton sombra esqRedondas editar">Editar</a></td>
			<td width="80"><a href="include/html/box_cliente_elim.php?width=480&height=250&cl=<?= $ln->id;?>" class="thickbox tboton sombra esqRedondas eliminar" title="Eliminar cliente">Eliminar</a></td>
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
