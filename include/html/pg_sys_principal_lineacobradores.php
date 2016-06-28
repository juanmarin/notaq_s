<?php
@session_start();
require_once("include/php/fun_global.php");
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
## IDENTIFICAR USUARIOS QUE PUEDEN VER DINEROS ##
if( $_SESSION["REQUIRED1"]==1 || $_SESSION["REQUIRED1"]==20 ){
	$verdinero=true;
}else{
	$verdinero=false;
}
?>
<table>
<caption>Porcentaje de riesgo de vencimiento de cartera</caption>
<tbody>
	<?php
	#TABLA TEMPORAL CON LA LISTA DE CUENTAS Y SU ESTADO
	/*
	$db->execute("TRUNCATE TABLE v_cuentas");
	$sql = "INSERT INTO v_cuentas
		SELECT 
		cl.c_cobrador 'cobrador'
		,cu.id 'cuenta'
		,cl.id 'cliente'
		,(SELECT IFNULL(SUM(pa.pago),0) pago FROM pagos pa WHERE pa.cuenta = cu.id AND pa.estado=0 AND pa.fecha < CURDATE()) AS 'vencido'
		,@dd:=IFNULL((SELECT DATEDIFF(CURDATE(),pa.fecha) FROM pagos pa WHERE pa.cuenta = cu.id AND pa.estado=0 AND pa.fecha < CURDATE() ORDER BY pa.fecha ASC LIMIT 0,1),0) AS 'diasvencidos'
		,CASE
			WHEN @dd=0 THEN 'AZULCLARO'
			WHEN @dd=1 THEN 'AZUL'
			WHEN (@dd>1 AND @dd<8) THEN 'VERDE'
			WHEN (@dd>7 AND @dd<=30) THEN 'AMARILLO'
			WHEN (@dd>30 AND @dd<=60) THEN 'ROJO'
			WHEN (@dd>61 AND @dd<=90) THEN 'MORADO'
			WHEN (@dd>91) THEN 'NEGRO'
		END AS 'color'
		FROM clientes cl 
		RIGHT JOIN cuentas cu ON cl.id=cu.cliente
		LEFT JOIN mymvcdb_users co ON cl.c_cobrador=co.username
		WHERE cu.estado=0";
	$db->execute($sql);
	*/
	$usercob = ($UserLevel==3)? "WHERE username='$UserName'":'WHERE nivel=3';
	$sql = "SELECT username FROM mymvcdb_users $usercob GROUP BY username";
	$res = $db->query($sql);
	while($cob = $db->fetchNextObject($res))
	{
		#INICIALIZAR VARIABLES
		$COBRADOR=$cob->username;
		echo "<tr>";
		echo "<td width='200'>$COBRADOR</td>";
		echo "<td>";
			echo "<div style='padding:1px;border:1px solid #bbb;height:12px;width:100%;background:#eee;'>";
			#-- clientes al día
			$sql1 = "SELECT COUNT(*) cantidad, SUM(vencido) monto FROM v_cuentas WHERE diasvencidos = 0 AND cobrador='$COBRADOR'";
			//secho $sql1;
			$res1		= $db->query($sql1);
			$ln		= $db->fetchNextObject($res1);
			$azulclaro	= $ln->cantidad;
			$azulclaro_	= $ln->monto;
			#-- pagos pendientes del dia
			#$sql1 = "SELECT COUNT(pa.pago) cantidad, SUM(pa.pago) monto FROM pagos pa LEFT JOIN cuentas cu ON pa.cuenta=cu.id WHERE cu.estado=0 AND pa.estado=0 AND pa.fecha>curdate() AND cu.cobrador='$COBRADOR' GROUP BY pa.cliente";
			#-- [CAMBIO] - Listar todos los pagos con fecha entre los rangos de la semna actual
			#$sql1 = "SELECT COUNT(*) cantidad, SUM(vencido) monto FROM v_cuentas WHERE diasvencidos = 0 AND cobrador='$COBRADOR'";
			$sql1	= "SELECT count(pagos.pago) cantidad, sum(pagos.pago) monto FROM clientes, pagos WHERE clientes.id = pagos.cliente AND pagos.fecha = curdate() AND pagos.estado = 0 AND clientes.c_cobrador='$COBRADOR'";
			//secho $sql1;
			$res1 = $db->query($sql1);
			$ln   = $db->fetchNextObject($res1);
			$azul = $ln->cantidad;
			$azul_= $ln->monto;
			#-- pagos pendientes de 0 a 7 dias
			$sql1 = "SELECT COUNT(*) cantidad, SUM(vencido) monto FROM v_cuentas WHERE diasvencidos > 0 AND diasvencidos < 8 AND cobrador='$COBRADOR'";
			//echo $sql1;
			$res1  = $db->query($sql1);
			$ln    = $db->fetchNextObject($res1);
			$verde = $ln->cantidad;
			$verde_= $ln->monto;
			#-- pagos pendientes de 8 a 30 dias
			$sql1 = "SELECT COUNT(*) cantidad, SUM(vencido) monto FROM v_cuentas WHERE diasvencidos > 7 AND diasvencidos < 31 AND cobrador='$COBRADOR'";
			//echo $sql1;
			$res1 	= $db->query($sql1);
			$ln		= $db->fetchNextObject($res1);
			$amarillo	= $ln->cantidad;
			$amarillo_	= $ln->monto;
			#-- pagos pendientes de 31 a 60 dias
			$sql1 = "SELECT COUNT(*) cantidad, SUM(vencido) monto FROM v_cuentas WHERE diasvencidos > 30 AND diasvencidos < 61 AND cobrador='$COBRADOR'";
			//echo $sql1;
			$res1 = $db->query($sql1);
			$ln   = $db->fetchNextObject($res1);
			$rojo = $ln->cantidad;
			$rojo_= $ln->monto;
			#-- pagos pendientes de hasta 60 dias
			$sql1 = "SELECT COUNT(*) cantidad, SUM(vencido) monto FROM v_cuentas WHERE diasvencidos > 60 AND diasvencidos < 91 AND cobrador='$COBRADOR'";
			//echo $sql1;
			$res1  = $db->query($sql1);
			$ln    = $db->fetchNextObject($res1);
			$morado = $ln->cantidad;
			$morado_= $ln->monto;
			#-- pagos pendientes de hasta 60 dias
			$sql1 = "SELECT COUNT(*) cantidad, SUM(vencido) monto FROM v_cuentas WHERE diasvencidos > 90 AND cobrador='$COBRADOR'";
			//echo $sql1;
			$res1  = $db->query($sql1);
			$ln    = $db->fetchNextObject($res1);
			$negro = $ln->cantidad;
			$negro_= $ln->monto;
			//-resultados
			$suma = $azulclaro + $azul + $verde + $amarillo + $rojo + $morado + $negro;
			$ancho = ( $azulclaro / $suma ) * 100;
			$moneda= ($verdinero)?", $".moneda($azulclaro_, 0):"";
			echo "<div style='background-color:#3399FF;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$azulclaro clientes, ".moneda($ancho, 0)." %$moneda'></div>";
			$ancho = ( $azul / $suma ) * 100;
			$moneda= moneda($azul_, 0);
			echo "<div style='background-color:#0066CC;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$azul clientes, ".moneda($ancho, 0)." %$moneda'></div>";
			$ancho = ( $verde / $suma ) * 100;
			$moneda= moneda($verde_, 0);
			echo "<div style='background-color:#4acc66;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$verde clientes, ".moneda($ancho, 0)." %$moneda'></div>";
			$ancho = ( $amarillo / $suma ) * 100;
			$moneda= moneda($amarillo_, 0);
			echo "<div style='background-color:#f3ce2e;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$amarillo clientes, ".moneda($ancho, 0)." %$moneda'></div>";
			$ancho = ( $rojo / $suma ) * 100;
			$moneda= moneda($rojo_, 0);
			echo "<div style='background-color:#ce1818;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$rojo clientes, ".moneda($ancho, 0)." %$moneda'></div>";
			$ancho = ( $morado / $suma ) * 100;
			$moneda= moneda($morado_, 0);
			echo "<div style='background-color:#990DF0;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$morado clientes, ".moneda($ancho, 0)." %$moneda'></div>";
			$ancho = ( $negro / $suma ) * 100;
			$moneda= moneda($negro_, 0);
			echo "<div style='background-color:#000000;height:11px;width:$ancho%;float:left;' class='masterTooltip' title='$negro clientes, ".moneda($ancho, 0)." %$moneda'></div>";
			echo "<div style='clear:both;'></div>";
			echo "</div>";
			echo "</td>";
			echo "</tr>";
		$Tazulclaro	+= $azulclaro;
		$Tazulclaro_	+= $azulclaro_;
		$Tazul		+= $azul;
		$Tazul_		+= $azul_;
		$Tverde		+= $verde;
		$Tverde_	+= $verde_;
		$Tamarillo	+= $amarillo;
		$Tamarillo_	+= $amarillo_;
		$Trojo		+= $rojo;
		$Trojo_		+= $rojo_;
		$Tmorado	+= $morado;
		$Tmorado_	+= $morado_;
		$Tnegro		+= $negro;
		$Tnegro_	+= $negro_;
			
	}	
		if (($UserLevel == 0) && ($UserName == "jmarincastro") || ($UserName == "francisco")){
			echo "<tr>";
			echo "<td width='200'>TOTALES</td>";
			echo "<td>";
			echo "<div style='padding:1px;border:1px solid #bbb;height:12px;width:100%;background:#eee;'>";
			$Gtotal = $Tazulclaro + $Tazul + $Tverde + $Tamarillo + $Trojo + $Tmorado + $Tnegro;
			$Tancho = ( $Tazulclaro / $Gtotal ) * 100;
			$moneda = ($verdinero)?", $".moneda($Tazulclaro_, 0):"";
			echo "<div style='background-color:#3399FF;height:11px;width:$Tancho%;float:left;' class='masterTooltip' title='$Tazulclaro clientes, ".moneda($Tancho, 0)." %$moneda'></div>";
			$Tancho = ( $Tazul / $Gtotal ) * 100;
			$moneda = moneda($Tazul_, 0);
			echo "<div style='background-color:#0066CC;height:11px;width:$Tancho%;float:left;' class='masterTooltip' title='$Tazul clientes, ".moneda($Tancho, 0)." %$moneda'></div>";
			$Tancho = ( $Tverde / $Gtotal ) * 100;
			$moneda = moneda($Tverde_, 0);
			echo "<div style='background-color:#4acc66;height:11px;width:$Tancho%;float:left;' class='masterTooltip' title='$Tverde clientes, ".moneda($Tancho, 0)." %$moneda'></div>";
			$Tancho = ( $Tamarillo / $Gtotal ) * 100;
			$moneda = moneda($Tamarillo_, 0);
			echo "<div style='background-color:#f3ce2e;height:11px;width:$Tancho%;float:left;' class='masterTooltip' title='$Tamarillo clientes, ".moneda($Tancho, 0)." %$moneda'></div>";
			$Tancho = ( $Trojo / $Gtotal ) * 100;
			$moneda = moneda($Trojo_, 0);
			echo "<div style='background-color:#ce1818;height:11px;width:$Tancho%;float:left;' class='masterTooltip' title='$Trojo clientes, ".moneda($Tancho, 0)." %$moneda'></div>";
			$Tancho = ( $Tmorado / $Gtotal ) * 100;
			$moneda = moneda($Tmorado_, 0);
			echo "<div style='background-color:#990DF0;height:11px;width:$Tancho%;float:left;' class='masterTooltip' title='$Tmorado clientes, ".moneda($Tancho, 0)." %$moneda'></div>";
			$Tancho = ( $Tnegro / $Gtotal ) * 100;
			$moneda = moneda($Tnegro_, 0);
			echo "<div style='background-color:#000000;height:11px;width:$Tancho%;float:left;' class='masterTooltip' title='$Tnegro clientes, ".moneda($Tancho, 0)." %$moneda'></div>";
			echo "<div style='clear:both;'></div>";
			echo "</div>";
			echo "</td>";
			echo "</tr>";
		}
	?>
</tbody>
</table>
