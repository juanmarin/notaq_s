<?php
@session_start();
/*
CREATE TABLE `coordenadas` (
  `idcoord` int(11) NOT NULL auto_increment,
  `cliente` int(11) default NULL,
  `latitud` varchar(255) default NULL,
  `longitud` varchar(255) default NULL,
  `zoom` int(11) default NULL,
  `fecha` datetime default NULL,
  `aplicado_x` varchar(10) default NULL,
  PRIMARY KEY  (`idcoord`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Coordenadas para geolocalización de google maps';

*/
require_once("../../include/php/sys_db.class.php");
require_once("../../include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);

if( isset($_POST["guardarmapa"]) ){
	$cliente = $_POST["cl"];
	$sql = "SELECT idcoord FROM coordenadas WHERE cliente = $cliente LIMIT 1";
	$res = $db->query($sql);
	if( $db->numRows($res)>0 ){
		$sql = "UPDATE coordenadas SET 
			latitud		= '".$_POST["la"]."', 
			longitud	= '".$_POST["lo"]."', 
			zoom		=  ".$_POST["zo"].", 
			fecha		= '".date("Y-m-d H:i:s")."', 
			aplicado_x	= '".$_SESSION["USERNAME"]."'
			WHERE cliente = ".$_POST["cl"];
		$res = $db->query($sql);
	} else {
		$sql = "INSERT INTO coordenadas (cliente, latitud, longitud, zoom, fecha, aplicado_x) 
		VALUES (".$_POST["cl"].", '".$_POST["la"]."', '".$_POST["lo"]."', ".$_POST["zo"].", '".date("Y-m-d H:i:s")."', '".$_SESSION["USERNAME"]."')";
		$res = $db->query($sql);
	}
} else {
	$cliente = $_GET["c"];
}
$sql = "SELECT direccion FROM clientes WHERE id = $cliente LIMIT 1";
$res = $db->query($sql);
$cli = $db->fetchNextObject($res);
$direccion = $cli->direccion;

$sql = "SELECT * FROM coordenadas WHERE cliente = $cliente LIMIT 1";
$res = $db->query($sql);
if( $db->numRows($res)>0 ){
	$cor = $db->fetchNextObject($res);
	$lat = $cor->latitud;
	$lon = $cor->longitud;
	$zoo = $cor->zoom;
	$msgcor = 'La geolocalización ya ha sido implementada, los datos aun pueden ser actualizadas.';
	$cordboton = "coordactualizar";
} else {
	$lat = '29.088393678978534';
	$lon = '-110.9615478515625';
	$zoo = 12;
	$msgcor = 'De click en buscar y después reajuste la etiqueta a la posición correcta en el mapa';
	$cordboton = "coordguardar";
}

?>

<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en-US" prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en-US" prefix="og: http://ogp.me/ns#"> <!--<![endif]-->
<head>

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />



  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.js"></script>
      <script src="/js/respond.min.js"></script>
    <![endif]-->

<link rel="stylesheet" href="../../estilo/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="../../estilo/global.css" type="text/css" media="all" />
<link rel="stylesheet" href="../../estilo/alerts.css" type="text/css" media="all" />
<link rel="stylesheet" href="../../estilo/themes/base/ui.all.css" type="text/css" media="all" />

<script type='text/javascript' src='../../js/jquery-1.4.1.min.js'></script>
</head>

<body class="single single-post postid-3081 single-format-standard" style="background-color:#dddddd;">

<form action="" method="post">
<table class="formato" style="width:100%">
<tbody>
	<tr>
		<td>
			<?=$msgcor;?>
		</td>
	</tr>
	<tr>
		<td>
			<fieldset class="gllpLatlonPicker">
			<input type="text" class="gllpSearchField" value="Hermosillo, Son., Mexico, <?=$direccion;?>" size=50 readonly />
			<input type="button" class="gllpSearchButton" value="Buscar">
			<input type="hidden" value="<?=$cliente;?>" name="cl" />
			<div class="gllpMap">Google Maps</div>
				lat/lon:	<input type="text" name="la" class="gllpLatitude" value="<?=$lat;?>" size="10" readonly /> 
				/ 		<input type="text" name="lo" class="gllpLongitude" value="<?=$lon;?>" size="10" readonly />, 
				zoom: 		<input type="text" name="zo" class="gllpZoom" value="<?=$zoo;?>" size="3" readonly /> 
				<input type="submit" name="guardarmapa" value="Guardar" />
				<!-- <input type="button" class="gllpUpdateButton" value="Actualizar mapa"> -->
			</fieldset>
		</td>
	</tr>
</tbody>
</table>
</form>

<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="http://www.wimagguc.com/projects/jquery-latitude-longitude-picker-gmaps/js/jquery-gmaps-latlon-picker.js"></script>

<style>
.gllpMap { max-width: 95%; height: 330px; margin: 0; padding: 0; }
.gllpLatlonPicker { border: none; margin: 0; padding: 0; }
.gllpLatlonPicker input { width: auto; }
.gllpLatlonPicker P { margin: 0; padding: 0; }
.code { margin: 20px 0; font-size: 0.9em; width: 100%; font-family: "Monofur", courier; background-color: #555; padding: 15px; box-shadow: #f6f6f6 1px 1px 3px; color: #999; }
</style>

</body>
</html>
