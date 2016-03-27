<?php
/*******************************************
** FUNCIONES GLOBALES
********************************************/
	function showPost($post){
		foreach ($post as $var => $val) {
			echo $var . " => " . $val . "<br />";
		}
	}
	
	function arregloForms($arreglo){
		$_info = split("&", $arreglo);
		for($i=0; $i<count($_info); $i++){
			$tmp = split("=", $_info[$i]);
			$_data[$tmp[0]] = str_replace("+", " ", $tmp[1]);
		}
		return $_data;
	}
	
	function galeria($cant = 0, $cols = 0, $titulos = ""){
		$cols = ($cols == 0)? $cant : $cols;
		if ($cols > 2){
			$w = ( $cols * 200 ) - 10;
			$h = ( ceil( $cant / $cols ) * 200 ) - 10;
		} elseif ($cols < 3){
			$w = ( $cols * 200 ) - 5;
			$h = ( ceil( $cant / $cols ) * 200 ) - 5;
		}
		if ($cols > $cant) {
			$w = ( $cant * 200 ) - 5;
		}
		$galeria_estilo = ' style="width:'. $w .'px; height:'. $h .'px;"';
		if ($cant > 0){
			$dibuja = '<div class="galeria" '. $galeria_estilo .'>';
			for ($i=1;$i<=$cant;$i++){
				$dibuja .='		<a href="images/galeria/Imagen'. $i .'.jpg" rel="sexylightbox[group1]" title="Trabajos">
  									<img src="images/galeria/thumbs/Imagen'. $i .'.jpg" alt=""/>
									</a>';
			}
			$dibuja .= '</div>';
		} else {
			$dibuja = "";
		}
		echo $dibuja;
	}
	//------------------------------------
	function getCadena($campo, $tabla, $donde, $es){
		require_once("../php/sys_db.class.php");
		require_once("../conf/Config_con.php");
		$sql = "SELECT ".$campo." FROM ".$tabla." WHERE ".$donde." = ".$es;
		$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
		$res = $db->query($sql);
		$ln = $db->fetchNextObject($res);
		return $ln->$campo;
	}
	function getCuenta($monto, $interes, $tiempo, $modo_tiempo, $modo_pago){
		# CALCULAR INTERESES
		switch($modo_tiempo){
			case 1:	if($tiempo > 4){
						$interes *= ($tiempo / 4);
					}
					break;
			case 2: if($tiempo > 2){
						$interes *= ($tiempo / 2);
					}
					break;
			case 3:	$interes *= $tiempo;
		}
		# CALCULAR NUMERO DE PAGOS 
		if($modo_tiempo == $modo_pago){
			$n_pagos = $tiempo;
		}else{
			if(($modo_tiempo == 1) && ($modo_pago == 2)){		## 	SEMANAS VS QUINCENAS 
				$n_pagos = floor($tiempo / 2);
				if(($tiempo % 2) > 0){$n_pagos++;}
			}elseif(($modo_tiempo == 1) && ($modo_pago == 3)){	##	SEMANAS	VS MESES
				$n_pagos = floor($tiempo / 4);
				if(($tiempo % 4) > 0){$n_pagos++;}
			}elseif(($modo_tiempo == 2) && ($modo_pago == 1)){	##	QUINCENAS VS SEMANAS
				$n_pagos = $tiempo * 2;
			}elseif(($modo_tiempo == 2) && ($modo_pago == 3)){	##	QUINCENAS VS MESES
				$n_pagos = floor($tiempo / 2);
				if(($tiempo % 2) > 0){$n_pagos++;}
			}elseif(($modo_tiempo == 3) && ($modo_pago == 1)){	##	MESES VS SEMANAS
				$n_pagos = $tiempo * 4;
			}elseif(($modo_tiempo == 3) && ($modo_pago == 2)){	##	MESES VS QUINCENAS
				$n_pagos = $tiempo * 2;
			}
		}
		# CALCULAR MONTO DE PAGO
		$pago = $monto / $n_pagos;
		# CALCULAR INTERES POR ABONO
		$pago_int = (($interes / 100) * $monto) / $n_pagos;
		# ARREGLO A FEVOLVER 
		$cuenta[0] = $pago;
		$cuenta[1] = $pago_int;
		$cuenta[2] = $n_pagos;
		$cuenta[3] = $monto + (($interes / 100) * $monto);
		return $cuenta;
	}
	function getPagos($monto, $tiempo, $tipo){
		switch($tipo)
		{
			case 1:	$pagos = $tiempo * 4;
					break;
			case 2:	$pagos = $tiempo * 2;
					break;
			case 3:	$pagos = $tiempo * 2;
					break;
			case 4:	$pagos = $tiempo;
					break;
		}
		return $pagos;
	}
	function moneda($num, $show = 1){
		if($show == 0){
			return number_format($num, 2, ".", ",");
		}else {
			echo number_format($num, 2, ".", ",");
		}
	}
	
	function getPagoRedondo($pago){
		if($pago < 50){
			$monto = 50;
		}else{
			$monto = (floor($pago / 50) * 50);
			if(($pago % 50) > 0){$monto += 50;}
		}
		return $monto;
	}
	function getDiaSemana($dia, $tipo){
		if($tipo < 3){
			switch($dia) {
				case 1	:		echo "LUNES";			break;
				case 2	:		echo "MARTES";			break;
				case 3	:		echo "MIERCOLES";		break;
				case 4	:		echo "JUEVES";			break;
				case 5	:		echo "VIERNES";			break;
				case 6	:		echo "SABADO";			break;
				case 7	:		echo "DOMINGO";			break;
				default	:		echo "NO DEFINIDO";
			}
		}elseif($tipo==3) {
			switch($dia) {
				case "1-16"	:	echo "DIAS 16 Y 1 DE CADA MES";		break;
				case "2-17"	:	echo "DIAS 17 Y 2 DE CADA MES";		break;
				case "2-16"	:	echo "DIAS 2 Y 16 DE CADA MES";		break;
				case "8-22"	:	echo "DIAS 8 Y 22 DE CADA MES";		break;
				case "15-30"	:	echo "DIAS 15 Y 30 DE CADA MES";	break;
				case "1-15"	:	echo "DIAS 15 Y 1 DE CADA MES";		break;
				case "6-21"	:	echo "DIAS 6 Y 21 DE CADA MES";		break;
				case "3-18"	:	echo "DIAS 3 Y 18 DE CADA MES";		break;
				case "4-18"	:	echo "DIAS 4 Y 18 DE CADA MES";		break;
				default		:	echo "NO DEFINIDO";
			}
		}else {
			switch($dia) {
				case "1"	:	echo "DIA 1 DE CADA MES";		break;
				case "16"	:	echo "DIA 16 CADA MES";			break;
				default		:	echo "NO DEFINIDO";
			}	
		}
	}
	function getDiaSemana_($dia, $tipo){
		if($tipo < 3){
			switch($dia) {
				case 1	:	return "LUNES";					break;
				case 2	:	return "MARTES";				break;
				case 3	:	return "MIERCOLES";				break;
				case 4	:	return "JUEVES";				break;
				case 5	:	return "VIERNES";				break;
				case 6	:	return "SABADO";				break;
				case 7	:	return "DOMINGO";				break;
				default	:	return "NO DEFINIDO";
			}
		}elseif($tipo==3) {
			switch($dia) {
				case "1-16"	:	return "DIAS 16 Y 1 DE CADA MES";	break;
				case "2-17"	:	return "DIAS 17 Y 2 DE CADA MES";	break;
				case "2-16"	:	return "DIAS 2 Y 16 DE CADA MES";	break;
				case "8-22"	:	return "DIAS 8 Y 22 DE CADA MES";	break;
				case "15-30"	:	return "DIAS 15 Y 30 DE CADA MES";	break;
				case "1-15"	:	return "DIAS 15 Y 1 DE CADA MES";	break;
				case "6-21"	:	return "DIAS 6 Y 21 DE CADA MES";	break;
				case "3-18"	:	return "DIAS 3 Y 18 DE CADA MES";	break;
				case "4-18"	:	return "DIAS 4 Y 18 DE CADA MES";	break;
				default:		return "NO DEFINIDO";
			}
		}else {
			switch($dia) {
				case "1"	:	return "DIA 1 DE CADA MES";		break;
				case "16"	:	return "DIA 16 CADA MES";		break;
				default		:	return "NO DEFINIDO";
			}	
		}
	}
	function getInteresPago2($cantidad, $npagos, $interes){
        $pago = ($cantidad/$npagos);
		$int = (($interes * $pago) / 100);
		return $int;
	}
	// Necesito saber cuanto $$ de interes corresponde a cada pago
	// Entonces necesitamos el monto prestado, el total de la deuda ya con intereses
	// Y el numero de pagos
	function getInteresPorPago($d,$c,$p){	
		$intCuenta = $d-$c;
		$intPorPago = ($intCuenta/$p);
		return $intPorPago;
	}
    function getInteresPago($pago, $interes){
		$int = (($interes * $pago) / 100);
		return $int;
	}
    function quitaInteres($interes, $cantidad){
        $int = (($interes / 100)+1);
        $nvoSaldo = ($cantidad / $int);
        $intFinal = ($cantidad - $nvoSaldo);
        return $intFinal; 
     }
	function setMontoRestante($cta, $monto) {
		$sql = "SELECT * FROM cuentas WHERE id = ".$cta;
		#echo $sql . "<br />";
		$res = mysql_query($sql);
		$c = mysql_fetch_array($res);
		$cp = $c["pago"];
		$ct = $c["total"];
		## obtener ultimo registro de pagos
		$sql = "SELECT id, pago FROM pagos WHERE estado = 0 AND cuenta = ".$cta;
		#echo $sql . "<br />";
		$res = mysql_query($sql);
		while($p = mysql_fetch_array($res))
		{
			$pids[]	= $p[0];
			$pagos[] = $p[1];
		}
		$n = count($pagos) - 1;
		#echo $monto . " -- " . $n . "<br />";
		if($monto > $pagos[$n])
		{
			for($i=$n; $i>=0; $i--) {
				echo $monto . " ///--> " . $pagos[$i] . "<br />";
				if($monto > $pagos[$i]){
					echo "mayor -->";
					$sql = "UPDATE pagos SET estado = 3 WHERE id = ". $pids[$i];
					echo $sql . "<br />";
					mysql_query($sql);
					$monto = $monto - $pagos[$i];
				}elseif($monto == $pagos[$i]){
					echo "igual -->";
					$saldo = $pagos[$n] - $monto;
					$sql = "UPDATE pagos SET estado = 3 WHERE id = ". $pids[$i];
					echo $sql . "<br />";
					mysql_query($sql);
					break;
				}else{
					#echo "menor -->";
					$sql = "UPDATE pagos SET pago = ".$saldo." WHERE id = ". $pids[$i];
					echo $sql . "<br />";
					mysql_query($sql);
					break;
				}
				#echo "[" . $i . "]" . $pids[$i] . "=>" . $pagos[$i] . "<br />";
			}
		}else {
			$saldo = $pagos[$n] - $monto;
			if($saldo > 0){
				$sql = "UPDATE pagos SET pago = ".$saldo." WHERE id = ". $pids[$n];
			}else{
				$sql = "UPDATE pagos SET estado = 3 WHERE id = ". $pids[$n];
			}
			#echo $sql . "<br />";
			mysql_query($sql);
		}
	}
	function getHayRecargo($fecha){
		$fecha_actual = strtotime(date("Y-m-d"));  
		$fecha_entrada = strtotime($fecha);  
		if($fecha_actual > $fecha_entrada){  
			return 1;
		}else{  
			return 0;
		}
	}
	function getFechasQuincenas($diasp, $np, $fpp){
		$dp = split("-", $diasp);
		if( (int)$dp > 15 ){
			
		}
		for($i=1; $i<=$np; $i++){
			$fechas = 0;
			$a = substr($fpp, 0, -6);
		}
	}
	function getFecha($fecha, $show = 1){
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
	function recorreFechas($cuenta){
		# PRIMERO OBTENEMOS DATOS COMO CUENTA Y TIPOS DE PAGO 
		# PARA SABER CUANTO TIEMTPO SERAN RECORRIDAS LAS FECHAS
		$sql = "SELECT * FROM cuentas WHERE id = ". $cuenta;
		$res = mysql_query($sql);
		$c = mysql_fetch_array($res);
		$tp = $c["tipo_pago"];
		$dp = split("-", $c["dias_pago"]);
		# CALCULAMOS EL TIPO DE GENERACION DE FECHAS
		$cnt = 0;
		$n = 1;
		if($tp == 1){
			$tipo = "week";
		}elseif($tp == 2) { 
			$tipo = "week";
			$n = 2;
		}elseif($tp == 3) { 
			
		}else {
			$tipo = "month";
		}
		# AHORA SACAMOS LOA PAGOS QUE SERAN ACTUALIZADOS 
		$sql = "SELECT * FROM pagos WHERE cuenta = ".$cuenta." AND estado = 0 ORDER BY id ASC";
		$res = mysql_query($sql);
		while($p = mysql_fetch_array($res)) {
			$prxpago = $p["fecha"];
			if($tp == 3){
				$a = (int)substr($prxpago, 0, -6);
				$m = (int)substr($prxpago, 5, -3);
				$d = (int)substr($prxpago, -2);
				if( $d > 15 ){
					if($m == 12){$m = 1; $a++;}else{ $m++;}
					$d = $dp[0];
				}else{
					if($m == 2 && $dp[1] == 30) {
						$d = 28;
					}else {
						$d = $dp[1];
					}
				}
				if($m < 10){$m = "0".$m;}
				if($d < 10){$d = "0".$d;}
				$prxpago = $a . "-" . $m . "-" . $d;
				#echo $prxpago . " ~ " . $dia[0] . $dia[1] . "<br />";
			}else {
				$prxpago = date('Y-m-d', strtotime($prxpago.' + '.$n.' '.$tipo));
			}
			$sql = "UPDATE pagos SET fecha = '".$prxpago."' WHERE id = ".$p["id"];
			#echo $sql . " ~ " . $tp . "<br />";
			mysql_query($sql);
			return($p["id"]);
		}
	}
	
	function numtoletras($xcifra){////// FUNCION CONVERTIR NUMEROS A LETRAS
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
//
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }
    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }
            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                            
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                            
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                            
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO
        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";
        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";
        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO PESOS $xdecimales/100 M.N.";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN PESO $xdecimales/100 M.N. ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= " PESOS $xdecimales/100 M.N. "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}
