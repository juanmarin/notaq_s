<?php

if(isset($_GET["c"]))
{
	header("Content-Type: image/jpeg");
	require_once "../conf/Config.php";
	$sql = "SELECT imagen FROM clienteine WHERE cliente = ".$_GET["c"]." AND lado = '".$_GET["l"]."'";
	$res = mysql_query($sql);
	if(mysql_num_rows($res)>0)
	{
		$img = mysql_fetch_array($res);
		echo $img["imagen"];
	} else {
		require_once("../../img/Imagen_no_disponible_infobox.gif");
	}
}

?>
