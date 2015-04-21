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
?>