function subfijo($xx)
{ // esta funcion regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
}
function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0){ ##### Funcion para calcular fecha de vencimiento del pagare ######
      $date_r = getdate(strtotime($date));
      $date_result = date("Y-m-d",
                    mktime(($date_r["hours"]+$hh),
                           ($date_r["minutes"]+$mn),
                           ($date_r["seconds"]+$ss),
                           ($date_r["mon"]+$mm),
                           ($date_r["mday"]+$dd),
                           ($date_r["year"]+$yy)));
     return $date_result;
}
function calculamonto($cantidad, $monto1, $monto2, $plazo1, $plazo2, $tipo_pago){
	$saldo = (($plazo1 * $monto1) + ($plazo2 * $monto2));
	$int_moneda = ($saldo - $cantidad);
	$int_tot = (($int_moneda * 100)/$cantidad);
	switch($tipo_pago)
	{
		case "SEMANAL":	
			$tiempo = ceil(($plazo1 + $plazo2) / 4);
			$interes = $int_tot / $tiempo;
			break;
		case "QUINCENAL":
			$tiempo = ceil(($plazo1 + $plazo2) / 2);	
			$interes = $int_tot / $tiempo;
			break;
		case "MENSUAL":
			$tiempo = ceil(($plazo1 + $plazo2));
			$interes = $int_tot / $tiempo;	
			break; 
		case "CATORCENAL":
			$tiempo = ceil(($plazo1 + $plazo2) / 2);	
			$interes = $int_tot / $tiempo;
			break;		
	}
	$datosPrestamo = array('saldo'=>$saldo, 'int_moneda'=>$int_moneda, 'interes'=>$interes, 'tiempo'=>$tiempo);   
	return $datosPrestamo;
}   
function eliminaUsuario($u, $pas, $usr_Delete){
        $sql = 'SELECT username, password FROM mymvcdb_users WHERE username = "'.$u.'" AND  password = "'.$pas.'" AND NIVEL = 0';
        $res = mysql_query($sql);
        if(mysql_num_rows($res) > 0){
        	$sqlc = "DELETE FROM mymvcdb_users WHERE userID = $usr_Delete";
        	$queryc = mysql_query($sqlc);
        }
}
function revert_tipoPago($tipo_pago){
	switch($tipo_pago){
			case "SEMANAL":	
					$tipo_pago = 1;
			break;
			case "CATORCENAL":
					$tipo_pago = 2;	
			break;
            case "QUINCENAL":
					$tipo_pago = 3;	
			break;   
			case "MENSUAL":
					$tipo_pago = 4;
			break;  	
			}
	return $tipo_pago;
}
/*
function date_diffe($hoy, $proxpago){
	$hoy = date("Y-m-d");
	$dias	= (strtotime($hoy)-strtotime($proxpago))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	if($dias > 30){
		$dias = 30;
	}
	return $dias;
}
*/

