<?php

if(isset($_GET["c"]))
{
	header("Content-Type: image/jpeg");
	require_once "../conf/Config.php";
	$sql = "SELECT foto FROM clientefoto WHERE idcliente = ".$_GET["c"]." AND detalle = '".$_GET["d"]."'";
	//echo $sql;
	$res = mysql_query($sql);
	if(mysql_num_rows($res)>0)
	{
		$img = mysql_fetch_array($res);
		echo $img["foto"];
	} else {
		require_once("../../img/Imagen_no_disponible_infobox.gif");
	}
}

?>
