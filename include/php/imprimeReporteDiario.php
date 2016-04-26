<?php
## IMPRESION DE RECIBO *********************************************************************************** 
$handle=printer_open("Recibo");
printer_start_doc($handle, "Recibo");
printer_start_page($handle);
$font = printer_create_font("Verdana",30,9,400,false,false,false,0);
printer_select_font($handle, $font);
$alto = 100;
$li = $alto;

$res = $db->query($sql);
#
printer_draw_text($handle,"-------------------------------",0,0);
printer_draw_text($handle,"::       REPORTE DIARIO       ::",0,$li);
$li+=$alto;		printer_draw_text($handle,'Fecha: '.getFecha($fecha, 0), 0,$li);
$li+=$alto;
while($r = $db->fetchNextObject($res)) {
	$cnombre = $r->nombre . " " . $r->apellidom . " " . $r->apellidop;
	$interes = getInteresPago($r->pago_real, $r->interes);
	$pago = $r->pago_real - $interes;
	$sum_pagos += $pago;
	$sum_inter += $interes;
	#
	$li+=$alto;		printer_draw_text($handle,$cnombre, 0,$li);
	$li+=$alto*2;	printer_draw_text($handle,'Abono: ' . moneda($pago, 0), 0,$li);
	$li+=$alto;		printer_draw_text($handle,'Interes: ' . moneda($interes, 0), 0,$li);
	$li+=$alto;		printer_draw_text($handle,'Total: ' . moneda($interes+$pago, 0), 0,$li);
	if($r->interes == 0){
		$li+=$alto;	printer_draw_text($handle,'Recibo: Pago de interes', 0,$li);
	}else{
		$li+=$alto;	printer_draw_text($handle,'Recibo: ' . getNpago($r->cid, $r->pid) . ' de ' . $r->npagos, 0,$li);
	}
	$li+=$alto;		printer_draw_text($handle,'DP: ' . getDiaSemana_($r->dias_pago, $r->tipo_pago), 0,$li);
	$li+=$alto;		printer_draw_text($handle,"-------------------------------",0,$li);
	$li+=$alto;
}
/*
#[[ IMPRIMIENDO TOTALES ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
$li+=$alto*2;     printer_draw_text($handle,'-', 0,$li);
$li+=$alto;		printer_draw_text($handle,'Subtotal: ' . moneda($sum_pagos, 0), 0,$li);
$li+=$alto;		printer_draw_text($handle,'Interes: ' . moneda($sum_inter, 0), 0,$li);
$li+=$alto;		printer_draw_text($handle,'Total: ' . moneda($sum_pagos + $sum_inter, 0), 0,$li);
*/

printer_draw_text($handle," ",0,$li);
printer_delete_font($font);
printer_end_page($handle);
printer_end_doc($handle);
printer_abort($handle);
printer_close($handle);
/*
while($r = $db->fetchNextObject($res)) {
	$cnombre = $r->nombre . " " . $r->apellidom . " " . $r->apellidop;
	$interes = getInteresPago($r->pago_real, $r->interes);
	$pago = $r->pago_real - $interes;
	$sum_pagos += $pago;
	$sum_inter += $interes;
	#
	echo $cnombre . '<br />';
	echo 'Abono: ' . moneda($pago, 0) . '<br />';
	echo 'Interes: ' . moneda($interes, 0) . '<br />';
	echo 'Total: ' . moneda($interes+$pago, 0) . '<br />';
	if($r->interes == 0){
		echo 'Recibo: Pago de interes' . '<br />';
	}else{
		echo 'Recibo: ' . getNpago($r->cid, $r->pid) . ' de ' . $r->npagos . '<br />';
	}
	echo 'DP: ' . getDiaSemana_($r->dias_pago, $r->tipo_pago) . '<br />';
	echo $handle,"-------------------------------<br />";
}
*/
?>
