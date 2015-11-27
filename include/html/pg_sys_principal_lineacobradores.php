<?php
@session_start();
require_once("include/php/fun_global.php");
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
?>
<table>
<caption>Porcentaje de riesgo de vencimiento de cartera</caption>
<tbody>
	<?php
	$usercob = ($UserLevel==3)? "WHERE username='$UserName'":'WHERE nivel=3';
	$sql = "SELECT username FROM mymvcdb_users $usercob GROUP BY username";
	$res = $db->query($sql);
	while($cob = $db->fetchNextObject($res))
	{
		echo "<tr>";
		echo "<td width='200'>".$cob->username."</td>";
		echo "<td>";
			echo "<div style='padding:1px;border:1px solid #bbb;height:12px;width:100%;background:#eee;'>";
			#-- clientes al día
			$sql1 = "SELECT * FROM (
			SELECT clientes.id, clientes.demanda, clientes.c_cobrador
			, cuentas.cobrador , cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha
			FROM clientes, cuentas, pagos 
			WHERE clientes.id = cuentas.cliente 
			AND clientes.demanda != 1 
			AND cuentas.id = pagos.cuenta 
			AND cuentas.estado = 0 
			AND pagos.estado = 0 
			AND clientes.c_cobrador='".$cob->username."' 
			GROUP BY pagos.cliente ) AS cartera
			where fecha > '".date('Y-m-d')."'";
			//secho $sql1;
			$res1 = $db->query($sql1);
			$azulclaro = $db->numRows($res1);
			#-- pagos pendientes del dia
			$sql1 = "SELECT * FROM (
			SELECT clientes.id, clientes.demanda, clientes.c_cobrador
			, cuentas.cobrador , cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha
			FROM clientes, cuentas, pagos 
			WHERE clientes.id = cuentas.cliente 
			AND clientes.demanda != 1 
			AND cuentas.id = pagos.cuenta 
			AND cuentas.estado = 0 
			AND pagos.estado = 0 
			AND clientes.c_cobrador='".$cob->username."' 
			GROUP BY pagos.cliente ) AS cartera
			where fecha = '".date('Y-m-d')."'";
			//secho $sql1;
			$res1 = $db->query($sql1);
			$azul = $db->numRows($res1);
			#-- pagos pendientes de 0 a 7 dias
			$sql1 = "SELECT * FROM (
			SELECT clientes.id, clientes.demanda, clientes.c_cobrador
			, cuentas.cobrador , cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha
			FROM clientes, cuentas, pagos 
			WHERE clientes.id = cuentas.cliente 
			AND clientes.demanda != 1 
			AND cuentas.id = pagos.cuenta 
			AND cuentas.estado = 0 
			AND pagos.estado = 0 
			AND clientes.c_cobrador='".$cob->username."' 
			GROUP BY pagos.cliente ) AS cartera
			where fecha between '".date('Y-m-d' , strtotime('- 7 days'))."' and '".date('Y-m-d' , strtotime('- 1 days'))."'";
			//echo $sql1;
			$res1 = $db->query($sql1);
			$verde = $db->numRows($res1);
			#-- pagos pendientes de 8 a 30 dias
			$sql1 = "SELECT * FROM (
			SELECT clientes.id, clientes.demanda, clientes.c_cobrador
			, cuentas.cobrador , cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha
			FROM clientes, cuentas, pagos 
			WHERE clientes.id = cuentas.cliente 
			AND clientes.demanda != 1 
			AND cuentas.id = pagos.cuenta 
			AND cuentas.estado = 0 
			AND pagos.estado = 0 
			AND clientes.c_cobrador='".$cob->username."' 
			GROUP BY pagos.cliente ) AS cartera
			where fecha between '".date('Y-m-d' , strtotime('- 30 days'))."' and '".date('Y-m-d' , strtotime('- 8 days'))."'";
			//echo $sql1;
			$res1 = $db->query($sql1);
			$amarillo = $db->numRows($res1);
			#-- pagos pendientes de 31 a 60 dias
			$sql1 = "SELECT * FROM (
			SELECT clientes.id, clientes.demanda, clientes.c_cobrador
			, cuentas.cobrador , cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha
			FROM clientes, cuentas, pagos 
			WHERE clientes.id = cuentas.cliente 
			AND clientes.demanda != 1 
			AND cuentas.id = pagos.cuenta 
			AND cuentas.estado = 0 
			AND pagos.estado = 0 
			AND clientes.c_cobrador='".$cob->username."' 
			GROUP BY pagos.cliente ) AS cartera
			where fecha between '".date('Y-m-d' , strtotime('- 60 days'))."' and '".date('Y-m-d' , strtotime('- 31 days'))."'";
			//echo $sql1;
			$res1 = $db->query($sql1);
			$rojo = $db->numRows($res1);
			#-- pagos pendientes de mas de 60 dias
			$sql1 = "SELECT * FROM (
			SELECT clientes.id, clientes.demanda, clientes.c_cobrador
			, cuentas.cobrador , cuentas.estado, pagos.cuenta, pagos.cliente, pagos.fecha
			FROM clientes, cuentas, pagos 
			WHERE clientes.id = cuentas.cliente 
			AND clientes.demanda != 1 
			AND cuentas.id = pagos.cuenta 
			AND cuentas.estado = 0 
			AND pagos.estado = 0 
			AND clientes.c_cobrador='".$cob->username."' 
			GROUP BY pagos.cliente ) AS cartera
			where fecha < '".date('Y-m-d' , strtotime('- 60 days'))."'";
			//echo $sql1;
			$res1 = $db->query($sql1);
			$negro = $db->numRows($res1);
			//-resultados
			$suma = $azulclaro + $azul + $verde + $amarillo + $rojo + $negro;
			$ancho = ( $azulclaro / $suma ) * 100;
			echo "<div style='background-color:#3399FF;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$azulclaro clientes, ".moneda($ancho, 0)." %'></div>";
			$ancho = ( $azul / $suma ) * 100;
			echo "<div style='background-color:#0066CC;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$azul clientes, ".moneda($ancho, 0)." %'></div>";
			$ancho = ( $verde / $suma ) * 100;
			echo "<div style='background-color:#4acc66;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$verde clientes, ".moneda($ancho, 0)." %'></div>";
			$ancho = ( $amarillo / $suma ) * 100;
			echo "<div style='background-color:#f3ce2e;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$amarillo clientes, ".moneda($ancho, 0)." %'></div>";
			$ancho = ( $rojo / $suma ) * 100;
			echo "<div style='background-color:#ce1818;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$rojo clientes, ".moneda($ancho, 0)." %'></div>";
			$ancho = ( $negro / $suma ) * 100;
			echo "<div style='background-color:#a020f0;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$negro clientes, ".moneda($ancho, 0)." %'></div>";
			echo "<div style='clear:both;'></div>";
			echo "</div>";
		echo "</td>";
		echo "</tr>";
	}
	?>
</tbody>
</table>
