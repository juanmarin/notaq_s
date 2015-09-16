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
	<form action="" method="post">
	
	</form>
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
		</tr>
	</thead>
	<tbody>
		<?php
		
		$sql = "SELECT 
			cliente, nombre, cobrador, cta_id, p_id, fechacob, fecha, pagos, abonos, recargos 
			FROM
			(SELECT 
			cl.id cliente, concat(cl.nombre,' ',cl.apellidop,' ',cl.apellidom) nombre, cl.c_cobrador cobrador
			, pa.id p_id, pa.cuenta cta_id, pa.fecha fechacob, pa.fechaPago fecha, pa.pago_real pagos, pa.estado ep, pa.reportado rp
			, ab.idpago, ab.fecha fechaabono, ab.abono abonos, ab.reportado ra
			, re.fecha fecharecargo, re.monto_saldado recargos, re.estado er, re.reportado rr
			FROM cuentas cu
			left join clientes cl on cl.id=cu.cliente
			left join pagos pa on pa.cuenta=cu.id
			left join abono ab on ab.idpago=pa.id
			left join recargos re on re.pago_id=pa.id
			WHERE cu.estado=0) AS cobros
			WHERE ((ep=1 AND fecha <='".$hoy."' AND rp=0) 
			OR (fechaabono is not null AND fechaabono <= '".$hoy."' AND ra=0) 
			OR (er!=0 and fecharecargo <='".$hoy."' and rr=0))
			$clcobrador";
	
		$result = $db->query($sql);
		$totGlobal=0;
		while ($ln = $db->fetchNextObject($result)){
			$totpagos += $ln->pagos;
			$totabonos += $ln->abonos;
			$totrecargos += $ln->recargos;

			if ($ln->abonos == "") {
				$ln->abonos = 0.00;
			}
			if ($ln->recargos == "") {
				$ln->recargos = 0.00;
			}
			
			?>
			<tr>
			<th colspan="3" width="250px" style="text-align: center"><?= strtoupper($ln->nombre) ;?></th>
			<!--
			<th colspan="3" width="250px" style="text-align: center"><?= strtoupper($ln->cobrador) ;?></th>
			-->
			<th width="100px" style="text-align: center;"><?= $ln->fechacob;?></th>
			<th width="100px" style="text-align: center;"><?= $ln->fecha;?></th>
			<th width="100px" style="text-align: center;"><?= "&#36;"; echo number_format($ln->pagos,2);?></th>
			<th width="100px" style="text-align: center;"><?= "&#36;"; echo number_format($ln->abonos,2);?></th>
			<th width="100px" style="text-align: center;"><?= "&#36;"; echo number_format($ln->recargos,2);?></th>
			</tr>
			<?php
		}
			$totGlobal = ($totpagos+$totabonos+$totrecargos);
		?>
	
	
	<tr>
	<th style="text-align:left" colspan="4">Total Pagos </th>
	<th style="text-align:right" colspan="4">$ <?=number_format($totpagos,2);?></th>
	</tr>
	<tr>
	<th style="text-align:left" colspan="4">Total Abonos </th>
	<th style="text-align:right" colspan="4">$ <?=number_format($totabonos,2);?></th>
	</tr>
	<tr>
	<th style="text-align:left" colspan="4">Total Recargos </th>
	<th style="text-align:right" colspan="4">$ <?=number_format($totrecargos,2);?></th>
	</tr>
	<tr>
	<th style="text-align:left" colspan="4">Total a Entregar </th>
	<th style="text-align:right" colspan="4">$ <?=number_format($totGlobal,2);?></th>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<form action="include/php/sys_modelo.php" method="post">
			<input type="hidden" name="action" value="corte_caja">
			<input type="hidden" name="cobrador" value="<?php echo $_POST["cobrador"]; ?>">
			<input type="hidden" name="supervisor" value="<?php echo $UserName; ?>" />
			<input type="hidden" name="totpagos" value="<?php echo $totpagos;?>" />
			<input type="hidden" name="totabonos" value="<?php echo $totabonos;?>" />
			<input type="hidden" name="totrecargos" value="<?php echo $totrecargos;?>" />
			<input type="hidden" name="totGlobal" value="<?php echo $totGlobal;?>" />
			<input type="hidden" name="consulta" value="<?php echo $sql;?>" />
			<th colspan="8"><input type="submit" value="Realizar corte" name="enviar" /></th>
		</form>
	</tr>
	</tfoot>
	<table>
<?php
}else{
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
	<th colspan="4"><input type="submit" value="Mostrar Reporte" name="enviar" /></th>
	</tr>
	</tfoot>
	</table>
	</form>
	<?php
}
?>
