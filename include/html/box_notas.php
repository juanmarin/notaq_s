<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<style>
	#notas{margin:-5px;}
</style>
<div id="notas" class="sombra">
<div id="n_title">Notas de cliente</div>
<ol>
	<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT * FROM notas WHERE cliente = ".$_GET["cl"];
	$res = $db->query($sql);
	if($db->numRows() > 0){
		while($nt = $db->fetchNextObject($res)){
			echo '<li>'.$nt->nota.'</li>';
		}
	}else{
		echo 'No hay notas para este cliente.';
	}
	?>
</ol>
</div>