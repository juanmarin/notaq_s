<?php
@session_start();
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
if ($UserLevel == 0) {
	$qcobrador = "";
?>
<table>
<caption>REPORTE DE COBRADORES</caption>
<thead>
	<tr>
		<th>COBRADOR</th>
		<th>C. ASIGNADOS</th>
		<th>C. CORRIENTE</th>
		<th>C. VENCIDOS</th>
		<th>TOTAL AVANCE %</th>
	</tr>
</thead>
<tbody>
<?php
		//// ESTADISTICOS DE COBRADORES/////
		require_once("include/php/sys_db.class.php");
		require_once("include/php/fun_global.php");
		require_once("include/conf/Config_con.php");
		$fecha = date("Y-m-d");
		$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
		$sql = "SELECT mymvcdb_users.username AS cobrador, COUNT(clientes.c_cobrador) AS mis_ctes
			FROM mymvcdb_users,clientes 
			WHERE mymvcdb_users.username = clientes.c_cobrador
			AND clientes.activo = 1
			GROUP BY clientes.c_cobrador";
		$res = $db->query($sql);
		while($cob=$db->fetchNextObject($res))
		{
			?>
			<tr>
				<td><?php echo $cob->cobrador;?></td>
				<td align="center"> <?php echo $cob->mis_ctes; ?></td>
				<?php
					$sql2="SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidop, clientes.demanda, cuentas.cliente, clientes.c_cobrador, 
				cuentas.cobrador, cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha, 
				SUM(pagos.pago) AS pago, pagos.estado
				FROM clientes, cuentas, pagos 
				WHERE
					clientes.id = cuentas.cliente 
					AND clientes.demanda != 1 
					AND cuentas.id = pagos.cuenta 
					AND cuentas.estado = 0 
					AND pagos.estado = 0 
					AND pagos.fecha < '".$fecha."'
					AND clientes.c_cobrador='".$cob->cobrador."'
					GROUP BY pagos.cliente 
					ORDER BY clientes.nombre ASC";
					$res2 = $db->query($sql2);
					$morosos = mysql_num_rows($res2);
					while ($mor = $db->fetchNextObject($res2))
					{
						$corriente=$cob->mis_ctes-$morosos;
						$avance = ($corriente/$cob->mis_ctes)*100;
						?>
						
					<?php
					}	
				?>
			<td align="center"><?php echo $corriente;?></td>
			<td style="background-color: #FA5858;" align="center"><?php echo $morosos;?></td>
			<td align="right"><?php echo number_format($avance, 2)."%";?></td>
			</tr>
		    	<?php
		}
?>
</tbody>
</table>

<?php
} else {
	$qcobrador = "AND c_cobrador = '$UserName'";
?>
<p class="title">Portada &raquo; Listado de clientes</p>
<table>
<caption>CUENTAS ABIERTAS</caption>
<thead>
	<tr>
		<th>CLIENTE</th>
		<th>CARGOS</th>
		<th></th>
		<th></th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, 
					clientes.apellidom, clientes.c_cobrador, cuentas.cantidad 
					FROM clientes, cuentas 
					WHERE clientes.id = cuentas.cliente 
					$qcobrador
					AND cuentas.estado = 0 
					ORDER BY clientes.id ASC";
	$res = $db->query($sql);
	$num_rows = mysql_num_rows($res);
	while($r = $db->fetchNextObject($res)){
		?>
		<tr>
			<td style="text-align:center"> <?= $r->id;?></td>
			<td style="text-align:center"><?= $r->nombre . " ". $r->apellidop ." " .$r->apellidom;?></td>
			<td style="text-align:center">$ <?= $r->cantidad;?></td>
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
<?php
}
?>
