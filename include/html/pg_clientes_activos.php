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
<p class="title">Clientes &raquo; Listado de clientes activos</p>
<table>
<caption>CUENTAS ABIERTAS</caption>
<thead>
	<tr>
		<th>ID</th>
		<th>CLIENTE</th>
		<th>COBRADOR</th>
		<th>D. VENC.</th>
		<th>SALDO</th>
		<th></th>
	</tr>
</thead>
<tbody>
	<?php
	$sql = "SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, clientes.c_cobrador, cuentas.total 
			FROM clientes, cuentas 
			WHERE clientes.id = cuentas.cliente 
			AND cuentas.estado = 0
			$clcobrador
		ORDER BY clientes.nombre ASC";
	$res = $db->query($sql);
	$num_rows = mysql_num_rows($res);
	while($r = $db->fetchNextObject($res)){
		?>
		<tr>
			<td style="text-align:center"> <?= $r->id;?></td>
			<td style="text-align:center"><?= $r->nombre . " ". $r->apellidop ." " .$r->apellidom;?></td>
			<td style="text-align:center"> <?= $r->c_cobrador;?></td>
			<td style="text-align:center"> <?= diasVencidos($r->id);?></td>
			<td style="text-align:center">$ <?= moneda($r->total);?></td>
			<td width="80"><a href="?pg=2e&cl=<?= $r->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
		</tr>
		<?php
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th colspan="6"></th>
	</tr>
</tfoot>
</table>
<?php
}else{
?>
	<p class="title">Portada &raquo; Activos</p>
	<form name="repoFechas" action="" method="post">
	<table>
	<caption>Clientes Activos</caption>
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