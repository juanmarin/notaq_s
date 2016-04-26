<?php
/*-INICIALIZAR-TABLA-DESEMPEÑO/////////////////////////////////////////////////////////////////////
-tabla desempeno--//
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

//-CREAR-TABLA-DESEMPENO_TMP
CREATE TABLE `desempeno_tmp` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `cobrador` VARCHAR(45) NULL COMMENT '',
  `idcl` INT NULL COMMENT '',
  `idcta` INT NULL COMMENT '',
  `fecha` DATE NULL COMMENT '',
  `monto` DOUBLE NULL COMMENT '',
  `diasatraso` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '');
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
require_once("/home/confianzp/confianzp.com/include/php/sys_db.class.php");
require_once("/home/confianzp/confianzp.com/include/php/fun_global.php");
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
$sql = "TRUNCATE TABLE desempeno_tmp";
$db->execute($sql);
##-LLENAR-CON-DATOS
$sql = "INSERT INTO desempeno_tmp (cobrador,idcl,idcta,idpago,fecha) 
	SELECT c.cobrador, p.cliente, p.cuenta, p.id, p.fecha
	FROM pagos p, cuentas c WHERE p.cuenta=c.id AND c.estado=0 AND p.estado=0
	AND p.fecha BETWEEN CAST('$fi' AS DATE) AND CAST('$ff' AS DATE)";
echo "<p>$sql</p>";
$res = $db->query($sql);
##-COMPROBAR ESTADO DE LOS PAGOS
/*
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
*/
#
#
##-ACTUALIZANDO TABLA DE DESEMPEÑO
//echo date("W");
//mysql  SELECT WEEK(NOW(),3)
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
	/*
	#-OBTENER NÚMERO DE COBROS EN FECHA
	$sql= "SELECT iddes,cobrador FROM desempeno WHERE year=$an AND semana=$se";
	echo "<p>$sql</p>";
	$res=$db->query($sql);
	while($d=$db->fetchNextObject($res))
	{
		#.INICIALIZAR VARIABLES
		$cob = $d->cobrador;
		$idd = $d->iddes;
		#-PAGOS EN FECHA
		$sql = "SELECT count(*) ef FROM desempeno_tmp WHERE cobrador = '$cob' AND diasatraso = 0";
		echo "<p>$sql</p>";
		$re1 = $db->query($sql);
		$des = $db->fetchNextObject($re1);
		$ef  = $des->ef;
		#-PAGOS FUERA DE FECHA
		$sql = "SELECT count(*) ff FROM desempeno_tmp WHERE cobrador = '$cob' AND diasatraso > 0";
		echo "<p>$sql</p>";
		$re1 = $db->query($sql);
		$des = $db->fetchNextObject($re1);
		$ff  = $des->ff;
		#-PAGOS POR COBRAR
		$pc  = $ff+$ef;
		#-ACTUALIZAR VAKORES EN REPORTE DE DESEMPEÑO
		$sql = "UPDATE desempeno SET en_fecha=$ef, fuera_fecha=$ff, por_cobrar=$pc WHERE iddes=$idd";
		echo "<p>$sql</p>";
		$re1 = $db->query($sql);
	}
	*/
}else{
	echo "<p>La tabla ya existe.</p>";
}
///////////////////////////////////////////////////////////////////////////////////////////////////
?>
