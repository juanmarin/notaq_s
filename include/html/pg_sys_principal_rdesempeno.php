<table class="table">
	<caption>Reporte de desempeño desde el <?=$_POST["fi"];?> al <?=$_POST["ff"];?></caption>
	<thead>
		<tr>
			<th>COBRADOR</th>
			<th>TOTAL</th>
			<th>COBROS EN FECHA</th>
			<th>COBROS FUERA DE FECHA</th>
			<th>POR COBRAR</th>
			<th>AVANCE</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$sql="SELECT cu.cobrador, count(pa.id) total 
			FROM cuentas cu, pagos pa 
			WHERE cu.id=pa.cuenta
			AND pa.fechaPago BETWEEN CAST('".$_POST["fi"]."' AS DATE) AND CAST('".$_POST["ff"]."' AS DATE)
			GROUP BY cu.cobrador ORDER BY cu.cobrador";
		//echo $sql;
		$res=$db->query($sql);
		while($rd=$db->fetchNextObject($res))
		{
			#INFORMACION PRINCIPAL DE REPORTE DE DESEMPEñO, VENDEDOR Y TOTAL COBRADO
			echo'<tr>';
			echo'<td align="center">'.$rd->cobrador.'</td>';
			echo'<td align="center">'.$rd->total.'</td>';
			$totalavance = $rd->total;

			#BUSCANDO COBROS EN FECHA
			$sql="SELECT count(*) cobrosef
			FROM cuentas cu, pagos pa
			WHERE cu.id=pa.cuenta AND pa.estado=1 AND pa.fechaPago BETWEEN CAST('".$_POST["fi"]."' AS DATE) AND CAST('".$_POST["ff"]."' AS DATE)
			AND cu.cobrador='".$rd->cobrador."'
			AND pa.fechaPago<=pa.fecha";
			$re2=$db->query($sql);
			while($get=$db->fetchNextObject($re2))
			{
				$cobrosenfecha = $get->cobrosef;
				echo'<td align="center">'.$get->cobrosef.'</td>';
			}

			#BUSCANDO COBROS FUERA DE FECHA
			$sql="SELECT count(*) cobrosff
			FROM cuentas cu, pagos pa
			WHERE cu.id=pa.cuenta AND pa.estado=1 AND pa.fechaPago BETWEEN CAST('".$_POST["fi"]."' AS DATE) AND CAST('".$_POST["ff"]."' AS DATE)
			AND cu.cobrador='".$rd->cobrador."'
			AND pa.fechaPago>pa.fecha";
			$re2=$db->query($sql);
			while($get=$db->fetchNextObject($re2))
			{
				echo'<td align="center">'.$get->cobrosff.'</td>';
			}

			#BUSCANDO COBROS PENDIENTES
			$sql="SELECT count(*) cobrospc
			FROM cuentas cu, pagos pa
			WHERE cu.id=pa.cuenta AND pa.estado=0 AND pa.fechaPago BETWEEN CAST('".$_POST["fi"]."' AS DATE) AND CAST('".$_POST["ff"]."' AS DATE)
			AND cu.cobrador='".$rd->cobrador."'";
			$re2=$db->query($sql);
			while($get=$db->fetchNextObject($re2))
			{
				echo'<td align="center">'.$get->cobrospc.'</td>';
			}
			
			#CALCULANDO EL PORCCENTAJE DE AVANCE
			$pavance = ( $cobrosenfecha / $totalavance ) * 100;
			$tbl_color = semaforo(number_format($pavance, 2));
			?><td style='background-color:<?php echo $tbl_color;?>' align='right'><?php moneda($pavance);?> %</td><?php
			
			echo'</tr>';
		}
		?>
	</tbody>
</table>
