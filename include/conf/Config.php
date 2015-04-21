<?php
	if ($_SERVER["HTTP_HOST"] == 'localhost'){
		define('DB_HOST', 'localhost');
		define('DB_USER', 'root');
		define('DB_PASSWORD', 'root');
		define('DB_DATABASE', 'notaq');
	} else {
		define('DB_HOST', 'localhost');
		define('DB_USER', 'root');
		define('DB_PASSWORD', 'root');
		define('DB_DATABASE', 'notaq');	
	}
	$con = mysql_connect(DB_HOST, DB_USER , DB_PASSWORD);
	if (!$con){
		die('Error en la conexi&oacute;n: ' . mysql_error());
	}
	mysql_select_db(DB_DATABASE, $con);
?>
