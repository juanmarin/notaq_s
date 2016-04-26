<?php
function getFecha1($fecha, $show = 1){
		#OBTENER FECHA DESMEMBRADA 
		$diasem = date("w", strtotime($fecha));
		$d = substr($fecha, -2);
		$m = (int)substr($fecha, 5, -3);
		$a = substr($fecha, 0, -6);
		#DEFINIR CADENAS 
		$dias = array("DOM", "LUN", "MAR", "MIE", "JUE", "VIE", "SAB");
		$meses = array("X", "ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC");
		if($show == 1){
			echo $dias[$diasem] . ", " . $d . " DE " . $meses[$m] . " DEL " . $a;
		}else {
			return $dias[$diasem] . ", " . $d . " DE " . $meses[$m] . " DEL " . $a;
		}
	}
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
$cta = 
$sql = "SELECT * FROM cuentas WHERE id = ".$cuenta;
$res = mysql_query($sql);
$c = mysql_fetch_array($res);
$npagos = $c["npagos"];
$interes = $c["interes"];
$int = (($interes / 100)+1);

## INFORMACIÓN DEL CLIENTE ******************************************************************************* 
$sql = "SELECT * FROM clientes WHERE id = ".$cliente;
$res = mysql_query($sql);
$cli = mysql_fetch_array($res);
## INFORMACIÓN DEL CLIENTE ******************************************************************************* 
$sql = "SELECT fecha FROM pagos WHERE id = ".($pid+1);
$res = mysql_query($sql);
$p = mysql_fetch_array($res);
$pp = getFecha($p["fecha"], 0);
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
printer_draw_text($handle,"::   ----------------------------   ::",0,$li);
$li+=$alto;
printer_draw_text($handle,"::   RECIBO GENERADO POR RECARGOS  ::",0,$li);
$li+=$alto;
printer_draw_text($handle,"Fecha: ".date("Y-m-d").", ".date("H:i:s"),0,$li);
$li+=$alto;
printer_draw_text($handle,"Recibí de:",0,$li);
$li+=$alto;
printer_draw_text($handle,$cli["nombre"]." ".$cli["apellidop"]." ".$cli["apellidom"],0,$li);
$li+=$alto;
printer_draw_text($handle,"La cantidad de: $".$recargos,0,$li);
$li+=$alto;
printer_draw_text($handle,"Ref: ".$c["cliente"]."-".$cuenta,0,$li);
$li+=$alto;
printer_draw_text($handle,"   ¡GRACIAS POR SU PREFERENCIA!",0,$li);
$li+=$alto;
printer_draw_text($handle,"**********************************",0,$li);
$li+=$alto*2;
printer_draw_text($handle,"::         RECARGO PAGADO       ::",0,$li);
$li+=$alto*2;
printer_draw_text($handle,"**********************************",0,$li);
$li+=$alto*3;
printer_draw_text($handle," ",0,$li);

printer_delete_font($font);
printer_end_page($handle);
printer_end_doc($handle);
printer_abort($handle);
printer_close($handle);
?>
