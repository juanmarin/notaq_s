<?php
@session_start();
if(isset($_SESSION["REQUIRED1"])){
	unset($_SESSION["EDITARCUENTA"]);
}
?>
