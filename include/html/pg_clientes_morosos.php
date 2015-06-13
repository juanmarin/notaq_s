<?php
@session_start();
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
    $fecha = date("Y-m-d");
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
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
}if (isset($_POST["enviar"])){
	?>
	<p class="title">Portada &raquo; Listado de Clientes Morosos</p>
<table>
<caption>CUENTAS CON RECARGOS</caption>
<thead>
	<tr>
		<th>ID</th>
		<th>CLIENTE</th>
		<th>COBRADOR</th>
		<th>CARGOS</th>
		<th>DEMANDAR</th>
		<th colspan="2">ACCIONES</th>
	</tr>
</thead>
<tbody>
	<?php
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidop, clientes.demanda, cuentas.cliente, clientes.c_cobrador, 
	cuentas.cobrador, cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha, 
	SUM(pagos.pago) AS pago, pagos.estado
	FROM clientes, cuentas, pagos 
	WHERE
		clientes.id = cuentas.cliente 
		AND clientes.demanda != 1 
		AND cuentas.id = pagos.cuenta 
		AND cuentas.estado = 0 
		AND pagos.estado = 0 
		AND pagos.fecha < '".$fecha."'
		$clcobrador
	GROUP BY pagos.cliente 
	ORDER BY clientes.nombre ASC";
	$res = $db->query($sql);
	$num_rows = mysql_num_rows($res);
	echo $sql;
	while($r = $db->fetchNextObject($res)){
	?>
		<form action="include/php/sys_modelo.php" method="post">
		<input type="hidden" name="action" value="cliente_demandas" />
		<tr>
			<td style="text-align:center"> <?= $r->id;?></td>	
			<td style="text-align:center"><?= $r->nombre ." ". $r->apellidop ." ".$r->apellidom;?></td>
			<td style="text-align:center"> <?= $r->c_cobrador;?></td>
			<td style="text-align:center">$ <?= moneda($r->pago);?></td>
			<th style="text-align:center"><input type="checkbox" name="ids[]" value="<?= $r->id; ?>" /></th>
			<td width="80"><a href="?pg=2e&cl=<?= $r->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
		</tr>
		<?php
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th colspan="6">
			<input type="submit" name="demanda" id="demanda" value="Guardar &raquo;" />
			</th>
	</tr>
	</tfoot>
	</form>
	</table>
	<?php
}else{
	?>
	<p class="title">Clientes &raquo; Vencidos</p>
	<form name="repoFechas" action="" method="post">
	<table>
	<caption>Clientes con Recargos</caption>
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
	<th colspan="4"><input type="submit" value="Mostrar Lista" name="enviar" /></th>
	</tr>
	</tfoot>
	</table>
	</form>
	<?php
}
?>
