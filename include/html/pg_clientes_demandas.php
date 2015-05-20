<?php
	session_start();
	$UserName = $_SESSION["USERNAME"];
	$UserLevel = $_SESSION["U_NIVEL"];
?>
<p class="title">Portada &raquo; Listado de Clientes en Demanda</p>
<table>
<caption>CUENTAS EN PROCESO DE DEMANDA</caption>
<thead>
	<tr>
		<th>ID</th>
		<th>CLIENTE</th>
		<th colspan="2">ACCIONES</th>
	</tr>
</thead>
<tbody>
	<?php
    	$fecha = date("Y-m-d");
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
<<<<<<< HEAD
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, demandas.cliente_id AS id 
=======
	if($UserLevel == 0){
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidop, demandas.cliente_id AS id 
>>>>>>> 8a02758a4eee9e75fb651c00a48757ab6eca133e
            FROM clientes, demandas
            WHERE
                clientes.id = demandas.cliente_id
            ORDER BY clientes.nombre ASC";
	}elseif($UserLevel != 0){
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidop, clientes.c_cobrador, demandas.cliente_id AS id 
            FROM clientes, demandas
            WHERE
                clientes.id = demandas.cliente_id
		AND clientes.c_cobrador = '".$UserName."'
            ORDER BY clientes.nombre ASC";
	} {
	}
	$res = $db->query($sql);
	$num_rows = mysql_num_rows($res);
	while($r = $db->fetchNextObject($res)){
	?>
		<tr>
<<<<<<< HEAD
			<td style="text-align:center"> <?= $r->id;?></td>	
			<td style="text-align:center"><?= $r->nombre ." ". $r->apellidop ." " .$r->apellidom;?></td>
			<td width="80"><a href="?pg=2e&cl=<?= $r->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
			<!--
			<?php /*
			<td width="80"><a href="include/php/sys_modelo.php?cte=<?= $r->id;?>&cta=<?= $r->cuenta;?>&action=recargo_elimina" class="tboton sombra esqRedondas recargos">Elimina</a></td>
			*/ ?>
			-->
		</tr>
		<?php
=======
			<td style="text-align:center"> <?php echo $r->id;?></td>	
			<td style="text-align:center"><?php echo $r->nombre ." ". $r->apellidop ." " .$r->apellidom;?></td>
			<td width="80"><a href="?pg=2e&cl=<?php echo $r->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
		</tr>
	<?php
>>>>>>> 8a02758a4eee9e75fb651c00a48757ab6eca133e
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th colspan="5"></th>
	</tr>
</tfoot>
</table>
