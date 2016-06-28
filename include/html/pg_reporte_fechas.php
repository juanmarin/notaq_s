<?php
@session_start();
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
if($UserLevel==0){
	if(isset($_POST["cobrador"])){
		if($_POST["cobrador"]=="0"){
			$clcobrador="";
		}else{
			$clcobrador="AND clientes.c_cobrador = '".$_POST["cobrador"]."'";
		}
	}else{
		$clcobrador="";
	}
}else{
	$clcobrador="AND clientes.c_cobrador = '$UserName'";
}
if(isset($_POST['enviar'])){
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	?>
	<p class="title">Reportes &raquo; Reporte por fechas</p>
	<table>
	<caption>Reporte del dia : <?php echo getFecha($desde); ?> al <?php echo getFecha($hasta); ?></caption>
	<thead>
	<tr>
	<th>Fecha Pago</th>
	<th>Nombre</th>
	<th>Pago Total</th>
	<th>Cobrador</th>
	<th colspan="2">Acciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$sql="SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, 
	pagos.fechaPago, pagos.pago_real, pagos.interes, pagos.estado 
	FROM clientes, pagos 
	WHERE clientes.id = pagos.cliente 
	AND DATE(pagos.fechaPago) BETWEEN '".$desde."' AND '".$hasta."' 
	AND pagos.pago_real > 0 
	AND (pagos.estado = 1 OR pagos.estado = 3) 
	$clcobrador
	ORDER BY fechaPago ASC";
	$result = $db->query($sql);
	$totCapCobrado=0;
	$totGlobal=0;
	$totIntCobrado=0;
	while ($ln = $db->fetchNextObject($result)){
		$intPagado = getInteresPago($ln->pago_real, $ln->interes);
		$aboCapital = ($ln->pago_real - $intPagado);
		$totCapCobrado += $aboCapital;
		$totGlobal += $ln->pago_real;
		$totIntCobrado += $intPagado;
		?>
		<tr>
		<th width="350px" style="text-align: center;"><?= getFecha($ln->fechaPago);?></th>
		<th width="350px" style="text-align: center"><?= $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
		<th width="350px" style="text-align: center;"><?= "&#36;"; echo $ln->pago_real;?></th>
		<th colspan="2" style="text-align: center;"><a href="?pg=2e&cl=<?= $ln->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
		</tr>
		</tr>
		<?php
	}
	$sql="SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, 
	cuentas.interes, cuentas.cliente, cuentas.fecha_pago, cuentas.total, cuentas.monto_saldado, cuentas.estado 
	FROM clientes, cuentas 
	WHERE clientes.id = cuentas.cliente 
	AND DATE(fecha_pago) BETWEEN '".$desde."' AND '".$hasta."' 
	AND cuentas.monto_saldado > 0 
	AND (cuentas.estado = 1 OR cuentas.estado= 2) 
	$clcobrador
	ORDER BY fecha_pago ASC";
	$salda = $db->query($sql);
	$ctasNum = mysql_num_rows($salda);
	?>
	<caption>Cuentas Saldadas</caption>
	<thead>
	<tr>
	<th colspan="5" style="text-align:center"> CUENTAS SALDADAS <?= $ctasNum; ?> </th>
	</tr>
	<th colspan="5" style="text-align:center">&nbsp;</th>
	<tr>
	</thead>
	<?php
	while ($ln = $db->fetchNextObject($salda)){
		$totSaldado += $ln->monto_saldado;
		$saldaInteres = getInteresPago($ln->monto_saldado,$ln->interes);
		$saldaCapital = ($ln->monto_saldado - $saldaInteres);
		$Acum_Salda_Capital += $saldaCapital;
		$Acum_Salda_Interes += $saldaInteres;
		?>
		<tr>
		<th width="250px" style="text-align: center;"><?= getFecha($ln->fecha_pago);?></th>
		<th width="250px" style="text-align: center"><?= $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
		<th width="250px" style="text-align: center;"><?= "&#36;"; echo $saldaCapital;?></th>
		<th width="250px" style="text-align: center;"><?= "&#36;"; echo $saldaInteres;?></th>
		<th width="250px" style="text-align: center;"><?= "&#36;"; echo $ln->monto_saldado;?></th>
		</tr>
		<?php
	}
	?>    
	</tbody>
	<tfoot>
	<tr>
	<th colspan="2" style="text-align: left"> Totales </th>
	<th colspan="3" style="text-align: center"><?="&#36;"; echo $totGlobal;?></th>
	</tr>
	</tfoot>
	<table>
	<?php
}else{
	?>
	<p class="title">Reportes &raquo; Reporte por fechas</p>
	<form name="repoFechas" action="" method="post">
	<table>
	<caption>Seleccione el rango de Fechas</caption>
	<thead>
	<tr>
	<th colspan="4"></th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<th width="150">Desde Fecha:</th>
	<td><input type="text" name="desde" id="desde" size="10" value="<?= date('Y-m-d');?>" class="dpfecha" /></td>
	<th width="150">Hasta Fecha:</th>
	<td><input type="text" name="hasta" id="hasta" size="10" value="<?= date('Y-m-d');?>" class="dpfecha" /></td>
	</tr>
	<?php
	if($UserLevel==0){
		?>
		<tr>
		<th colspan="2">Seleccione un cobrador:</th>
		<td colspan="2">
		<select name="cobrador" id="cobrador">
		<option value="0">Todos</option>
		<?php
		$sql = "SELECT username FROM mymvcdb_users WHERE username!='jmarincastro'";
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
