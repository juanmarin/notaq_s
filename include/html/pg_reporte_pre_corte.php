<?php
@session_start();
require_once("include/php/sys_db.class.php");
require_once("include/php/fun_global.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
if($UserLevel==0){
	if(isset($_POST["cobrador"])){
		if($_POST["cobrador"]=="0"){
			$clcobrador="";
		}else{
			$clcobrador="AND cobrador = '".$_POST["cobrador"]."'";
		}
	}else{
		$clcobrador="";
	}
}else{
	$clcobrador="AND cobrador = '$UserName'";
}
if(isset($_POST['enviar']))
{
	$hoy = date("Y-m-d");
	?>
	<p class="title">Reportes &raquo; Reporte diario</p>
	<table>
	<caption>Reporte hasta el dia : <?php echo getFecha($hoy); ?> </caption>
	<thead>
		<tr>
		<th colspan="2">Nombre</th>
		<th colspan="2">Fecha Pago</th>
		<th>Fecha Cobro</th>
		<th>Pago</th>
		<th>Abono</th>
		<th>Recargo</th>
		<th>Abono recargo</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$sql = "SELECT 
				cliente, nombre, cobrador, cta_id, fechacob
				, fecha, p_id, pagos, abo_id, abonos, rec_id, recargos, ar_id, abrec
			FROM
				(SELECT 
				pa.cuenta cta_id, cl.id cliente, concat(cl.nombre,' ',cl.apellidop,' ',cl.apellidom) nombre, cl.c_cobrador cobrador, pa.fecha fechacob
				, case 
					when pa.pago_real>0		then pa.fechaPago
					when ab.abono>0			then ab.fecha
					when re.monto_saldado>0		then re.fecha
					when ar.abono>0			then ar.fecha_ab
					else ''
				end as fecha
				, pa.id p_id,		pa.fechaPago fechapago,	if(pa.reportado=0,pa.pago_real,0) pagos,	pa.reportado rp, pa.estado ep
				, ab.idabono abo_id,	ab.fecha fechaabono,	if(ab.reportado=0,ab.abono,0) abonos,		ab.reportado ra
				, re.id rec_id,		re.fecha fecharecargo,	if(re.reportado=0,re.monto_saldado,0) recargos,	re.reportado rr, re.estado er
				, ar.idabrec ar_id,	ar.fecha_ab fecha_abr,	if(ar.reportado=0,ar.abono,0) abrec,		ar.reportado rar
				FROM cuentas cu
				left join clientes cl on cl.id=cu.cliente
				left join pagos pa on pa.cuenta=cu.id
				left join abono ab on ab.idpago=pa.id
				left join recargos re on re.pago_id=pa.id
				left join abono_recargos ar on ar.idrec=re.id
				WHERE cu.estado=0 OR cu.fecha_pago='".$hoy."') AS cobros
			WHERE (
					(ep=1 AND fecha <='".$hoy."' AND rp=0 and pagos>0) 
				OR	(fechaabono is not null AND fechaabono <= '".$hoy."' AND ra=0 and abonos>0) 
				OR	(er!=0 and fecharecargo <='".$hoy."' and rr=0 and recargos > 0)
				OR	(fecha_abr<='".$hoy."' and rar=0 and abrec>0)
				) $clcobrador ORDER BY fecha DESC";
	
		$result = $db->query($sql);
		$num_rows = mysql_num_rows($result);
		$totGlobal=0;
		if ($num_rows > 0)
		{
			$chkpaid=0;
			$chkreid=0;
			$totpagos = 0;
			$totabonos = 0;
			$totrecargos = 0;
			$totabrecargos = 0;
			while ($ln = $db->fetchNextObject($result))
			{
				/* comprobar que no esten repetidos los pagos y recargos */
				$montopago=($ln->p_id==$chkpaid)?0:$ln->pagos;
				$montoreca=($ln->rec_id==$chkreid)?0:$ln->recargos;
				/* fin de la comprobacion */
				$totpagos += $montopago;
				$totabonos += $ln->abonos;
				$totrecargos += $montoreca;
				$totabrecargos += $ln->abrec;
				
				if ($ln->abonos == "") {
					$ln->abonos = 0.00;
				}
				if ($montoreca == "") {
					$montoreca = 0.00;
				}
				
				if ($ln->abrec == "") {
					$ln->abrec = 0.00;
				}
				?>
				<tr>
				<th colspan="3" width="250px" style="text-align: center"><?= strtoupper($ln->nombre) ;?></th>
				<!--
				<th colspan="3" width="250px" style="text-align: center"><?= strtoupper($ln->cobrador) ;?></th>
				-->
				<th width="100px" style="text-align: center;"><?= date("d-m-Y", strtotime($ln->fechacob));?></th>
				<th width="100px" style="text-align: center;"><?= date("d-m-Y", strtotime($ln->fecha));?></th>
				<th width="100px" style="text-align: center;"><?= "&#36;"; echo number_format($montopago,2);?></th>
				<th width="100px" style="text-align: center;"><?= "&#36;"; echo number_format($ln->abonos,2);?></th>
				<th width="100px" style="text-align: center;"><?= "&#36;"; echo number_format($montoreca,2);?></th>
				<th width="100px" style="text-align: center;"><?= "&#36;"; echo number_format($ln->abrec,2);?></th>
				</tr>
				<?php
				$chkpaid=$ln->p_id;
				$chkreid=$ln->rec_id;
			}
			$totGlobal = ($totpagos+$totabonos+$totrecargos+$totabrecargos);
		?>	
		<tr>
		<th style="text-align:left" colspan="4">Total Pagos </th>
		<th style="text-align:right" colspan="5">$ <?=number_format($totpagos,2);?></th>
		</tr>
		<tr>
		<th style="text-align:left" colspan="4">Total Abonos </th>
		<th style="text-align:right" colspan="5">$ <?=number_format($totabonos,2);?></th>
		</tr>
		<tr>
		<th style="text-align:left" colspan="4">Total Recargos </th>
		<th style="text-align:right" colspan="5">$ <?=number_format($totrecargos,2);?></th>
		</tr>
		<tr>
		<th style="text-align:left" colspan="4">Total Abonos de recargos </th>
		<th style="text-align:right" colspan="5">$ <?=number_format($totabrecargos,2);?></th>
		</tr>
		<tr>
		<th style="text-align:left" colspan="4">Total a Entregar </th>
		<th style="text-align:right" colspan="5">$ <?=number_format($totGlobal,2);?></th>
		</tr>
		</tbody>
		<tfoot>
		<tr>
		<form action="include/php/sys_modelo.php" method="post">
			<input type="hidden" name="action" 		value="corte_caja">
			<input type="hidden" name="cobrador" 	value="<?php echo $_POST["cobrador"]; ?>">
			<input type="hidden" name="supervisor" 	value="<?php echo $UserName; ?>" />
			<input type="hidden" name="totpagos" 	value="<?php echo $totpagos;?>" />
			<input type="hidden" name="totabonos" 	value="<?php echo $totabonos;?>" />
			<input type="hidden" name="totrecargos" value="<?php echo $totrecargos;?>" />
			<input type="hidden" name="totarecargos"value="<?php echo $totabrecargos;?>" />
			<input type="hidden" name="totGlobal" 	value="<?php echo $totGlobal;?>" />
			<input type="hidden" name="consulta" 	value="<?php echo $sql;?>" />
			<th colspan="9"><input type="submit" value="Realizar corte" name="enviar" /></th>
		</form>
		</tr>
		</tfoot>
		<table>
		<?php
	}
	else
	{
		?>
		<tr align="center">
			<th colspan="9">POR EL MOMENTO NO SE ENCONTRARON REGISTROS PARA MOSTRAR</th>
		</tr>
		<?php
	}
}else{
	if(isset($_POST["enviar2"])){
		echo "<p>Este reporte se encuentra actualmente en contrucción.</p>";
	}
	?>
	<p class="title">Reportes &raquo; Corte de caja</p>
	<form name="repoFechas" action="" method="post">
	<table>
	<caption>Entrega de cobros recibidos</caption>
	<thead>
	<tr>
	<th colspan="4"></th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($UserLevel==0){
		?>
		<tr>
		<th colspan="2">Seleccione un cobrador:</th>
		<td colspan="2">
		<select name="cobrador" id="cobrador">
		<option value="0">Todos</option>
		<?php
		$sql = "SELECT username FROM mymvcdb_users WHERE nivel=3";
		$res = $db->query($sql);
		while( $cob = $db->fetchNextObject($res) ){
			echo '<option value="'.$cob->username.'">'.$cob->username.'</option>';
		}
		?>
		</select>
		</td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
	<th colspan="4">
		<input type="submit" value="Mostrar Reporte" name="enviar" />
		<input type="submit" value="Mostrar Reporte 2" name="enviar2" />
	</th>
	</tr>
	</tfoot>
	</table>
	</form>
	<?php
}
?>
