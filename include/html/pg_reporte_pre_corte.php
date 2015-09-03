<?php
@session_start();
require_once("include/php/sys_db.class.php");
require_once("include/php/fun_global.php");
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
if(isset($_POST['enviar']))
{
	$hoy = date("Y-m-d");
	?>
	<p class="title">Reportes &raquo; Reporte diario</p>
	<form action="" method="post">
	
	</form>
	<table>
	<caption>Reporte hasta el dia : <?php echo getFecha($hoy); ?> </caption>
	<thead>
		<tr>
		<th>Fecha Pago</th>
		<th colspan="2">Nombre</th>
		<th colspan="2">Pago Total</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$sql = "SELECT clientes.id AS clientes, CONCAT(clientes.nombre, ' ' ,clientes.apellidop, ' ' ,clientes.apellidom) AS cte_nom, 
		clientes.c_cobrador, pagos.fechaPago, pagos.pago_real AS pago_real, pagos.interes, pagos.estado, pagos.reportado 
		FROM clientes, pagos 
		WHERE clientes.id = pagos.cliente 
		AND DATE(pagos.fechaPago) <= '".$hoy."'
		AND pagos.pago_real > 0 
		AND (pagos.estado = 1 OR pagos.estado = 3) 
		AND pagos.reportado = 0
		$clcobrador";
		echo $sql;
		$result = $db->query($sql);
		$totGlobal=0;
		while ($ln = $db->fetchNextObject($result)){
			$totGlobal += $ln->pago_real;
			?>
			<tr>
			<th width="250px" style="text-align: center;"><?= getFecha($ln->fechaPago);?></th>
			<th colspan="3" width="250px" style="text-align: center"><?= strtoupper($ln->cte_nom) ;?></th>
			<th width="250px" style="text-align: center;"><?= "&#36;"; echo $ln->pago_real;?></th>
			</tr>
			<?php
		}
		?>
	</tbody>
	<tfoot>
	<tr>
	<th style="text-align:left" colspan="3">Total a entregar</th>
	<th style="text-align:center" colspan="2">$ <?=moneda($totGlobal);?></th>
	</tr>
	<tr>
		<form action="include/php/sys_modelo.php" method="post">
			<input type="hidden" name="action" value="corte_caja">
			<input type="hidden" name="cobrador" value="<?php echo $_POST["cobrador"]; ?>">
			<input type="hidden" name="supervisor" value="<?php echo $UserName; ?>" />
			<input type="hidden" name="totales" value="<?php echo $totGlobal;?>" />
			<input type="hidden" name="consulta" value="<?php echo $sql;?>" />
			<th colspan="5"><input type="submit" value="Realizar corte" name="enviar" /></th>
		</form>
	</tr>
	</tfoot>
	<table>
<?php
}else{
	?>
	<p class="title">Reportes &raquo; Corte de caja</p>
	<form name="repoFechas" action="" method="post">
	<table>
	<caption>Entrega de cobros recibidos</caption>
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
	<th colspan="4"><input type="submit" value="Mostrar Reporte" name="enviar" /></th>
	</tr>
	</tfoot>
	</table>
	</form>
	<?php
}
?>
