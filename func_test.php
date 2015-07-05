<?php
	require_once("include/php/fun_global.php");
	
		$cantidad = 1000;
		$monto1 = 150; 
		$plazo1 = 6;
		$plazo2 = 5;
		$monto2 = 100;
		$tipo_pago = "SEMANAL";
		$hoy = date("Y-m-d");
		
	$datosPrestamo = calculamonto($cantidad, $monto1, $monto2, $plazo1, $plazo2, $tipo_pago);
	
		echo "Prestamo: ".$datosPrestamo['saldo']."<br/>";
		echo "Monto Int.: ".$datosPrestamo['int_moneda']."<br/>";
		echo "Interes Tot: ".$datosPrestamo['interes']."<br/>";
		echo "Plazo Real: ".$datosPrestamo['tiempo']."<br/>";

		echo var_dump($datosPrestamo)."</br>";
		$date = "0000-00-00";
		echo date("d-m-Y", strtotime($date))."</br>";
?>
<table border="1">
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
				<td> <?php echo $cob->mis_ctes; ?></td>
				<?php
				#Buscando el total de clientes Morosos
				/*
				$sql2 = "SELECT mymvcdb_users.username, clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidop, 
				cuentas.cliente, clientes.c_cobrador, cuentas.cobrador, cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha, 
				SUM(pagos.pago) AS pago, pagos.estado
				FROM mymvcdb_users,clientes, cuentas, pagos 
				WHERE
				mymvcdb_users.username = clientes.c_cobrador
				AND clientes.id = cuentas.cliente
				AND cuentas.id = pagos.cuenta 
				AND cuentas.estado = 0 
				AND pagos.estado = 0 
				AND pagos.fecha < '".$fecha."'";
				*/
				$sql2=" SELECT count(*) morosos FROM cuentas c LEFT JOIN pagos p ON c.id=p.id 
				WHERE c.estado = 0 AND c.cobrador='".$cob->cobrador."' AND p.estado=0 AND p.fecha <= '$hoy'";
				$res2 = $db->query($sql2);
				while ($mor = $db->fetchNextObject($res2))
				{
					$corriente=$cob->mis_ctes-$mor->morosos;
					?>
					<td><?=$corriente;?></td>
					<td><?=$mor->morosos;?></td>
					<td align="right"><?=moneda(($corriente/$cob->mis_ctes)*100);?></td>
					<?php
				}	
				?>
			</tr>
		    	<?php
		}
?>
</tbody>
</table>
<?php
/*
		echo $cob->cobrador."----->".$cob->mis_ctes."</br>";
		$cuenta = 144;
		$cliente = 120;/*
		    if (hayRecargos($cuenta, $cliente) == 1) {
		    	echo "tiene Recargos";
		    }

				*/
				/*
		    $sql = "SELECT COUNT(clientes.c_cobrador) AS cobradores, mymvcdb_users.nombre, mymvcdb_users.username, count( clientes.id )
				FROM `clientes`
				JOIN mymvcdb_users ON mymvcdb_users.username = clientes.c_cobrador
				AND mymvcdb_users.nivel =3
				GROUP BY clientes.c_cobrador
				ORDER BY mymvcdb_users.nombre";
				$res = $db->query($sql);
				while ($cob = $db->fetchNextObject($res)) {
				echo $cob->username."----->".$cob->cobradores."</br>";
}
         echo "//////////////////////////////////////////////////////////////////////////////////////////////////////////";
         
         
				$sql2 = "SELECT clientes.c_cobrador, mymvcdb_users.nombre, mymvcdb_users.username, count( DISTINCT(clientes.id) )
				FROM `clientes`
				JOIN mymvcdb_users ON mymvcdb_users.username = clientes.c_cobrador AND mymvcdb_users.nivel =3
				JOIN pagos ON pagos.cliente = clientes.id AND pagos.estado =0 AND pagos.fecha <= '2015-07-03'
				GROUP BY clientes.c_cobrador
				ORDER BY mymvcdb_users.nombre";
				$res2 = $db->query($sql2);
				while ($cob2 = $db->fetchNextObject($res2)) {
				var_dump($cob2);
}

/*

ESTE ULTIMO
SELECT clientes.c_cobrador, mymvcdb_users.nombre, mymvcdb_users.username, count( DISTINCT(clientes.id) )
FROM `clientes`
JOIN mymvcdb_users ON mymvcdb_users.username = clientes.c_cobrador AND mymvcdb_users.nivel =3
JOIN pagos ON pagos.cliente = clientes.id AND pagos.estado =0 AND pagos.fecha < '2015-06-01'
WHERE demanda !=1
GROUP BY clientes.c_cobrador
ORDER BY mymvcdb_users.nombre
*/
		    
?>
