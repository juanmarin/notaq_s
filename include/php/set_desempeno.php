<?php
require_once("sys_db.class.php");
require_once("fun_global.php");
require_once("/home/confianzp/confianzp.com/include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
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
$fi = date("Y-m-d");
$ff = date('Y-m-d', strtotime('+7 days'));
$se = date("W");
$an = date("Y");
$sql = "TRUNCATE TABLE desempeno_tmp";
$db->execute($sql);
$sql = "INSERT INTO desempeno_tmp (cobrador,idcl,idcta,idpago,fecha) 
	SELECT c.cobrador, p.cliente, p.cuenta, p.id, p.fecha
	FROM pagos p, cuentas c WHERE p.cuenta=c.id AND c.estado=0 AND p.estado=0
	AND p.fecha BETWEEN CAST('$fi' AS DATE) AND CAST('$ff' AS DATE)";
echo "<p>$sql</p>";
$res = $db->query($sql);
$sql = "SELECT id, fecha FROM desempeno_tmp";
echo "<p>$sql</p>";
$rel = $db->query($sql);
echo $db->numRows();
while( $des = $db->fetchNextObject($rel) )
{
	$fec = $des->fecha;
	$idd = $des->id;
	if( comparedate($fec) ){
		$dia = diasatrazo(date("Y-m-d"), $fec);
	}else{
		$dia = 0;
	}
	$sql = "UPDATE desempeno_tmp SET diasatraso = $dia WHERE id = $idd";
	$re1 = $db->query($sql);
}
$sql = "SELECT iddes FROM desempeno WHERE year = '$an' AND semana = ".date("W");
echo "<p>$sql</p>";
$res = $db->query($sql);
echo $db->numRows() . " Regisros encontrados.<br />Número de semana: ".date("W");
if($db->numRows()==0)
{
	$sql= "INSERT INTO desempeno (semana, cobrador, total, en_fecha, fuera_fecha, por_cobrar, year) 
		SELECT $se, cobrador, count(*), 0, 0, count(*), $an 
		FROM desempeno_tmp GROUP BY cobrador";
	echo "<p>$sql</p>";
	$res= $db->query($sql);
}else{
	echo "<p>La tabla ya existe.</p>";
}
?>
