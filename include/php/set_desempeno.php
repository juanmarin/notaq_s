<?php
//-INICIALIZAR-TABLA-DESEMPEÑO/////////////////////////////////////////////////////////////////////
/*-tabla desempeno--//
CREATE TABLE `desempeno` (
  `Iddes` int(11) NOT NULL auto_increment,
  `semana` int(3) NOT NULL default '0',
  `cobrador` varchar(255) NOT NULL default '',
  `total` double default NULL,
  `en_fecha` int(11) default NULL,
  `fuera_fecha` int(11) default NULL,
  `por_cobrar` int(11) default NULL,
  PRIMARY KEY  (`Iddes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*-----------------------------------------------trigger-
BEGIN
	IF OLD.estado = 0 AND NEW.estado > 0 THEN
		IF NEW.fechaPago <= OLD.fecha THEN
			UPDATE desempeno 
			SET en_fecha = en_fecha + 1, por_cobrar = por_cobrar - 1
			WHERE semana=WEEK(NOW(),3) AND year=YEAR(CURDATE()) AND cobrador=NEW.aplicado_x;
		ELSEIF NEW.fechaPago > OLD.fecha THEN
			UPDATE desempeno 
			SET fuera_fecha = fuera_fecha + 1, por_cobrar = por_cobrar - 1
			WHERE semana=WEEK(NOW(),3) AND year=YEAR(CURDATE()) AND cobrador=NEW.aplicado_x;
		END IF;
	ELSEIF OLD.estado > 0 AND NEW.estado = 0 THEN
		IF OLD.fechaPago <= OLD.fecha THEN
			UPDATE desempeno 
			SET en_fecha = en_fecha - 1, por_cobrar = por_cobrar + 1
			WHERE semana=WEEK(NOW(),3) AND year=YEAR(CURDATE()) AND cobrador=NEW.aplicado_x;
		ELSEIF OLD.fechaPago > OLD.fecha THEN
			UPDATE desempeno 
			SET fuera_fecha = fuera_fecha - 1, por_cobrar = por_cobrar + 1
			WHERE semana=WEEK(NOW(),3) AND year=YEAR(CURDATE()) AND cobrador=NEW.aplicado_x;
		END IF;
	END IF;
END
---------------------------------------------------------*/
//////////////////////////////////////////////////////////////////////////////////////////////////
require_once("sys_db.class.php");
require_once("fun_global.php");
require_once("../conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
//echo date("W");
//mysql  SELECT WEEK(NOW(),3)
$sql = "SELECT iddes FROM desempeno WHERE semana = ".date("W");
$res = $db->query($sql);
echo $db->numRows() . " Regisros encontrados.<br />Número de semana: ".date("W");
if($db->numRows()==0)
{
	$fi = date("Y-m-d");
	$ff = date('Y-m-d', strtotime('+7 days'));
	$sql="SELECT cu.cobrador, count(pa.id) total 
		FROM cuentas cu, pagos pa 
		WHERE cu.id=pa.cuenta AND cu.estado=0  AND pa.estado<2
		AND pa.fecha BETWEEN CAST('$fi' AS DATE) AND CAST('$ff' AS DATE)
		GROUP BY cu.cobrador ORDER BY cu.cobrador";
	echo $sql;
	$res=$db->query($sql);
	echo "<br />".$db->numRows()."<br />";
	while($rd=$db->fetchNextObject($res))
	{
		#INFORMACION PRINCIPAL DE REPORTE DE DESEMPEÑO, VENDEDOR Y TOTAL COBRADO
		$dcobra = $rd->cobrador;
		$dtotal = $rd->total;
		$totalavance = $rd->total;
		#BUSCANDO COBROS EN FECHA
		$sql="SELECT count(*) cobrosef
		FROM cuentas cu, pagos pa
		WHERE cu.id=pa.cuenta AND cu.estado=0 AND pa.estado=1 AND pa.fecha BETWEEN CAST('$fi' AS DATE) AND CAST('$ff' AS DATE)
		AND cu.cobrador='".$rd->cobrador."'
		AND pa.fechaPago<=pa.fecha";
		$re2=$db->query($sql);
		while($get=$db->fetchNextObject($re2))
		{
			$cobrosenfecha = $get->cobrosef;
		}
		#BUSCANDO COBROS FUERA DE FECHA
		$sql="SELECT count(*) cobrosff
		FROM cuentas cu, pagos pa
		WHERE cu.id=pa.cuenta AND cu.estado=0 AND pa.estado=1 AND pa.fecha BETWEEN CAST('$fi' AS DATE) AND CAST('$ff' AS DATE)
		AND cu.cobrador='".$rd->cobrador."'
		AND pa.fechaPago>pa.fecha";
		$re2=$db->query($sql);
		while($get=$db->fetchNextObject($re2))
		{
			$cobrosfufecha = $get->cobrosff;
		}
		#BUSCANDO COBROS PENDIENTES
		$sql="SELECT count(*) cobrospc
		FROM cuentas cu, pagos pa
		WHERE cu.id=pa.cuenta AND cu.estado=0 AND pa.estado=0 AND pa.fecha BETWEEN CAST('$fi' AS DATE) AND CAST('$ff' AS DATE)
		AND cu.cobrador='".$rd->cobrador."'";
		$re2=$db->query($sql);
		while($get=$db->fetchNextObject($re2))
		{
			$cobrosporcobr = $get->cobrospc;
		}		
		$sql = "INSERT INTO desempeno (semana,cobrador,total, en_fecha, fuera_fecha, por_cobrar, year)
			VALUES(".date("W").", '$dcobra', $dtotal, $cobrosenfecha, $cobrosfufecha, $cobrosporcobr, ".date("Y").")";
		echo "<br />$sql<br />";
		$db->execute($sql);
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////
?>
