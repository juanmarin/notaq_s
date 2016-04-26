<?php
function moneda2($num){
	return number_format($num, 2, ".", ",");
}
function getDiaSemana2($dia, $tipo){
	if($tipo < 3){
		switch($dia) {
			case 1:	$diasemana =  "LUNES";			break;
			case 2:	$diasemana =  "MARTES";			break;
			case 3:	$diasemana =  "MIERCOLES";		break;
			case 4:	$diasemana =  "JUEVES";			break;
			case 5:	$diasemana =  "VIERNES";		break;
			case 6:	$diasemana =  "SABADO";			break;
			default:	$diasemana =  "NO DEFINIDO";
		}
	}elseif($tipo==3) {
		switch($dia) {
			case "1-16"	:	$diasemana =  "DIAS 1 Y 16 DE CADA MES";		break;
			case "2-17"	:	$diasemana =  "DIAS 2 Y 17 DE CADA MES";		break;
			case "2-16"	:	$diasemana =  "DIAS 16 Y 2 DE CADA MES";		break;
			case "8-22"	:	$diasemana =  "DIAS 8 Y 22 DE CADA MES";		break;
			case "15-30":	$diasemana =  "DIAS 15 Y 30 DE CADA MES";		break;
			case "1-15"	:	$diasemana =  "DIAS 1 Y 15 DE CADA MES";		break;
			default:	$diasemana =  "NO DEFINIDO";
		}
	}else {
		switch($dia) {
			case "1"	:	$diasemana =  "DIA 1 DE CADA MES";	break;
			case "16":	$diasemana =  "DIA 16 CADA MES";		break;
			default:	$diasemana =  "NO DEFINIDO";
		}	
	}
	return $diasemana;
}
## CALCULANDO DATOS 
if($_POST["tipo_pago"] == 1) {
	$tp = "SEMANAL";
}elseif($_POST["tipo_pago"] == 2) {
	$tp = "CATORCENA";
}elseif($_POST["tipo_pago"] == 3) {
	$tp = "QUINCENAL";
}else {
	$tp = "MENSUAL";
}

## INFORMACIÓN DE LA CUENTA ****************************************************************************** 
$sql = "SELECT * FROM cuentas WHERE id = ".$cuenta;
$res = mysql_query($sql);
$c = mysql_fetch_array($res);
## INFORMACIÓN DEL CLIENTE ******************************************************************************* 
$sql = "SELECT * FROM clientes WHERE id = ".$c["cliente"];
$res = mysql_query($sql);
$cl = mysql_fetch_array($res);
## IMPRESION DE RECIBO *********************************************************************************** 
$handle=printer_open("Recibo");
printer_start_doc($handle, "Recibo");
printer_start_page($handle);
$font = printer_create_font("Verdana",30,9,400,false,false,false,0);
printer_select_font($handle, $font);
$alto = 100;
$li = $alto;
printer_draw_text($handle,"-------------------------------",0,0);
printer_draw_text($handle,"::      NUEVO CREDITO       ::",0,$li);
$li+=$alto;
printer_draw_text($handle,"Fecha: ".date("Y-m-d").", ".date("H:i:s"),0,$li);
$li+=$alto;
printer_draw_text($handle,"Cliente:",0,$li);
$li+=$alto;
printer_draw_text($handle,$cl["nombre"] . " " . $cl["apellidop"] . " " . $cl["apellidom"],0,$li);
$li+=$alto;
printer_draw_text($handle,"Concepto: PRESTAMO EN EFECTIVO",0,$li);
$li+=$alto;
printer_draw_text($handle,"Cantidad: $ ".moneda2($_POST["cantidad"]),0,$li);
$li+=$alto;
printer_draw_text($handle,"Plazo: ".$_POST["tiempo"]." MESES",0,$li);
$li+=$alto;
printer_draw_text($handle,"Total con interes: ".moneda2($total),0,$li);
$li+=$alto;
printer_draw_text($handle,$npagos." pagos de $".getPagoRedondo($pago),0,$li);
$li+=$alto;
printer_draw_text($handle,"Tipo de pago: ".$tp,0,$li);
$li+=$alto;
printer_draw_text($handle,"Dias de pago: ".getDiaSemana2($_POST["dias_pago"], $_POST["tipo_pago"]),0,$li);
$li+=$alto;
printer_draw_text($handle,"Próximo pago: ".$_POST["fechapp"],0,$li);
$li+=$alto;
printer_draw_text($handle,"---",0,$li);
$li+=$alto;
printer_draw_text($handle,"OBSERVACIONES: ".$_POST["observ"],0,$li);
$li+=$alto;
printer_draw_text($handle,"¡GRACIAS POR SU PREFERENCIA!",0,$li);
$li+=$alto*3;
printer_draw_text($handle," ",0,$li);
printer_delete_font($font);
printer_end_page($handle);
printer_end_doc($handle);
printer_abort($handle);
printer_close($handle);
?>
