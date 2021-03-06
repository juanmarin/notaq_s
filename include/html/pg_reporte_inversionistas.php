<?php
/*
*/
?>
<?php
    if(isset($_POST['enviar'])){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
?>
	<p class="title">Reporte &raquo; Inversionistas</p>
	<form action="" method="post">
		<input type="hidden" name="desde" id="desde" size="10" value="<?echo $desde;?>" />
		<input type="hidden" name="hasta" id="hasta" size="10" value="<?echo $hasta;?>" />
	</form>
<table>
<caption>Reporte del dia : <?php echo getFecha($desde); ?>  al <?php echo getFecha($hasta); ?> </caption>
<thead>
	<tr>
    	<th colspan="2">Fecha Pago</th>
		<th colspan="2">Monto Prestado</th>
		<th colspan="2">Nombre</th>
		<th colspan="2">Modo de Pago</th>
		<th colspan="2">Pago Total</th>
		<th colspan="2">Pago Inv.</th>
		<th colspan="2">Acciones</th>
	</tr>
</thead>
<tbody>
<?php
        require_once("include/php/sys_db.class.php");
        require_once("include/conf/Config_con.php");
        $db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
        $result = $db->query("SELECT COUNT(clientes.id) AS veces, clientes.id AS cliente, clientes.nombre, clientes.apellidop, clientes.apellidom, 
        pagos.fechaPago, cuentas.cliente, cuentas.cantidad AS monto, cuentas.tipo_pago AS tipo_pago, SUM(pagos.pago_real) AS pago_real, pagos.interes, pagos.estado 
        FROM clientes, pagos, cuentas
        WHERE clientes.id = pagos.cliente 
        			AND pagos.cliente = cuentas.cliente
        			AND cuentas.id = pagos.cuenta
        			AND DATE(pagos.fechaPago) BETWEEN '".$desde."' 
        			AND '".$hasta."' AND pagos.pago_real > 0 
        			AND (pagos.estado = 1 OR pagos.estado = 3) GROUP BY clientes.id");
        while ($ln = $db->fetchNextObject($result,$salda)){
            $intPagado = getInteresPago($ln->pago_real, $ln->interes);
            $aboCapital = ($ln->pago_real - $intPagado);
            $totCapCobrado += $aboCapital;
            $totGlobal += $ln->pago_real;
            $totIntCobrado += $intPagado;
            
            if($ln->tipo_pago ==1) {
										$pago_inver = (($ln->monto) * (1.5)/100)*($ln->veces);            	
            		}elseif($ln->tipo_pago ==2) {
            				$pago_inver= (($ln->monto) *(3)/100)*($ln->veces);
										}elseif($ln->tipo_pago ==3) {
            				$pago_inver = (($ln->monto) *(3)/100)*($ln->veces);}
            		else $pago_inver = (($ln->monto) *(6)/100)*($ln->veces);{
				}
            	$pagotot_inver += $pago_inver;
?>
		<tr>
			<th colspan="2"style="text-align: center;"><? echo getFecha($ln->fechaPago);?></th>
			<th colspan="2" style="text-align: center;"><? echo $ln->monto;?></th>
			<th colspan="3" width="250px" style="text-align: center"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
			<th colspan="1" style="text-align: center;"><? echo get_tipo_pago($ln->tipo_pago);?></th>
			<th colspan="2" style="text-align: center;"> <? echo $ln->pago_real;?> (<? echo $ln->veces;?>)</th>

			<th colspan="2" style="text-align: center;"> <? echo number_format($pago_inver,2);?></th>
			
			<th colspan="2" style="text-align: center;"><a href="?pg=2e&cl=<?echo $ln->cliente;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
		</tr>
	</tbody>
	<?php
	}
	?>
	<tfoot>
	<tr>
		<th colspan="6" style="text-align: left"> Intereses para Inversionistas </th>
		
		<th colspan="6" style="text-align:center"><?echo "$"; echo $pagotot_inver;?></th>
	</tr>
	</tfoot>			
	</table>
	<br /><br /><br /><br /><br />
	<table>
	<form action="" method="post">
		<input type="hidden" name="desde" id="desde" size="10" value="<?echo $desde;?>" />
		<input type="hidden" name="hasta" id="hasta" size="10" value="<?echo $hasta;?>" />
	</form>
	<?php	
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql="SELECT clientes.id AS clientes, clientes.nombre, clientes.apellidop, clientes.apellidom, pagos.cliente, pagos.fecha, 
		pagos.estado, pagos.pago AS pago 
		FROM cuentas, pagos, clientes 
		WHERE cuentas.id=pagos.cuenta AND clientes.id=pagos.cliente 
		AND pagos.estado = 0 AND DATE(pagos.fecha) BETWEEN '".$desde."' AND '".$hasta."'
		";
	$salda = $db->query($sql);
	$ctasNum = mysql_num_rows($salda);
	?>
	
    <?php
    while ($ln = $db->fetchNextObject($salda)){
		$totSaldado += $ln->monto_saldado;
		$saldaInteres = getInteresPago($ln->monto_saldado,$ln->interes);
		$saldaCapital = ($ln->monto_saldado - $saldaInteres);
		$Acum_Salda_Capital += $saldaCapital;
		$Acum_Salda_Interes += $saldaInteres;
    ?>
    <?php
        }
    ?>    
<?php
    }else{
?>
<p class="title">Reporte &raquo; Diario</p>
<table>
<caption></caption>
<thead>
	<tr>
		<th colspan="2">Reporte Diario</th>
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
function get_tipo_pago($tipo_pago)
	{
		switch($tipo_pago)
		{
			case 1:	echo "Semanal";	break;
			case 2:	echo "Catorcenal";	break;
			case 3:	echo "Quincenal";	break;
			case 4:	echo "Mensual";	break;
			default:	echo "No definido";	break;
		}
	}

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
	if(($num % 7) > 0){
		$paginas+=1;
	}
	$regs = 0;
	$chkpt = 0;
	$alto = 100;
	$li = $alto;
	
	for ($i=1; $i<=$paginas; $i++){
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
