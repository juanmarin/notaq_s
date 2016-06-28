<p class="title">Reportes &raquo; Historial crediticio &raquo; Historial cliente</p>
<table>
<caption>HISTORIAL DE CREDITO</caption>
<thead>
	<tr>
      <th></th>
	</tr>
</thead>
<tbody>
	<?php
### CLIENTE -----------------------------------------------------------------------------------------------------------
	$cliente = $_GET["cl"];
### CONEXION CON BASE DE DATOS ----------------------------------------------------------------------------------------
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
### GET NOTAS ---------------------------------------------------------------------------------------------------------
	function getNotas($cliente){
		$sql = "SELECT * FROM notas WHERE cliente = ".$cliente;
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		if($num > 0){
			return '<a href="include/html/box_notas.php?height=400&width=300&cl='.$cliente.'" class="thickbox boton sombra">'.$num.' notas</a>';
		}else {
			return 'No hay notas';
		}
	}
	function getNPagos($cuenta){
		$sql = "SELECT * FROM pagos WHERE cuenta = ".$cuenta;
		$res = mysql_query($sql);
		return mysql_num_rows($res);
	}
	function getRecargos($cuenta){
		$sql = "SELECT * FROM recargos WHERE cuenta = ".$cuenta;
		$res = mysql_query($sql);
		return mysql_num_rows($res);
	}
### INFORMACION DEL CLIENTE -------------------------------------------------------------------------------------------
	$sql = "SELECT * FROM clientes WHERE id=".$cliente;
	$result = $db->query($sql);
	while ($ln = $db->fetchNextObject($result)){
		echo '<tr>';
		echo '	<td colspan="5">CLIENTE: '.$ln->nombre.' '.$ln->apellidop.' '.$ln->apellidom.'</td>';
		echo '	<td colspan="1"><a href="?pg=2e&cl='.$cliente.'" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>';
		echo '</tr>';
		echo '<tr>';
		echo '	<td colspan="6">AVAL: '.$ln->R1nombre.' '.$ln->R1apellidop.' '.$ln->R1apellidom.'</td>';
		echo '</tr>';
	}
### ENCABEZADO 
	echo '<tr>';
	echo '	<th>FECHA</th>';
	echo '	<th>MONTO</th>';
	echo '	<th>MONTO SALDADO</th>';
	echo '	<th>PAGOS</th>';
	echo '	<th>RECARGOS</th>';
	echo '	<th>NOTAS</th>';
	echo '</tr>';
### INFORMACION DE LA CUENTA ------------------------------------------------------------------------------------------
	$sql = "SELECT * FROM cuentas WHERE estado > 0 AND cliente=".$cliente;
	$result = $db->query($sql);
	$regs =  mysql_num_rows($result);
	while ($ln = $db->fetchNextObject($result)){
		$monto = $ln->cantidad * ( ( ( $ln->interes * $ln->tiempo ) / 100 ) + 1 );
		echo '<tr>';
		echo '	<td>'.$ln->fecha.'</td>';
		echo '	<td>$ '.moneda($monto, 0).'</td>';
		echo '	<td>$ ';
			if($ln->monto_saldado == 0){
				moneda($monto);
			}else{
				moneda($ln->monto_saldado);
			}
		echo '	</td>';
		echo '	<td>'.getNPagos($ln->id).'</td>';
		echo '	<td>'.getRecargos($ln->id).'</td>';
		echo '	<td>'.getNotas($ln->id).'</td>';
		echo '</tr>';
	}
	$db->close(); 
	?>
   </tbody>

<tfoot>
	<tr>
		<th colspan="5"></th>
	</tr>
</tfoot>
<table>