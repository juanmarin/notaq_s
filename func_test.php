<?php
	require_once("include/php/fun_global.php");
	
		$cantidad = 1000;
		$monto1 = 150; 
		$plazo1 = 6;
		$plazo2 = 5;
		$monto2 = 100;
		$tipo_pago = "SEMANAL";
		
	$datosPrestamo = calculamonto($cantidad, $monto1, $monto2, $plazo1, $plazo2, $tipo_pago);
	
		echo "Prestamo: ".$datosPrestamo['saldo']."<br/>";
		echo "Monto Int.: ".$datosPrestamo['int_moneda']."<br/>";
		echo "Interes Tot: ".$datosPrestamo['interes']."<br/>";
		echo "Plazo Real: ".$datosPrestamo['tiempo']."<br/>";

		echo var_dump($datosPrestamo);
		$date = "2013-04-22";
		echo "Fecha: ".date("d-m-Y", strtotime($date));
	
		
?>