function date_diffe($hoy, $proxpago){
	$hoy = date("Y-m-d");
	$dias	= (strtotime($hoy)-strtotime($proxpago))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}

function hayRecargos($cuenta, $cliente){
	$sql = "SELECT * FROM recargos WHERE cuenta = $cuenta AND cliente = $cliente AND estado = 0";
	$res = mysql_query($sql);
	if(mysql_num_rows($res) > 0){
		$recargos = 1;
	}else{
		$recargos = 0;
	}
	return $recargos;
}
function semaforo($avanced){

	if ($avanced <= 33.33) {
		$color = "#F78181";
	} elseif ($avanced > 33.33 && $avanced <= 66.66) {
		$color = "#E6E650";
	}elseif ($avanced > 66.66 && $avanced <= 100.00) {
		$color = "#7DB77B";
	}{
		
	}
	return $color;
}

function diasVencidos($cliente){
	### Obteniendo la cuenta que actualmente esta activa del cliente
	$cta ="SELECT id FROM cuentas WHERE cliente = $cliente AND estado = 0 LIMIT 1";
	$res1 = mysql_query($cta);
	$rec1 = mysql_fetch_array($res1);
	$cuenta = $rec1[0];
	### Obteniendo el primer pago sin pagar apartir de la cuenta del cliente
	$sql ="SELECT fecha FROM  pagos WHERE cuenta = $cuenta AND estado =0 LIMIT 1";
		$res = mysql_query($sql);
		$rec = mysql_fetch_array($res);
		$hoy = date("Y-m-d");
		$proxpago = $rec[0];
		if($hoy > $proxpago){ 
			$dias	= (strtotime($hoy)-strtotime($proxpago))/86400;
			$dias 	= abs($dias); $dias = floor($dias);
		}else{
			$dias = 0;
		}
		return $dias;
	}

