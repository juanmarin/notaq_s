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
	<p class="title">Reportes &raquo; inicio</p>
<form action = "" method="post">
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
<br />
<table>
<caption>Pagos recibidos del dia : <?php echo getFecha($desde); ?> al <?php echo getFecha($hasta); ?> </caption>
<thead>
	<tr>
      	<th>Fecha Pago</th>
      	<th>F. Operacion</th>
		<th colspan="2">Nombre</th>
		<th colspan="2">Cobrador</th>
		<th colspan="2">Pago Total</th>
	</tr>
</thead>
<tbody>
<?php
        require_once("include/php/sys_db.class.php");
        require_once("include/conf/Config_con.php");
        $db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
        $result = $db->query("SELECT clientes.id AS clientes, clientes.nombre, clientes.apellidop, 
        clientes.apellidom, clientes.c_cobrador AS cobrador,
        pagos.id AS pago, pagos.fecha, pagos.fechaPago, SUM(pagos.pago_real) AS pago_real, pagos.interes, pagos.estado 
        FROM clientes, pagos 
        WHERE clientes.id = pagos.cliente 
        			AND DATE(pagos.fechaPago) BETWEEN '".$desde."' 
        			AND '".$hasta."' AND pagos.pago_real > 0 
        			AND (pagos.estado = 1 OR pagos.estado = 3) 
        			$clcobrador
        			GROUP BY pagos.id");
        while ($ln = $db->fetchNextObject($result,$salda)){
            $intPagado = getInteresPago($ln->pago_real, $ln->interes);
            $aboCapital = ($ln->pago_real - $intPagado);
            $totCapCobrado += $aboCapital;
            $totGlobal += $ln->pago_real;
            $totIntCobrado += $intPagado;
?>
		<tr>
			<th width="180px" style="text-align: center;"><?php echo getFecha($ln->fecha);?></th>
			<th width="180px" style="text-align: center;"><?php echo getFecha($ln->fechaPago);?></th>
			<th colspan="3" width="250px" style="text-align: center"><?php echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
			<th width="180px" style="text-align: center;"><?php echo $ln->cobrador;?></th>
			<th width="180px" style="text-align: center;"><?php echo "&#36;"; echo $ln->pago_real;?></th>
			<!--
			<th style="text-align:center"><input type="checkbox" name="ids[]" value="<?php echo $ln->pago; ?>" /></th>
			<th style="text-align: center;"><a href="?pg=2e&cl=<?php echo $ln->clientes;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
			-->
		</tr>
	</tbody>
	<?php
	}
	?>
	<tfoot>
	<tr>
		<th colspan="4" style="text-align: left"> Totales </th>
		
		<th colspan="3" style="text-align:center"><?php echo "&#36;"; echo $totSaldado + $totGlobal;?></th>
	</tr>
	</tfoot>
	</table>
	<input type="submit" name="elimina_pago" id="elimina_pago" value="Eliminar &raquo;" />
	</form>
	<br /><br /><br /><br /><br />
	<table>
	<form action="" method="post">
		<input type="hidden" name="desde" id="desde" size="10" value="<?php echo $desde;?>" />
		<input type="hidden" name="hasta" id="hasta" size="10" value="<?php echo $hasta;?>" />
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

	<thead>

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
    <?php
        }
    ?>    
</tbody>

<table>
<?php
    }else{
?>
<p class="title">Control &raquo; Pagos</p>
<table>
	<!--
<caption></caption>

<thead>
	
	<tr>
		<th colspan="2">Control de Pagos</th>
	</tr>

</thead>

<tbody>
</tbody>
-->
<tfoot>
	<tr>
		<th colspan="3"></th>
	</tr>
</tfoot>
</table>
<br />
<form action = "" method="post">
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

