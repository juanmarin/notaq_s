<?php
	session_start();
	$UserName = $_SESSION["USERNAME"];
	$UserLevel = $_SESSION["U_NIVEL"];
?>
<p class="title">Portada &raquo; Listado de Clientes Morosos</p>
<table>
<caption>CUENTAS CON RECARGOS</caption>
<thead>
	<tr>
		<th>ID</th>
		<th>CLIENTE</th>
		<th>CARGOS</th>
		<th>DEMANDAR</th>
		<th colspan="2">ACCIONES</th>
	</tr>
</thead>
<tbody>
	<?php
    $fecha = date("Y-m-d");
echo $fecha;
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	if($UserLevel == 0){
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidop, clientes.demanda, cuentas.cliente, 
				   cuentas.cobrador, cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha, 
				   SUM(pagos.pago) AS pago, pagos.estado
            FROM clientes, cuentas, pagos 
            WHERE
                clientes.id = cuentas.cliente AND
                clientes.demanda != 1 AND
                cuentas.id = pagos.cuenta AND
                cuentas.estado = 0 AND
                pagos.estado = 0 AND
                pagos.fecha < '".$fecha."'
            GROUP BY pagos.cliente 
            ORDER BY clientes.nombre ASC";
	}elseif($UserLevel != 0){
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidop, clientes.demanda, cuentas.cliente, 
				   cuentas.cobrador, cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha, 
				   SUM(pagos.pago) AS pago, pagos.estado
            FROM clientes, cuentas, pagos 
            WHERE
                clientes.id = cuentas.cliente AND
		clientes.c_cobrador = '".$UserName."' AND
                clientes.demanda != 1 AND
                cuentas.id = pagos.cuenta AND
		cuentas.cobrador = '".$UserName."' AND
                cuentas.estado = 0 AND
                pagos.estado = 0 AND
                pagos.fecha < '".$fecha."'
            GROUP BY pagos.cliente 
            ORDER BY clientes.nombre ASC";
	}{
	}	
	$res = $db->query($sql);
	$num_rows = mysql_num_rows($res);
	while($r = $db->fetchNextObject($res)){
		?>
		<form action="include/php/sys_modelo.php" method="post">
		<input type="hidden" name="action" value="cliente_demandas" />
		<tr>
			<td style="text-align:center"> <?php echo $r->id;?></td>	
			<td style="text-align:center"><?php echo $r->nombre ." ". $r->apellidop ." " .$r->apellidom;?></td>
			<td style="text-align:center">$ <?php echo moneda($r->pago);?></td>
			<th style="text-align:center"><input type="checkbox" name="ids[]" value="<?php echo $r->id; ?>" /></th>
			<td width="80"><a href="?pg=2e&cl=<?php echo $r->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
		</tr>
		<?php
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th colspan="5">
			<input type="submit" name="demanda" id="demanda" value="Guardar &raquo;" />
			</th>
	</tr>
</tfoot>
</form>
</table>
