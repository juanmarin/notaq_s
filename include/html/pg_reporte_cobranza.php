<?php
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);

if(isset($_POST['enviar'])){ 
	$desde = $_POST['desde'];
	$hasta = $_POST['hasta']; 
	?>
	<p class="title">Reportes &raquo; Cobranza</p>
	<table>
	<caption>Reporte del dia : <?php echo getFecha($desde); ?> al <?php echo getFecha($hasta); ?></caption>
	<thead>
		<tr>
			<th colspan="1">Fecha Pago</th>
			<th>Nombre</th>
			<th>DIRECCION</th>
			<th>PAGO</th>
			<th>ACCIONES</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, clientes.direccion, 
		clientes.colonia, clientes.telefono, clientes.celular, pagos.id as idp, pagos.cuenta, pagos.fecha, pagos.pago, 
		pagos.estado 
		FROM clientes, pagos 
		WHERE clientes.id = pagos.cliente AND pagos.fecha BETWEEN '".$desde."' AND '".$hasta."' 
		AND pagos.estado = 0 ORDER BY fecha ASC";
		$result = $db->query($sql);
		$_SESSION["QUERY"] = $sql;
		while ($ln = $db->fetchNextObject($result)){
			?>
			<tr>
				<th width="250px" style="text-align: center;"><? echo getFecha($ln->fecha);?></th>
				<th width="250px" style="text-align: center"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
				<th width="250px" style="text-align: center;"><?echo $ln->direccion." ".$ln->colonia." ".$ln->telefono;?></th>
				<th width="250px" style="text-align: center;"><?echo "&#36;"; echo moneda($ln->pago);?></th>
				<th colspan="1" style="text-align: center;"><a href="?pg=2e&cl=<?echo $ln->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
			</tr>
			<?php
		}
		$db->close();
		?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="5">
				<form method="post" action="">
					<!--<input type="hidden" name="consulta" value="<?echo $sql;?>" />-->
					<input type="hidden" name="del" value="<?echo $desde;?>" />
					<input type="hidden" name="al" value="<?echo $hasta;?>" />
					<input type="submit" name="imprimir" value="Imprimir" />
				</form>
			</th>
		</tr>
	</tfoot>
	<table>
	<?php
}elseif(isset($_POST["imprimir"])) {
	
	# INICIANDO VARIABLES ********************************************************************************++
	$desde = $_POST["del"];
	$hasta = $_POST["al"];

	#[[ OBTENIENDO DATOS DE PAGINACION ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
	$sql = "SELECT clientes.id 
		FROM clientes, pagos 
		WHERE clientes.id = pagos.cliente AND pagos.fecha BETWEEN '".$desde."' AND '".$hasta."' 
		AND pagos.estado = 0 ORDER BY fecha ASC";
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
	# FUNCIONES ******************************************************************************************++
	function getNumPago($cuenta, $pago){
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
	function getCSaldo($cuenta){
		$sql = "SELECT total FROM cuentas WHERE id = ".$cuenta;
		$res = mysql_query($sql);
		$r = mysql_fetch_array($res);
		return $r["total"];
	}
	for ($i=1; $i<=$paginas; $i++){
		#[[ OBTENIENDO DATOS DE RELLENO ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
		$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, clientes.direccion, 
		clientes.colonia, clientes.telefono, clientes.celular, pagos.id as idp, pagos.cuenta, pagos.fecha, pagos.pago, 
		pagos.estado, dias_pago, tipo_pago, npagos 
		FROM clientes, pagos, cuentas
		WHERE clientes.id = pagos.cliente AND cuentas.id=pagos.cuenta AND pagos.fecha BETWEEN '".$desde."' AND '".$hasta."' 
		AND pagos.estado = 0 ORDER BY fecha ASC LIMIT ".$regs.", 7";
		include 'include/php/imprimeReporteCobranza.php';
		$regs += 7;
		sleep(1);
	}

	#-REGRESAR AL REPORTE
	echo '<meta http-equiv="refresh" content="0;url=?pg=3c">';
}else {
	?>
	<p class="title">Reporte &raquo; Cobranza</p>
	<table>
	<caption></caption>
	<thead>
	<tr>
	<th colspan="2">Lista de cobranza por fechas</th>
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
