<?php 
## IMPRESION DE RECIBO *********************************************************************************** 
$handle=printer_open("Recibo");
printer_start_doc($handle, "Recibo");
printer_start_page($handle);
$font = printer_create_font("Verdana",30,9,400,false,false,false,0);
printer_select_font($handle, $font);
$alto = 100;;
$li = $alto;
$res = $db->query($sql);
#
printer_draw_text($handle,"-------------------------------",0,0);
printer_draw_text($handle,"::   CLIENTES QUE FALTARON   ::",0,$li);
$li+=$alto;		printer_draw_text($handle,'Fecha: '.getFecha($fecha, 0), 0,$li);
$li+=$alto;
while($r = $db->fetchNextObject($res)) {
	$cnombre = $r->nombre . " " . $r->apellidop . " " . $r->apellidom;
	$interes = getInteresPago($r->pago_real, $r->interes);
	$pago = $r->pago_real - $interes;
	$sum_pagos += $pago;
	$sum_inter += $interes;
	#
	$li+=$alto*2;	printer_draw_text($handle,'Fecha: ' . $r->fecha, 0,$li);
	$li+=$alto;		printer_draw_text($handle,'Nombre: ' . $cnombre, 0,$li);
	$li+=$alto;		printer_draw_text($handle,'Monto: $' . moneda($r->pago, 0), 0,$li);
	$li+=$alto;		printer_draw_text($handle,'Recibo: ' . getNpago($r->cid, $r->pid) . ' de ' . $r->npagos, 0,$li);
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
	$cnombre = $r->nombre . " " . $r->apellidop . " " . $r->apellidom;
	$interes = getInteresPago($r->pago_real, $r->interes);
	$pago = $r->pago_real - $interes;
	$sum_pagos += $pago;
	$sum_inter += $interes;
	#
	echo 'Fecha: ' . $r->fecha . '<br />';
	echo 'Nombre: ' . $cnombre . '<br />';
	echo 'Monto: $' . moneda($r->pago, 0) . '<br />';
	echo 'Recibo: ' . getNpago($r->cid, $r->pid) . ' de ' . $r->npagos . '<br />';
	echo 'DP: ' . getDiaSemana_($r->dias_pago, $r->tipo_pago) . '<br />';
	echo "-------------------------------<br />";
}
*/
?>
