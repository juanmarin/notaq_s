<?php
/*
*/
?>
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
	<p class="title">Reportes &raquo; Reporte diario</p>
	<form action="" method="post">
	<input type="hidden" name="desde" id="desde" size="10" value="<?= $desde;?>" />
	<input type="hidden" name="hasta" id="hasta" size="10" value="<?= $hasta;?>" />
	<input type="submit" name="imprimir" value="Imprimir" />
	</form>
	<table>
	<caption>Reporte del dia : <?php echo getFecha($desde); ?> </caption>
	<thead>
	<tr>
	<th>Fecha Pago</th>
	<th colspan="2">Nombre</th>
	<th colspan="2">Pago Total</th>
	<th colspan="2">Cobrador</th>
	<th colspan="2">Acciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$sql = "SELECT clientes.id AS clientes, clientes.nombre, clientes.apellidop, clientes.apellidom, 
	clientes.c_cobrador, pagos.fechaPago, SUM(pagos.pago_real) AS pago_real, pagos.interes, pagos.estado 
	FROM clientes, pagos 
	WHERE clientes.id = pagos.cliente 
	AND DATE(pagos.fechaPago) BETWEEN '".$desde."' AND '".$hasta."' 
	AND pagos.pago_real > 0 
	AND (pagos.estado = 1 OR pagos.estado = 3) 
	$clcobrador
	GROUP BY clientes.id";
	#echo $sql;
	$result = $db->query($sql);
	$totGlobal=0;
	$totCapCobrado=0;
	$totIntCobrado=0;
	while ($ln = $db->fetchNextObject($result)){
		$intPagado = getInteresPago($ln->pago_real, $ln->interes);
		$aboCapital = ($ln->pago_real - $intPagado);
		$totCapCobrado += $aboCapital;
		$totGlobal += $ln->pago_real;
		$totIntCobrado += $intPagado;
		?>
		<tr>
		<th width="250px" style="text-align: center;"><?= getFecha($ln->fechaPago);?></th>
		<th colspan="3" width="250px" style="text-align: center"><?= $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
		<th width="250px" style="text-align: center;"><?= "&#36;"; echo $ln->pago_real;?></th>
		<th width="250px" style="text-align: center;"><?php echo $ln->c_cobrador;?></th>
		<th colspan="150px" style="text-align: center;"><a href="?pg=2e&cl=<?= $ln->clientes;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
		</tr>
		</tbody>
		<?php
	}
	?>
	<tfoot>
	<tr>
	<th colspan="4" style="text-align: left"> Totales </th>

	<th colspan="3" style="text-align:center"><?= "&#36;"; echo $totGlobal;?></th>
	</tr>
	</tfoot>
	</table>
	<br /><br /><br /><br /><br />
	<table>
	<form action="" method="post">
	<input type="hidden" name="desde" id="desde" size="10" value="<?= $desde;?>" />
	<input type="hidden" name="hasta" id="hasta" size="10" value="<?= $hasta;?>" />
	<input type="submit" name="imprimirReporte" value="Imprimir Reporte" />
	</form>
	<?php
	$sql="SELECT clientes.id AS clientes, clientes.nombre, clientes.apellidop, clientes.apellidom, clientes.c_cobrador
	, pagos.cliente, pagos.fecha, pagos.estado, pagos.pago AS pago 
	, cuentas.monto_saldado, cuentas.interes
	FROM cuentas, pagos, clientes 
	WHERE cuentas.id=pagos.cuenta 
	AND clientes.id=pagos.cliente 
	AND pagos.estado = 0 
	AND DATE(pagos.fecha) BETWEEN '".$desde."' AND '".$hasta."'
	$clcobrador
	";
	$salda = $db->query($sql);
	$ctasNum = mysql_num_rows($salda);
	?>
	<caption>CLIENTES QUE FALTARON <?= $ctasNum; ?></caption>
	<thead>
	<tr>
	<th>Fecha Pago</th>
	<th colspan="2">Nombre</th>
	<th colspan="2">Monto Abono</th>
	<th colspan="2">Cobrador</th>
	<th colspan="2">Acciones</th>
	</tr>
	</thead>
	<?php
	$Acum_Salda_Capital=0;
	$Acum_Salda_Interes=0;
	$totSaldado=0;
	while ($ln = $db->fetchNextObject($salda)){
		$totSaldado += $ln->monto_saldado;
		$saldaInteres = getInteresPago($ln->monto_saldado,$ln->interes);
		$saldaCapital = ($ln->monto_saldado - $saldaInteres);
		$Acum_Salda_Capital += $saldaCapital;
		$Acum_Salda_Interes += $saldaInteres;
		?>
		<tbody>     
		<tr>
		<th width="250px" style="text-align: center;"><?= getFecha($ln->fecha);?></th>
		<th colspan="3" width="250px" style="text-align: center"><?= $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
		<th width="250px" style="text-align: center;"><?= "&#36;"; echo moneda($ln->pago);?></th>
		<th width="250px" style="text-align: center;"><?php echo $ln->c_cobrador;?></th>
		<th colspan="150px" style="text-align: center;"><a href="?pg=2e&cl=<?= $ln->clientes;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
		</tr>
		<?php
	}
	?>    
	</tbody>
	<tfoot>
	<tr>
	<th colspan="7">&nbsp;</th>
	</tr>
	</tfoot>
	<table>
	<?php
}else{
	?>
	<p class="title">Reportes &raquo; Reporte diario</p>
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
<br /><br /><br />
<?php
## FUNCIONES ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
function getNpago($cuenta, $pago){
	$sql = "SELECT id FROM pagos WHERE cuenta = ".$cuenta;
	$res = mysql_query($sql);
	$cnt = 0;
	while($r = mysql_fetch_array($res) ) {
		$cnt++;
		if($r["id"] == $pago){
			$np = $cnt;
		}
	}
	return $np;
}
if(isset($_POST["imprimir"])){
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	## IMPRESION ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	#[[ OBTENIENDO DATOS DE PAGINACION ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	$sql="SELECT nombre
	FROM pagos, cuentas, clientes
	WHERE pagos.cuenta=cuentas.id AND pagos.cliente=clientes.id 
	AND DATE(pagos.fechaPago) BETWEEN '".$desde."' AND '".$hasta."' 
	AND pagos.pago_real > 0 
	AND (pagos.estado = 1 OR pagos.estado = 3) 
	GROUP BY pagos.cliente";
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
	for($i=1; $i<=$paginas; $i++){
		#[[ OBTENIENDO DATOS DE RELLENO ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
		$sql="SELECT nombre, apellidom, apellidop, npagos, pagos.pago_real, pagos.interes, cuentas.id as cid, pagos.id as pid,
		dias_pago, tipo_pago
		FROM pagos, cuentas, clientes
		WHERE pagos.cuenta=cuentas.id AND pagos.cliente=clientes.id 
		AND DATE(pagos.fechaPago) BETWEEN '".$desde."' AND '".$hasta."'
		AND pagos.pago_real > 0 
		AND (pagos.estado = 1 OR pagos.estado = 3) 
		GROUP BY pagos.cliente 
		LIMIT ".$regs.", 7";
		#echo $sql . '<br />';
		$fecha = date("Y-m-d");
		include 'include/php/imprimeReporteDiario.php';
		$regs += 7;
		sleep(1);
	}
}
if(isset($_POST["imprimirReporte"]))
{
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	#[[  ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	#[[ OBTENIENDO DATOS DE PAGINACION ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	$sql="SELECT clientes.id
	FROM clientes, pagos, cuentas
	WHERE cuentas.id=pagos.cuenta AND clientes.id=pagos.cliente 
	AND pagos.estado = 0 AND DATE(pagos.fecha) BETWEEN '".$desde."' AND '".$hasta."'";
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
		$sql="SELECT pagos.fecha, nombre, apellidop, apellidom, pagos.pago, cuentas.id as cid, pagos.id as pid, 
		npagos, dias_pago, tipo_pago
		FROM clientes, pagos, cuentas 
		WHERE cuentas.id=pagos.cuenta AND clientes.id=pagos.cliente 
		AND pagos.estado = 0 
		AND DATE(pagos.fecha) BETWEEN '".$desde."' AND '".$hasta."' 
		LIMIT ".$regs.", 7";
		$fecha = date("Y-m-d");
		include 'include/php/imprimeReporteDiarioFaltan.php';
		$regs += 7;
		sleep(1);
	}	
}
?>
