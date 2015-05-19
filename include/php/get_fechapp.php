<?php
@session_start();
if(isset($_SESSION["REQUIRED1"])){
	## iniciar variables
	$tp = $_POST["tp"];
	$fpp = $_POST["fecha"];
	$dp = split("-", $_POST["dp"]);
	
	$a = substr($fpp, 0, -6);
	$dia = substr($fpp, -2);
	$mes = substr($fpp, 5, -3);
	## calcular la fecha del primer pago 
	if($tp == 3){
		if(((int)$dia == $dp[0]) || ((int)$dia == $dp[1])){
			echo $fpp;
		}
	}elseif($tp == 4) {
		if( (int)$dia == $dp[0]){
			echo $fpp;
		}else {
			if($dp[0] < 10){
				$d = "0".$dp[0];
			}else {
				$d = $dp[0];
			}
			echo $a . "-" . $mes . "-" . $d;
		}
	}else {
		echo $fpp;
	}
}
?>