function pagaCobrador($porcentaje, $cobrados){
	if ($porcentaje && $cobrados != "" && $porcentaje && $cobrados > 0){
		if ($porcentaje >= 90) {
			$tot_pagar = $cobrados * 15.00;
		}elseif ($porcentaje >= 85 && $porcentaje < 90) {
			$tot_pagar = $cobrados * 10.00;
		}elseif ($porcentaje >= 80 && $porcentaje < 85) {
			$tot_pagar = $cobrados * 7.00;
		}elseif ($porcentaje < 80) {
			$tot_pagar = $cobrados * 5.00;
		}
	}else{
		$tot_pagar = "Error: No se puede calcular el monto con valores vacios";
		return $tot_pagar;
	}

	return "$ ".number_format($tot_pagar,2,'.', ',');
}

function cuentaPagos($cuenta, $cliente){
	## Calculando el numero de pagos de la cuenta
	$sql = "SELECT COUNT(*) FROM pagos where cuenta = $cuenta AND cliente = $cliente";
	$res = mysql_query($sql);
	$rec = mysql_fetch_array($res);
	$numPagos = $rec[0];

	## Calculando el numero de pagos pagados de la cuenta
	$sql = "SELECT COUNT(*) FROM pagos where cuenta = $cuenta AND cliente = $cliente AND estado = 1";
	$res1 = mysql_query($sql);
	$rec1 = mysql_fetch_array($res1);
	$pPagados = $rec1[0];

	$ctaEdo = $pPagados ."/". $numPagos;
	return $ctaEdo;
}
?>
