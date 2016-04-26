<?php
@session_start();
if(isset($_SESSION["REQUIRED1"])){
	$_SESSION["EDITARCUENTA"] = $_POST["editarCuenta"];
}
?>
