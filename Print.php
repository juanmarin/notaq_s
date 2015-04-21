<?php
header('Content-Type: text/plain; charset=iso-8859-1');
$handle=printer_open("Generic / Text Only");

printer_start_doc($handle, "Recibo");
printer_start_page($handle);
$font = printer_create_font("Consolas",40,23,400,false,false,false,0);
printer_select_font($handle, $font);

printer_draw_text($handle,"-------------------------------",0,0);
printer_draw_text($handle,"::      RECIBO DE PAGO       ::",0,100);
printer_draw_text($handle,"Fecha: ".date("Y-m-d").", ".date("H:i:s"),0,300);
printer_draw_text($handle,"Recibí de:",0,400);
printer_draw_text($handle,"Jesús Alfonso Nuñez Valdez",0,500);
printer_draw_text($handle,"La cantidad de: $800.00",0,600);
printer_draw_text($handle,"Concepto: ABONO (1/9)",0,700);
printer_draw_text($handle,"Tipo de pago: LUNES",0,800);
printer_draw_text($handle,"Fecha de pago: 2011-06-13",0,900);
printer_draw_text($handle,"Próximo pago: 2011-06-20",0,1000);
printer_draw_text($handle,"Saldo anterior: $3,600.00",0,1100);
printer_draw_text($handle,"Saldo actual: $2,800.00",0,1200);
printer_draw_text($handle,"Ref: 1-6-1-0",50,1300);
printer_draw_text($handle,"¡GRACIAS POR SU PREFERENCIA!",0,1500);
printer_draw_text($handle," ",0,1900);

printer_delete_font($font);
printer_end_page($handle);
printer_end_doc($handle);
printer_abort($handle);
printer_close($handle);
?>