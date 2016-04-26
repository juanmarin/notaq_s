<?php
	require_once("include/php/fun_global.php");
	
		$cantidad = 1000;
		$monto1 = 150; 
		$plazo1 = 6;
		$plazo2 = 5;
		$monto2 = 100;
		$tipo_pago = "SEMANAL";
		$hoy = date("Y-m-d");
		
	$datosPrestamo = calculamonto($cantidad, $monto1, $monto2, $plazo1, $plazo2, $tipo_pago);
	
		echo "Prestamo: ".$datosPrestamo['saldo']."<br/>";
		echo "Monto Int.: ".$datosPrestamo['int_moneda']."<br/>";
		echo "Interes Tot: ".$datosPrestamo['interes']."<br/>";
		echo "Plazo Real: ".$datosPrestamo['tiempo']."<br/>";

		echo var_dump($datosPrestamo)."</br>";
		//$date = "0000-00-00";
		//echo date("d-m-Y", strtotime($date))."</br>";
		echo date("Y-m-d h:m")."</br>";
		
		$hoy = getdate();
		print_r($hoy);
		
		
?>
