<?php
    if(isset($_POST['enviar'])){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
?>
	<p class="title">Reportes &raquo; inicio</p>
	<form action="" method="post">
		<input type="hidden" name="desde" id="desde" size="10" value="<?echo $desde;?>" />
		<input type="hidden" name="hasta" id="hasta" size="10" value="<?echo $hasta;?>" />
		<input type="submit" name="imprimir" value="Imprimir" />
	</form>
<table>
<caption>Reporte del dia : <?php echo getFecha($desde); ?> </caption>
<thead>
	<tr>
      	<th>Num. Cte.</th>
		<th>Fecha Pago</th>
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
        $result = $db->query("SELECT clientes.id AS clientes, clientes.nombre, clientes.apellidop, clientes.apellidom, 
        pagos.fechaPago, SUM(pagos.pago_real) AS pago_real, pagos.interes, pagos.estado 
        FROM clientes, pagos 
        WHERE clientes.id = pagos.cliente 
        			AND DATE(pagos.fechaPago) BETWEEN '".$desde."' 
        			AND '".$hasta."' AND pagos.pago_real > 0 
        			AND (pagos.estado = 1 OR pagos.estado = 3) GROUP BY clientes.id");
        while ($ln = $db->fetchNextObject($result,$salda)){
            $intPagado = getInteresPago($ln->pago_real, $ln->interes);
            $aboCapital = ($ln->pago_real - $intPagado);
            $totCapCobrado += $aboCapital;
            $totGlobal += $ln->pago_real;
            $totIntCobrado += $intPagado;
?>
		<tr>
			<th width="250px" style="text-align: center;"><? echo $ln->clientes;?></th>
			<th width="250px" style="text-align: center;"><? echo getFecha($ln->fechaPago);?></th>
			<th width="250px" style="text-align: center"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
			<th width="250px" style="text-align: center;"><?echo "&#36;"; echo $aboCapital;?></th>
			<th width="250px" style="text-align: center;"><?echo "&#36;"; echo $intPagado;?></th>
			<th width="250px" style="text-align: center;"><?echo "&#36;"; echo $ln->pago_real;?></th>
		</tr>
	</tbody>
	<?php
	}
	?>
	<tfoot>
	<tr>
		<th colspan="3" style="text-align: left"> Totales </th>
		<th colspan="1" style="text-align: center"><?echo "&#36;"; echo $totCapCobrado + $Acum_Salda_Capital ;?></th>
		<th colspan="1" style="text-align: center"><?echo "&#36;"; echo $totIntCobrado + $Acum_Salda_Interes;?></th>
		<th colspan="1" style="text-align: center"><?echo "&#36;"; echo $totSaldado + $totGlobal;?></th>
	</tr>
	</tfoot>
	</table>
	<br /><br /><br /><br /><br />
	<table>
	<form action="" method="post">
		<input type="hidden" name="desde" id="desde" size="10" value="<?echo $desde;?>" />
		<input type="hidden" name="hasta" id="hasta" size="10" value="<?echo $hasta;?>" />
		<input type="submit" name="imprimirReporte" value="Imprimir Reporte" />
	</form>
	<?php	
    #$regs =  mysql_num_rows($result);
    $db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
    $salda = $db->query("SELECT clientes.id AS cte_Id, clientes.nombre, clientes.apellidop, clientes.apellidom, pagos.cliente, pagos.fecha, pagos.estado, pagos.pago AS pago FROM clientes, pagos WHERE clientes.id = pagos.cliente AND DATE(fecha) BETWEEN '".$desde."' AND '".$hasta."' AND (pagos.estado = 0) GROUP BY clientes.id");
    $ctasNum = mysql_num_rows($salda);
    ?>
        <caption>CLIENTES QUE FALTARON <? echo $ctasNum; ?></caption>
        <thead>
	<tr>
      	<th>Num. Cte.</th>
		<th>Fecha Pago</th>
		<th>Nombre</th>
		<th>Abono Capital</th>
		<th>Pago Inter&eacute;s</th>
		<th>Monto Abono</th>
	</tr>
</thead>
    <?php
    while ($ln = $db->fetchNextObject($salda)){
		$totSaldado += $ln->monto_saldado;
		$saldaInteres = getInteresPago($ln->monto_saldado,$ln->interes);
		$saldaCapital = ($ln->monto_saldado - $saldaInteres);
		$Acum_Salda_Capital += $saldaCapital;
		$Acum_Salda_Interes += $saldaInteres;
    ?>
	<tbody>     
		<tr>
			<th width="250px" style="text-align: center;"><? echo $ln->cte_Id;?></th>
			<th width="250px" style="text-align: center;"><? echo getFecha($ln->fecha);?></th>
			<th width="250px" style="text-align: center"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
			<th width="250px" style="text-align: center;"><?echo "&#36;"; echo $saldaInteres;?></th>
			<th width="250px" style="text-align: center;"><?echo "&#36;"; echo $saldaInteres;?></th>
			<th width="250px" style="text-align: center;"><?echo "&#36;"; echo getPagoRedondo($ln->pago);?></th>
		</tr>
    <?php
        }
    ?>    
</tbody>
<tfoot>
	<tr>
		<th colspan="6">&nbsp;</th>
	</tr>
</tfoot>
<table>
<?php
    }else{
?>
<p class="title">Reporte &raquo; Fechas</p>
<table>
<caption></caption>
<thead>
	<tr>
		<th colspan="2">Reporte por fechas</th>
	</tr>
</thead>
<tbody>
</tbody>
<tfoot>
	<tr>
		<th colspan="3"></th>
	</tr>
</tfoot>
</table>
<br />
<form name="repoFechas" action="<? $PHP_SELF=urlencode($PHP_SELF); ?>" method="post">
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
  	        	<td><input type="text" name="desde" id="desde" size="10" value="<?echo date('Y-m-d');?>" class="dpfecha" /></td>
			<th width="150">Hasta Fecha:</th>
			<td><input type="text" name="hasta" id="hasta" size="10" value="<?echo date('Y-m-d');?>" class="dpfecha" /></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4"><input type="submit" value="Mostrar Reporte" name="enviar" /></th>
		</tr>
	</tfoot>
	</table>
	</form>
<?
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
if($_POST["imprimir"]){
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
    	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	## IMPRESION ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	#[[ OBTENIENDO DATOS DE PAGINACION ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	$sql="SELECT nombre
		FROM clientes, pagos 
		WHERE clientes.id = pagos.cliente 
			AND DATE(pagos.fechaPago) BETWEEN '".$desde."' 
			AND '".$hasta."' AND pagos.pago_real > 0 
			AND (pagos.estado = 1 OR pagos.estado = 3) GROUP BY clientes.id";

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
			FROM clientes, pagos, cuentas 
		WHERE clientes.id = pagos.cliente AND clientes.id=cuentas.cliente
			AND DATE(pagos.fechaPago) BETWEEN '".$desde."' 
			AND '".$hasta."' AND pagos.pago_real > 0 
			AND (pagos.estado = 1 OR pagos.estado = 3) GROUP BY clientes.id LIMIT ".$regs.", 7";

		include 'include/php/imprimeReporteDiario.php';
		$regs += 7;
		sleep(1);
	}
}
if($_POST["imprimirReporte"])
{	
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
    	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta'];
	#[[  ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	#[[ OBTENIENDO DATOS DE PAGINACION ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	$sql="SELECT clientes.id
		FROM clientes, pagos
		WHERE clientes.id = pagos.cliente 
			AND DATE(fecha) BETWEEN '".$desde."' AND '".$hasta."' 
			AND (pagos.estado = 0) 
		GROUP BY clientes.id";

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
			WHERE clientes.id = pagos.cliente AND clientes.id=cuentas.cliente 
				AND DATE(pagos.fecha) BETWEEN '".$desde."' AND '".$hasta."' 
				AND (pagos.estado = 0) 
			GROUP BY clientes.id LIMIT ".$regs.", 7";
		include 'include/php/imprimeReporteDiarioFaltan.php';
		$regs += 7;
		sleep(1);
	}
}
?>
