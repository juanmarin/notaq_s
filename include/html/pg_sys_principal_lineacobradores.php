<?php
@session_start();
require_once("include/php/fun_global.php");
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
?>
<table>
	<caption>Comparativa de avance de cobradores</caption>
	<?php
	$sql = "SELECT username FROM mymvcdb_users WHERE nivel=3 GROUP BY username";
	$res = $db->query($sql);
	while($cob = $db->fetchNextObject($res))
	{
		echo "<tr>";
		echo "<td width='200'>".$cob->username."</td>";
		echo "<td>";
			echo "<div style='padding:1px;border:1px solid #bbb;height:12px;width:100%;background:#eee;'>";
			#-- pagos pendientes del dia
			$sql1 = "select * from (
				select cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha 
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha = '".date('Y-m-d')."'
				AND cobrador = '".$cob->username."'";
			$res1 = $db->query($sql1);
			//echo $sql1;
			$azul = $db->numRows($res1);
			#-- pagos pendientes de 0 a 7 dias
			$sql1 = "select * from (
				select cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha 
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha between '".date('Y-m-d' , strtotime('- 7 days'))."' and '".date('Y-m-d' , strtotime('- 1 days'))."' 
				AND cobrador = '".$cob->username."'";
			$res1 = $db->query($sql1);
			//echo $sql1;
			$verde = $db->numRows($res1);
			#-- pagos pendientes de 8 a 30 dias
			$sql1 = "select * from (
				select cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha 
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha between '".date('Y-m-d' , strtotime('- 30 days'))."' and '".date('Y-m-d' , strtotime('- 8 days'))."' 
				AND cobrador = '".$cob->username."'";
			$res1 = $db->query($sql1);
			$amarillo = $db->numRows($res1);
			#-- pagos pendientes de 31 a 60 dias
			$sql1 = "select * from (
				select cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha 
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha between '".date('Y-m-d' , strtotime('- 60 days'))."' and '".date('Y-m-d' , strtotime('- 31 days'))."' 
				AND cobrador = '".$cob->username."'";
			$res1 = $db->query($sql1);
			$rojo = $db->numRows($res1);
			#-- pagos pendientes de mas de 60 dias
			$sql1 = "select * from (
				select cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha 
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha < '".date('Y-m-d' , strtotime('- 60 days'))."'
				AND cobrador = '".$cob->username."'";
			$res1 = $db->query($sql1);
			$negro = $db->numRows($res1);
			//-resultados
			$suma = $azul + $verde + $amarillo + $rojo + $negro;
			$ancho = ( $azul / $suma ) * 100;
			echo "<div style='background-color:#000080;height:11px;width:$ancho%;float:left;'></div>";
			$ancho = ( $verde / $suma ) * 100;
			echo "<div style='background-color:#009900;height:11px;width:$ancho%;float:left;'></div>";
			$ancho = ( $amarillo / $suma ) * 100;
			echo "<div style='background-color:#FFFF00;height:11px;width:$ancho%;float:left;'></div>";
			$ancho = ( $rojo / $suma ) * 100;
			echo "<div style='background-color:#FF0000;height:11px;width:$ancho%;float:left;'></div>";
			$ancho = ( $negro / $suma ) * 100;
			echo "<div style='background-color:#000000;height:11px;width:$ancho%;float:left;'></div>";
			echo "<div style='clear:both;'></div>";
			echo "</div>";
		echo "</td>";
		echo "</tr>";
	}
	?>
</table>
