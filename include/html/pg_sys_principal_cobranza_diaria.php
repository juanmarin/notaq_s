
<?php
@session_start();
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$hoy = date("Y-m-d");
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

?>
	<table>
	<caption>Lista cobranza del dia : <?php echo date("d-m-Y"); ?> </caption>
	<thead>
	<tr>
	<th>Fecha Pago</th>
	<th>NOMBRE</th>
	<th>DIRECCION</th>
	<th>PAGOS</th>
	<th>PAGO</th>
	<th>ACCIONES</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$sql = "SELECT 
	clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, clientes.direccion, clientes.colonia, clientes.telefono, clientes.celular
	, pagos.id as idp, pagos.cuenta, pagos.fecha, pagos.pago, pagos.estado 
	FROM clientes, pagos 
	WHERE clientes.id = pagos.cliente 
	AND pagos.fecha BETWEEN '".$hoy."' AND '".$hoy."' 
	AND pagos.estado = 0 
	$clcobrador
	ORDER BY fecha ASC";
	$result = $db->query($sql);
	$_SESSION["QUERY"] = $sql;
	while ($ln = $db->fetchNextObject($result)){
	?>
		<tr>
		<th width="250px" style="text-align: center;"><?= getFecha($ln->fecha);?></th>
		<th width="250px" style="text-align: center"><?= $ln->nombre." ".$ln->apellidop." ".$ln->apellidom ;?></th>
		<th width="250px" style="text-align: center;"><?= $ln->direccion." ".$ln->colonia." ".$ln->telefono;?></th>
		<th width="250px" style="text-align: center;"><?= cuentaPagos($ln->cuenta, $ln->id);?></th>
		<th width="250px" style="text-align: center;"><?= "&#36;"; echo moneda($ln->pago);?></th>
		<th colspan="1" style="text-align: center;"><a href="?pg=2e&cl=<?= $ln->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></th>
		</tr>
	<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
	<th colspan="5">
	</tbody>
	<tfoot>
	
	</tfoot>
	</table>
	</form>