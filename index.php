<?php
/*
//INICIALIZANDO VARIABLES -------------------------------
//define("IN_SITE", true);
*/
$sitio_nombre_raiz = "public_html";
$tipo_login = 1;
$_GET["m"] = ( isset($_GET["m"]) )? $_GET["m"] : "1";
$msg = '<p class="suggestion">Recuerde coincidir en mayúsculas.</p>';
if ($tipo_login == 3){
	session_start();
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	require_once "include/php/sys_panel.php";
} else {
	require_once 'include/php/sys_access.class.php';
	$user = new flexibleAccess();
	if ( isset($_GET['logout']) && $_GET['logout'] == '1' ){
		##echo '<script>alert("Checkpoint");</script>';
		$user->logout('http://'.$_SERVER['HTTP_HOST'].'/'. $sitio_nombre_raiz .'/');
		session_destroy();
	}
	if ( !$user->is_loaded() ){
		//Login stuff:
		if ( isset($_POST['uname']) && isset($_POST['pwd'])){
			$log_remember = (isset($_POST['remember']))?$_POST['remember']:0;
			if ( !$user->login($_POST['uname'],$_POST['pwd'],$log_remember )){
				//Mention that we don't have to use addslashes as the class do the job
				//echo 'Wrong username and/or password';
				$loginmsg = '<p class="error">Usuario y/o contraseña incorrecto.</p>';
				if ( $tipo_login == 1 ){
					$msg = '<p class="error">Usuario y/o contraseña incorrectos.</p>';
					echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
					require_once "include/html/pg_sys_login.php";
				} else {
					//user is now loaded
					$_SESSION["hola"] = 0;
					require_once "include/php/sys_panel.php";
				}
			} else {
				//user is now loaded
				$_SESSION["hola"] = 0;
				require_once "include/php/sys_panel.php";
			}
		}elseif( $tipo_login == 1 ){
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			require_once "include/html/pg_sys_login.php";
		} else {
			//user is now loaded
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			require_once "include/php/sys_panel.php";
		}
	} else {
		//User is loaded
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		require_once "include/php/sys_panel.php";
	}
}
?>
