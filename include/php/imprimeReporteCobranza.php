<?php
# SQL QUERRY COMOPROBANDO REGISTROS ******************************************************************++
$res = $db->query($sql);
if($db->numRows() == 0){
	?>
	<script type="text/javascript" >
		alert("No hay registros para imprimir.");
	</script>
	<?php
} else {

	# INICIANDO IMPRESION ********************************************************************************++
	$handle=printer_open("Recibo");
	$font = printer_create_font("Verdana",30,9,400,false,false,false,0);
printer_select_font($handle, $font);
$alto = 100;
	$li = $alto;
	printer_start_doc($handle, "Recibo");
	printer_start_page($handle);
	printer_select_font($handle, $font);
	printer_draw_text($handle,"-------------------------------",0,0);
	printer_draw_text($handle,"::     CLIENTES COBRANZA      ::",0,$li);
	$li+=$alto;

	#-LISTADO DE CLIENTES **********************************************************************************
	while($r = $db->fetchNextObject($res)) {
		$ncliente = $r->nombre." ".$r->apellidom." ".$r->apellidop;
		#
		$li+=$alto;		printer_draw_text($handle,"Fecha: ".getFecha($r->fecha, 0),0,$li);
		$li+=$alto;		printer_draw_text($handle,$ncliente,0,$li);
		$li+=$alto;		printer_draw_text($handle,"Dir: ".$r->direccion,0,$li);
		$li+=$alto;		printer_draw_text($handle,"Col: ".$r->colonia,0,$li);
		$li+=$alto;		printer_draw_text($handle,"Tel: ".$r->telefono,0,$li);
		$li+=$alto;		printer_draw_text($handle,"Monto: $".moneda(getPagoRedondo($r->pago), 0),0,$li);
		$li+=$alto;		printer_draw_text($handle,"Num. de pago: ".getNumPago($r->cuenta, $r->idp) . " de " . $r->npagos,0,$li);
		$li+=$alto;		printer_draw_text($handle,"DP: ".getDiaSemana_($r->dias_pago, $r->tipo_pago),0,$li);
		$li+=$alto;		printer_draw_text($handle,"Saldo: $".moneda(getCSaldo($r->cuenta), 0),0,$li);
		$li+=$alto;		printer_draw_text($handle,"-------------------------------",0,$li);
		$li+=$alto*3;
		/*
		$li = '<br />';
		echo "Fecha: ".getFecha($r->fecha, 0).$li;
		echo $ncliente.$li;
		echo "Dir: ".$r->direccion.$li;
		echo "Col: ".$r->colonia.$li;
		echo "Tel: ".$r->telefono.$li;
		echo "Monto: $".moneda($r->pago, 0).$li;
		echo "Num. de pago: ".getNPago($r->cuenta, $r->idp).$li;
		echo "Saldo: $".moneda(getCSaldo($r->cuenta), 0).$li;
		echo "-------------------------------".$li;
		*/
	}

	#-FINALIZANDO IMPRESION ********************************************************************************
	printer_delete_font($font);
	printer_end_page($handle);
	printer_end_doc($handle);
	printer_abort($handle);
	printer_close($handle);
}
?>
