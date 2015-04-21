<?php
     $fecha = date('Y-m-d');
     #$fecha = '2011-09-12';
?>
<p class="title">Reportes &raquo; Reporte diario</p>
<form action="" method="post">
	<input type="submit" name="imprimir" value="Imprimir" />
</form>
<table>
<caption>Reporte Diario al <?php echo getFecha($fecha); ?></caption>
<thead>
	<tr>
		<th>Fecha</th>
		<th>Nombre</th>
		<th>Abono Capital</th>
		<th>Pago Inter&eacute;s</th>
		<th>Pago Total</th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, cuentas.id, cuentas.cliente, cuentas.cantidad, cuentas.npagos, pagos.fechaPago, pagos.pago_real, pagos.interes,
    pagos.estado FROM clientes, cuentas, pagos WHERE clientes.id = pagos.cliente AND clientes.id = cuentas.cliente AND pagos.fechaPago = '".$fecha."' AND pagos.pago_real > 0 AND
    (pagos.estado = 1 OR pagos.estado = 3) GROUP BY clientes.id";
	$result = $db->query($sql);
	$regs =  mysql_num_rows($result);
	if($regs == 0){
		?>
		<script type ="javascript">
			alert("No se recibieron pagos el dia de hoy");
		</script>
		<?php
	}else{
		while ($ln = $db->fetchNextObject($result)){
			$intPagado = getInteresPago2($ln->cantidad, $ln->npagos, $ln->interes);
			$aboCapital = ($ln->pago_real - $intPagado);
			$totCapCobrado += $aboCapital;
			$totGlobal += $ln->pago_real;
			$totIntCobrado += $intPagado;
			?>
			<tr>
				<th colspan="1" style="text-align: center"><?echo getFecha($ln->fechaPago);?></th>
				<th colspan="1" style="text-align: center"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
				<th colspan="1" style="text-align: center;"><?echo "&#36;"; echo moneda($aboCapital);?></th>
				<th colspan="1" style="text-align: center;"><?echo "&#36;"; echo moneda($intPagado);?></th>
				<th colspan="1" style="text-align: center;"><?echo "&#36;"; echo moneda($ln->pago_real);?></th>
			</tr>
			<?php
		}
		$db->close(); 
	}
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$salda = $db->query("SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, cuentas.id, cuentas.cliente, cuentas.cantidad, cuentas.npagos, pagos.fechaPago, pagos.pago_real, pagos.interes,
    pagos.estado FROM clientes, cuentas, pagos WHERE clientes.id = cuentas.cliente AND fecha_pago = '".$fecha."' AND (cuentas.estado = 1 OR cuentas.estado= 2) ORDER BY fecha_pago ASC");
	$ctasNum = mysql_num_rows($salda);
	?>
</tbody>
</table>

<br />

<table>
<caption>Cuentas Saldadas</caption>
<thead>
	<tr>
		<th colspan="5" style="text-align:center"> CUENTAS SALDADAS <? echo $ctasNum; ?> </th>
	</tr>
		<th colspan="5" style="text-align:center">&nbsp;</th>
	<tr>
</thead>
<tbody>
	<?php
	while ($ln = $db->fetchNextObject($salda)){
		$totSaldado += $ln->monto_saldado;
		$saldaInteres = getInteresPago2($ln->cantidad, $ln->npagos, $ln->interes);
		$saldaCapital = ($ln->monto_saldado - $saldaInteres);	
		$Acum_Salda_Capital += $saldaCapital;
		$Acum_Salda_Interes += $saldaInteres;
		?>
		<tr>
		<th width="250px" style="text-align: center;"><? echo $ln->fecha_pago;?></th>
		<th width="250px" style="text-align: center"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
		<th width="250px" style="text-align: center;"><?echo "&#36;"; echo $saldaCapital;?></th>
		<th width="250px" style="text-align: center;"><?echo "&#36;"; echo $saldaInteres;?></th>
		<th width="250px" style="text-align: center;"><?echo "&#36;"; echo $ln->monto_saldado;?></th>
		</tr>
		<?php
	}
?>
</tbody>
<tfoot>
	<tr>
		<th colspan="2" style="text-align: left"> Totales </th>
		<th colspan="1" style="text-align: center"><?echo "&#36;"; echo moneda($totCapCobrado);?></th>
		<th colspan="1" style="text-align: center"><?echo "&#36;"; echo moneda($totIntCobrado);?></th>
		<th colspan="1" style="text-align: center"><?echo "&#36;"; echo moneda($totGlobal);?></th>
	</tr>
</tfoot>
</table>
<?php
if($_POST["imprimir"]){
	## IMPRESION ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	## FUNCIONES ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	function getNpago($cuenta, $pago){
		$sql = "SELECT id FROM pagos WHERE cuenta = ".$cuenta;
		$res = mysql_query($sql);
		$cnt = 0;
		while($r = mysql_fetch_array($res)) {
			$cnt++;
			if($r["id"] == $pago){
				$np = $cnt;
			}
		}
		return $np;
	}
	
	#[[ OBTENIENDO DATOS DE PAGINACION ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	$sql = "SELECT nombre 
		FROM clientes, cuentas, pagos
		WHERE clientes.id=cuentas.cliente AND pagos.cuenta=cuentas.id
		AND pagos.estado=1 AND pagos.fechaPago = '".$fecha."'";
	$res = $db->query($sql);
	$num = $db->numRows();
	$paginas = $num / 7;
	$paginas = (int)$paginas;
	if(($num % 7) > 0){
		$paginas+=1;
	}
	$regs = 0;
	$chkpt = 0;
	$alto = 100;
	$li = $alto;
	
	for ($i=1; $i<=$paginas; $i++){
		#[[ OBTENIENDO DATOS DE RELLENO ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
		$sql = "SELECT nombre, apellidom, apellidop, npagos, pagos.pago_real, pagos.interes, cuentas.id as cid, pagos.id as pid,
			dias_pago, tipo_pago
			FROM clientes, cuentas, pagos
			WHERE clientes.id=cuentas.cliente AND pagos.cuenta=cuentas.id
			AND pagos.estado=1 AND pagos.fechaPago = '".$fecha."' LIMIT ".$regs.", 7";
		include 'include/php/imprimeReportediario.php';
		$regs += 7;
		sleep(1);
	}
}
?>
