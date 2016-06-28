<title>Confianzp - inconsistencias de cobradores</title>
<a href="./cobradores.php">Inicio</a>
<?php
	//// ESTADISTICOS DE COBRADORES/////
	require_once("include/php/sys_db.class.php");
	require_once("include/php/fun_global.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);

	//ACTUALIZAR COBRADOR DE CUENTA CON INFORMACION DEL CLIENTE
	if($_GET["cliente"])
	{
		$sql = "UPDATE cuentas SET cobrador = '".$_GET["cliente"]."' WHERE id = ".$_GET["cu"];
		$db->execute($sql);
	}
	//ACTUALIZAR COBRADOR DE CLIENTE CON INFORMACION DE LA CUENTA
	if($_GET["cuenta"])
	{
		$sql = "UPDATE clientes SET c_cobrador = '".$_GET["cuenta"]."' WHERE id = ".$_GET["cl"];
		$db->execute($sql);
	}
	//ACTUALIZAR TODAS LAS CUENTAS CON EL COBRADOR ASIGNADO AL CLIENTE
	if($_GET["setcliente"])
	{
		$sql = "UPDATE cuentas cu, clientes cl
				SET cu.cobrador = cl.c_cobrador
				WHERE cu.cliente=cl.id AND cu.cobrador!=cl.c_cobrador AND cu.estado=0";
		$db->query($sql);
	}
	//ACTUALIZAR TODOS LOS CLIENTES CON COBRADOR ASIGNADO EN LA CUENTA
	if($_GET["setcuenta"])
	{
		$sql = "UPDATE clientes cl, cuentas cu
				SET cl.c_cobrador = cu.cobrador
				WHERE cl.id=cu.cliente AND cu.cobrador!=cl.c_cobrador AND cu.estado=0";
		$db->query($sql);
	}
	
	?>

	<ul>
		<li><a href="?setcliente=update">Usar datos de cliente</a> - Actualizar todas las cuentas usando el cobrador asignado al cliente</li>
		<li><a href="?setcuenta=update">Usar datos de cuenta</a> - Actualizar todos los clientes usando el cobrador asignado a la cuenta</li>
	</ul>
	
	<table border=1>
	<caption>Cuentas con cobrador diferente asignado al cliente</caption>
	<thead>
		<tr>
			<th>Cliente</th>
			<th>idCliente</th>
			<th>Cobrador</th>
			<th>Usar datos de cliente</th>
			<th>idCuenta</th>
			<th>Cobrador</th>
			<th>Usar datos de cuenta</th>
			<th>Fecha</th>
		</tr>
	</thead>
	<tbody>
		<?php		
		$sql = "SELECT CONCAT(cl.nombre, ' ', cl.apellidop, ' ', cl.apellidom) AS cliente, cl.id idcliente, cl.c_cobrador clcobrador, cu.id idcuenta, cu.cobrador cucobrador, cu.fecha
				FROM cuentas cu, clientes cl 
				WHERE cu.cliente=cl.id AND cu.cobrador!=cl.c_cobrador AND cu.estado=0
				ORDER BY cl.nombre";
		$res = $db->query($sql);
		while($cob=$db->fetchNextObject($res))
		{
			?>
			<tr>
				<td><?=$cob->cliente;?></td>
				<td bgcolor="#add8e6"><?=$cob->idcliente;?></td>
				<td bgcolor="#add8e6"><?=$cob->clcobrador;?></td>
				<td bgcolor="#add8e6"><a href="?cliente=<?=$cob->clcobrador;?>&cu=<?=$cob->idcuenta;?>">Usar <?=$cob->clcobrador;?></a></td>
				<td bgcolor="#b8ffb8"><?=$cob->idcuenta;?></td>
				<td bgcolor="#b8ffb8"><?=$cob->cucobrador;?></td>
				<td bgcolor="#b8ffb8"><a href="?cuenta=<?=$cob->cucobrador;?>&cl=<?=$cob->idcliente;?>">Usar <?=$cob->cucobrador;?></a></td>
				<td><?=$cob->fecha;?></td>
			</tr>
		    <?php
		}
	?>
	</tbody>
	</table>

	<br />
	<br />
	
	<table border=1>
	<caption>Comparacion cobradores todas las cuentas</caption>
	<thead>
		<tr>
			<th>Cliente</th>
			<th>idCliente</th>
			<th>Cobrador</th>
			<th>idCuenta</th>
			<th>Cobrador</th>
			<th>fecha</th>
		</tr>
	</thead>
	<tbody>
		<?php		
		$sql = "SELECT CONCAT(cl.nombre, ' ', cl.apellidop, ' ', cl.apellidom) AS cliente, cl.id idcliente, cl.c_cobrador clcobrador, cu.id idcuenta, cu.cobrador cucobrador, cu.fecha
				FROM cuentas cu, clientes cl 
				WHERE cu.cliente=cl.id AND cu.estado=0
				ORDER BY cl.nombre";
		$res = $db->query($sql);
		while($cob=$db->fetchNextObject($res))
		{
			?>
			<tr>
				<td><?=$cob->cliente;?></td>
				<td bgcolor="#add8e6"><?=$cob->idcliente;?></td>
				<td bgcolor="#add8e6"><?=$cob->clcobrador;?></td>
				<td bgcolor="#b8ffb8"><?=$cob->idcuenta;?></td>
				<td bgcolor="#b8ffb8"><?=$cob->cucobrador;?></td>
				<td><?=$cob->fecha;?></td>
			</tr>
		    <?php
		}
	?>
	</tbody>
	</table>
	<?php
?>
