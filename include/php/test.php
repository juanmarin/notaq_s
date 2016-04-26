<?php
//////////////////////////////////////////////////////////////////////////////////////////////////
/*
require_once("/var/www/html/notaq_s/include/php/sys_db.class.php");
require_once("/var/www/html/notaq_s/include/php/fun_global.php");
require_once("/var/www/html/notaq_s/include/conf/Config_con.php");
*/
require_once("sys_db.class.php");
require_once("fun_global.php");
//require_once("../conf/Config_con.php");
require_once("/home/confianzp/confianzp.com/include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
#
#
#-FUNCIONES
function diasatrazo($hoy, $proxpago){
	$dStart = new DateTime($hoy);
	$dEnd  = new DateTime($proxpago);
	$dDiff = $dStart->diff($dEnd);
	$dDiff->format('%R'); // use for point out relation: smaller/greater
	return $dDiff->days;
}
function comparedate($fecha)
{
	#-DEVUELVE TRUE SI LA FECHA DADA ES ANTERIOR AL DÍA DE HOY
	$f = split("-", $fecha);
	if( date("Y")>$f[0] ){
		return true;
	}
	elseif( (date("Y")==$f[0]) && (date("m")>$f[1]) ){
		return true;
	}
	elseif( (date("Y")==$f[0]) && (date("m")==$f[1]) && (date("d")>=$f[2]) ){
		return true;
	}
	else{
		return false;
	}
}
##-INICIALIZANDO VALORES
$fi = date("Y-m-d");
$ff = date('Y-m-d', strtotime('+7 days'));
$se = date("W");
$an = date("Y");
#-IDENTIFICAR LISTADO DE CLIENTES CON SU COBRADOR - DESEMPENO_TMP
##-INICIALIZAR TABLA
$sql = "TRUNCATE TABLE tmp_desempeno";
$db->execute($sql);
##-LLENAR-CON-DATOS
$sql = "INSERT INTO tmp_desempeno (cobrador,idcl,idcta,idpago,fecha) 
	SELECT c.cobrador, p.cliente, p.cuenta, p.id, p.fecha
	FROM pagos p, cuentas c WHERE p.cuenta=c.id AND c.estado=0 AND p.estado=0
	AND p.fecha BETWEEN CAST('$fi' AS DATE) AND CAST('$ff' AS DATE)";
echo "<p>$sql</p>";
$res = $db->query($sql);
$sql = "SELECT iddes FROM desempeno WHERE year = '$an' AND semana = ".date("W");
echo "<p>$sql</p>";
$res = $db->query($sql);
echo $db->numRows() . " Regisros encontrados.<br />Número de semana: ".date("W");
if($db->numRows()==0)
{
	$sql= "INSERT INTO desempeno (semana, cobrador, total, en_fecha, fuera_fecha, por_cobrar, year) 
		SELECT $se, cobrador, count(*), 0, 0, count(*), $an FROM desempeno_tmp GROUP BY cobrador";
	echo "<p>$sql</p>";
}else{
	echo "<p>La tabla ya existe.</p>";
}
///////////////////////////////////////////////////////////////////////////////////////////////////
?>
