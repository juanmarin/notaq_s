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
			case "2-16"	:	$diasemana =  "DIAS 2 Y 16 DE CADA MES";		break;
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
## INFORMACIÓN DE LA CUENTA ****************************************************************************** 
$sql = "SELECT * FROM cuentas WHERE id = ".$cuenta;
$res = mysql_query($sql);
$c = mysql_fetch_array($res);
$npagos = $c["npagos"];
$totCant = $c["total"];
$interes = $c["interes"];
$int = (($interes / 100)+1);

## INFORMACIÓN DEL CLIENTE ******************************************************************************* 
$sql = "SELECT * FROM clientes WHERE id = ".$c["cliente"];
$res = mysql_query($sql);
$cli = mysql_fetch_array($res);
## INFORMACIÓN DEL CLIENTE ******************************************************************************* 
$sql = "SELECT fecha FROM pagos WHERE id = ".($pid);
//echo $sql;
$res = mysql_query($sql);
$p = mysql_fetch_array($res);
$pp = $p["fecha"];
## calculando ingfo
if($c["tipo_pago"] == 1) {
	$tp = "SEMANAL";
}elseif($c["tipo_pago"] == 2) {
	$tp = "CATORCENA";
}elseif($c["tipo_pago"] == 3) {
	$tp = "QUINCENAL";
}else {
	$tp = "MENSUAL";
}
## IMPRESION DE RECIBO *********************************************************************************** 
$handle=printer_open("Recibo");

printer_start_doc($handle, "Recibo");
printer_start_page($handle);
$font = printer_create_font("Verdana",30,9,400,false,false,false,0);
printer_select_font($handle, $font);
$alto = 100;
$li = $alto;
printer_draw_text($handle,"-------------------------------",0,0);
printer_draw_text($handle,"::      RECIBO DE PAGO       ::",0,$li);
$li+=$alto;
printer_draw_text($handle,"Fecha: ".date("Y-m-d").", ".date("H:i:s"),0,$li);
$li+=$alto;
printer_draw_text($handle,"Recibí de:",0,$li);
$li+=$alto;
printer_draw_text($handle,$cli["nombre"]." ".$cli["apellidop"]." ".$cli["apellidom"],0,$li);
$li+=$alto;
printer_draw_text($handle,"La cantidad de: $".moneda2($_POST["cant"]),0,$li);
$li+=$alto;
printer_draw_text($handle,"Concepto: PAGO INTERES (".$numpago."/".$npagos.")",0,$li);
$li+=$alto;
printer_draw_text($handle,"Tipo de pago: ".$tp,0,$li);
$li+=$alto;
printer_draw_text($handle,"Proximo pago: ".$pp,0,$li);
$li+=$alto;
printer_draw_text($handle,"Saldo anterior: $".moneda2($totCant),0,$li);
$li+=$alto;
printer_draw_text($handle,"Saldo actual: $".moneda2($totCant - $_POST["pago"]),0,$li);
$li+=$alto;
printer_draw_text($handle,"Ref: ".$c["cliente"]."-".$cuenta."-".$npagos,0,$li);
$li+=$alto;
printer_draw_text($handle,"¡GRACIAS POR SU PREFERENCIA!",0,$li);
$li+=$alto;
printer_draw_text($handle,"******************************",0,$li);
$li+=$alto*2;
printer_draw_text($handle,"::       RECIBO PAGADO      ::",0,$li);
$li+=$alto*2;
printer_draw_text($handle,"******************************",0,$li);
$li+=$alto*3;
printer_draw_text($handle," ",0,$li);

printer_delete_font($font);
printer_end_page($handle);
printer_end_doc($handle);
printer_abort($handle);
printer_close($handle);

?>
