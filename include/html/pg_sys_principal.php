<?php
@session_start();
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
?>
<p class="title">Portada &raquo; Cuadro de Avance</p>
<?php
if ($UserLevel == 0) {
	$clcobrador = "";
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
			<td style="background-color: #7DB77B"align="center"><?php echo $corriente;?></td>
			<td style="background-color: #F78181;" align="center"><?php echo $morosos;?></td>
			<td align="right"><?php echo number_format($avance, 2)."%";?></td>
			</tr>
		    	<?php
		}
?>
</tbody>
</table>

<?php
} else {
	$clcobrador = "AND c_cobrador = '$UserName'";
?>
<table>
<caption>COBRADOR: &nbsp; <b><?php echo $user->userData[6];?></b></caption>
<thead>
	<tr>
		<th>C. ASIGNADOS</th>
		<th>C. CORRIENTE</th>
		<th>C. VENCIDOS</th>
		<th>TOTAL AVANCE %</th>
	</tr>
</thead>
<tbody>
	<?php
	$fecha = date("Y-m-d");
		#Buscando los clientes asignados al cobrador
		require_once("include/php/sys_db.class.php");
		require_once("include/php/fun_global.php");
		require_once("include/conf/Config_con.php");
			$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
			$sql = "SELECT * FROM clientes WHERE activo = 1 $clcobrador ORDER BY nombre ASC";
			$res = $db->query($sql);
			$mis_ctes = mysql_num_rows($res);

		#Buscando el total de clientes Morosos
			$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidop, clientes.demanda, cuentas.cliente, clientes.c_cobrador, 
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
				$clcobrador
			GROUP BY pagos.cliente 
			ORDER BY clientes.nombre ASC";
			$res = $db->query($sql);
			$mis_morosos = mysql_num_rows($res);
			$mis_corriente = ($mis_ctes - $mis_morosos);
			$avance = ($mis_corriente/$mis_ctes)*100;

		?>
		<tr>
			<td style="text-align:center"> <?php echo $mis_ctes;?></td>
			<td style="background-color: #7DB77B"align="center"><?php echo $mis_corriente;?></td>
			<td style="background-color: #F78181;" align="center"><?php echo $mis_morosos;?></td>
			<td align="right"><?php echo number_format($avance, 2)."%";?></td>
		</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="4"></th>
	</tr>
</tfoot>
</table>
<br/>
<br/>
<table>
<caption>COBROS PARA EL DIA : <b><?php echo date("d-m-Y", strtotime($fecha)); ?></b> </caption>
<thead>
	<tr>
		<th>C. POR COBRAR</th>
		<th>C. REALIZADOS</th>
		<th>AVANCE DEL DIA %</th>
	</tr>
</thead>
<tbody>
	<?php
		#Buscando los clientes asignados al cobrador
		require_once("include/php/sys_db.class.php");
		require_once("include/php/fun_global.php");
		require_once("include/conf/Config_con.php");
			$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
		#Buscando el total de clientes a visitar hoy
			$sql = "SELECT clientes.id, clientes.c_cobrador, pagos.id, pagos.cliente, pagos.cuenta, pagos.fecha, pagos.estado 
			FROM clientes, pagos 
			WHERE clientes.id = pagos.cliente 
			AND pagos.fecha = '".$fecha."' 
			AND pagos.estado = 0
			$clcobrador";
			$res = $db->query($sql);
			$x_visitar = mysql_num_rows($res);
		#Buscando el total de clientes a visitados hoy
			$sql = "SELECT clientes.id, clientes.c_cobrador, pagos.id, pagos.cliente, pagos.cuenta, pagos.fecha, pagos.estado 
			FROM clientes, pagos 
			WHERE clientes.id = pagos.cliente 
			AND pagos.fechaPago = '".$fecha."' 
			AND pagos.estado = 1
			$clcobrador";
			$res = $db->query($sql);
			$visitados = mysql_num_rows($res);
			$avanced = ($visitados/$x_visitar)*100;
		?>
		<tr>
			<td style="text-align:center"> <?php echo $x_visitar;?></td>
			<td style="background-color: "align="center"><?php echo $visitados;?></td>
			<td align="right"><?php echo number_format($avanced, 2)."%";?></td>
		</tr>
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
