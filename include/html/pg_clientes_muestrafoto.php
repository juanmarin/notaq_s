<?php

if(isset($_GET["imagen"]))
{
	header("Content-Type: image/jpeg");
	require_once "../conf/Config.php";
	$sql = "SELECT foto FROM clientefoto WHERE idcliente = ".$_GET["imagen"]." AND detalle = '".$_GET["d"]."'";
	$res = mysql_query($sql);
	$img = mysql_fetch_array($res);
	echo $img["foto"];
}

?>
