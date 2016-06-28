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
			$clcobrador="AND recargos.aplicado_x = '".$_POST["cobrador"]."'";
		}
	}else{
		$clcobrador="";
	}
}else{
	$clcobrador="AND recargos.aplicado_x = '$UserName'";
}
if(isset($_POST['enviar']))
{
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	?>
	<p class="title">Reportes &raquo; Recargos</p>
	<form action="" method="post">
	<input type="hidden" name="desde" id="desde" size="10" value="<?= $desde;?>" />
	<input type="hidden" name="hasta" id="hasta" size="10" value="<?= $hasta;?>" />
	<input type="submit" name="imprimir" value="Imprimir" />
	</form>
	<table>
	<caption>Reporte recargos del dia : <?php echo getFecha($desde); ?> al <?php echo getFecha($hasta); ?></caption>
	<thead>
	<tr>
	<th>Fecha Pago</th>
	<th colspan="2">Nombre</th>
	<th colspan="2">Cobrador</th>
	<th colspan="2">Pago Total</th>
	<th colspan="2">Acciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$result = $db->query("SELECT clientes.id AS cliente, clientes.nombre, clientes.apellidop, clientes.apellidom, recargos.cliente, recargos.cuenta, recargos.fecha, SUM(recargos.monto_saldado)AS sumatoria, recargos.estado, recargos.aplicado_x FROM clientes, recargos WHERE clientes.id = recargos.cliente AND 
	DATE(fecha) BETWEEN '".$desde."' AND '".$hasta."' AND estado > 0 AND recargos.aplicado_x != '' $clcobrador GROUP BY recargos.cliente ORDER BY fecha DESC");
	$totRecCobrado=0;
	while ($ln = $db->fetchNextObject($result))
	{
		$totRecCobrado += $ln->sumatoria;
		?>
		<tr>
		<th width="250px" style="text-align: center;"><?= getFecha($ln->fecha);?></th>
		<th colspan="3" width="250px" style="text-align: center"><?= $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
		<th width="250px" style="text-align: center;"><?php echo $ln->aplicado_x;?></th>
		<th width="250px" style="text-align: center;"><?= "&#36;"; echo $ln->sumatoria;?></th>
		<th colspan="2" style="text-align: center;"><a href="?pg=2e&cl=<?= $ln->cliente;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
		</tr>
		</tbody>
		<?php
	}
	?>
	<tfoot>
	<tr>
	<th colspan="4" style="text-align: left"> Totales </th>
	<th colspan="4" style="text-align:center"><?="&nbsp; &nbsp; &nbsp;&nbsp; &#36;"; echo moneda($totRecCobrado);?></th>
	</tr>
	</tfoot>
	</table>
	<br /><br /><br /><br /><br />
	<?php
}else{
	?>
	<p class="title">Reporte &raquo; Recargos</p>
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
function getNpago($cuenta, $pago)
{
	$sql = "SELECT id FROM pagos WHERE cuenta = ".$cuenta;
	$res = mysql_query($sql);
	$cnt = 0;
	while($r = mysql_fetch_array($res) )
	{
		$cnt++;
		if($r["id"] == $pago)
		{
			$np = $cnt;
		}
	}
	return $np;
}
if(isset($_POST["imprimir"]))
{
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	## IMPRESION ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	#[[ OBTENIENDO DATOS DE PAGINACION ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	$sql="SELECT nombre
	FROM pagos, cuentas, clientes
	WHERE	pagos.cuenta=cuentas.id AND pagos.cliente=clientes.id 
	AND DATE(pagos.fechaPago) BETWEEN '".$desde."' AND '".$hasta."' 
	AND pagos.pago_real > 0 
	AND (pagos.estado = 1 OR pagos.estado = 3) 
	GROUP BY pagos.cliente";
	$res = $db->query($sql);
	$num = $db->numRows();
	$paginas = $num / 7;
	$paginas = (int)$paginas;
	if(($num % 7) > 0)
	{
		$paginas+=1;
	}
	$regs = 0;
	$chkpt = 0;
	$alto = 100;
	$li = $alto;
	for ($i=1; $i<=$paginas; $i++)
	{
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
		AND pagos.estado = 0 AND DATE(pagos.fecha) BETWEEN '".$desde."' AND '".$hasta."' LIMIT ".$regs.", 7";
		$fecha = date("Y-m-d");
		include 'include/php/imprimeReporteDiarioFaltan.php';
		$regs += 7;
		sleep(1);
	}
}
?>
