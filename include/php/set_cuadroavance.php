<?php
require_once("sys_db.class.php");
require_once("fun_global.php");
require_once("/home/confianzp/confianzp.com/include/conf/Config_con.php");
/*
require_once("sys_db.class.php");
require_once("fun_global.php");
require_once("../conf/Config_con.php");
*/
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
##-INICIALIZANDO VALORES
$se = date("W");
$an = date("Y");
$fe = date("Y-m-d");
$fi = date('Y-m-d', strtotime('-6 days'));
##-OBTENIENDO NUMERO DE CLIENTES POR COBRADOR
$sql = "SELECT us.username AS cobrador, us.nombre, COUNT(cl.c_cobrador) AS mis_ctes
	FROM mymvcdb_users us
	LEFT JOIN clientes cl ON us.username=cl.c_cobrador
	LEFT JOIN cuentas cu ON cl.id=cu.cliente 
	WHERE cu.estado=0
	GROUP BY us.username";
echo "<p>$sql</p>";
$res = $db->query($sql);
while($cob=$db->fetchNextObject($res))
{
	$cobra = $cob->cobrador;
	$miscl = $cob->mis_ctes;
	/// ***** ****** OBTENIEDO LOS CLIENTES CON PAGOS VENCIDOS 
	$sql2="SELECT sum(pa.pago) pago
		FROM pagos pa
		LEFT JOIN cuentas cu ON pa.cuenta=cu.id
		WHERE cu.estado=0 AND pa.estado=0 AND pa.fecha<'$fe' AND cu.cobrador='$cobra' 
		GROUP BY pa.cliente";
	echo "<p>$sql2</p>";
	$res2		= $db->query($sql2);
	$morosos	= ($res2)?$db->numRows():0;
	$corriente	= $cob->mis_ctes-$morosos;
	$avance	= ($corriente/$cob->mis_ctes)*100;
	/// ***** ****** Suma recargos y abonos de recargos cobrado
	$sql="SELECT sum(re.monto_saldado) pago 
		FROM recargos re 
		LEFT JOIN cuentas cu ON re.cuenta=cu.id 
		WHERE cu.estado=0 AND re.estado=1 AND date(re.fecha) BETWEEN '$fi' AND '$fe' AND re.aplicado_x='$cobra'";
	$rst=$db->query($sql);
	$row=$db->fetchNextObject($rst);
	$pas=$row->pago;
	echo "<p>$sql</p>";
	/// *****
	//-GUARDANDO PORCENTAJE DE AVANCE
	$sql = "INSERT INTO cuadroavance (year, week, cobrador, porcentaje, recargoscobrados)VALUES($an, $se, '$cobra', '$avance', '$pas')";
	echo "<p>$sql</p><hr>";
	$db->query($sql);
}
echo "<p>Fin</p>";
?>
