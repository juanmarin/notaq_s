<?php
    if(isset($_POST['enviar'])){ 
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
?>
    <p class="title">Reportes &raquo; inicio</p>
<table>
<caption>Reporte del dia : <?php echo getFecha($desde); ?> al <?php echo getFecha($hasta); ?></caption>
<thead>
	<tr>
        <th>Fecha Pago</th>
		<th>Nombre</th>
		<th>Pago Total</th>
		<th colspan="2">Acciones</th>
	</tr>
</thead>
<tbody>
<?php
        require_once("include/php/sys_db.class.php");
        require_once("include/conf/Config_con.php");
        $db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
        $result = $db->query("SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, pagos.fechaPago, pagos.pago_real, pagos.interes, pagos.estado FROM clientes, pagos WHERE clientes.id = pagos.cliente AND DATE(pagos.fechaPago) BETWEEN '".$desde."' AND '".$hasta."' AND pagos.pago_real > 0 AND (pagos.estado = 1 OR pagos.estado = 3) ORDER BY fechaPago ASC");
        while ($ln = $db->fetchNextObject($result,$salda)){
            $intPagado = getInteresPago($ln->pago_real, $ln->interes);
            $aboCapital = ($ln->pago_real - $intPagado);
            $totCapCobrado += $aboCapital;
            $totGlobal += $ln->pago_real;
            $totIntCobrado += $intPagado;
?>
		<tr>
            <th width="350px" style="text-align: center;"><? echo getFecha($ln->fechaPago);?></th>
			<th width="350px" style="text-align: center"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
			<th width="350px" style="text-align: center;"><?echo "&#36;"; echo $ln->pago_real;?></th>
			<th colspan="2" style="text-align: center;"><a href="?pg=2e&cl=<?echo $ln->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
		</tr>
		</tr>
	<?php
	}
	$db->close(); 
    #$regs =  mysql_num_rows($result);
    $db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
    $salda = $db->query("SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, cuentas.interes, cuentas.cliente, cuentas.fecha_pago, cuentas.total, cuentas.monto_saldado, cuentas.estado FROM clientes, cuentas WHERE clientes.id = cuentas.cliente AND DATE(fecha_pago) BETWEEN '".$desde."' AND '".$hasta."' AND cuentas.monto_saldado > 0 AND (cuentas.estado = 1 OR cuentas.estado= 2) ORDER BY fecha_pago ASC");
    $ctasNum = mysql_num_rows($salda);
    ?>
        <caption>Cuentas Saldadas</caption>
        <thead>
        <tr>
            <th colspan="5" style="text-align:center"> CUENTAS SALDADAS <? echo $ctasNum; ?> </th>
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
            <th width="250px" style="text-align: center;"><? echo getFecha($ln->fecha_pago);?></th>
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
        <th colspan="3" style="text-align: center"><?echo "&#36;"; echo $totSaldado + $totGlobal;?></th>
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
