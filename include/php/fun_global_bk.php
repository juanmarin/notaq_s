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
				case 1:	echo "LUNES";			break;
				case 2:	echo "MARTES";			break;
				case 3:	echo "MIERCOLES";		break;
				case 4:	echo "JUEVES";			break;
				case 5:	echo "VIERNES";			break;
				case 6:	echo "SABADO";			break;
				default:	echo "NO DEFINIDO";
			}
		}elseif($tipo==3) {
			switch($dia) {
				case "1-16"	:	echo "DIAS 16 Y 1 DE CADA MES";		break;
				case "2-17"	:	echo "DIAS 17 Y 2 DE CADA MES";		break;
				case "2-16"	:	echo "DIAS 2 Y 16 DE CADA MES";		break;
				case "8-22"	:	echo "DIAS 8 Y 22 DE CADA MES";		break;
				case "15-30":	echo "DIAS 15 Y 30 DE CADA MES";	break;
				case "1-15"	:	echo "DIAS 15 Y 1 DE CADA MES";		break;
				case "6-21"	:	echo "DIAS 6 Y 21 DE CADA MES";		break;
				case "3-18"	:	echo "DIAS 3 Y 18 DE CADA MES";		break;
				case "4-18"	:	echo "DIAS 4 Y 18 DE CADA MES";		break;
				default:	echo "NO DEFINIDO";
			}
		}else {
			switch($dia) {
				case "1"	:	echo "DIA 1 DE CADA MES";	break;
				case "16":	echo "DIA 16 CADA MES";			break;
				default:	echo "NO DEFINIDO";
			}	
		}
	}
	
	function getDiaSemana_($dia, $tipo){
		if($tipo < 3){
			switch($dia) {
				case 1:	return "LUNES";			break;
				case 2:	return "MARTES";			break;
				case 3:	return "MIERCOLES";		break;
				case 4:	return "JUEVES";			break;
				case 5:	return "VIERNES";			break;
				case 6:	return "SABADO";			break;
				default:	return "NO DEFINIDO";
			}
		}elseif($tipo==3) {
			switch($dia) {
				case "1-16"	:	return "DIAS 16 Y 1 DE CADA MES";		break;
				case "2-17"	:	return "DIAS 17 Y 2 DE CADA MES";		break;
				case "2-16"	:	return "DIAS 2 Y 16 DE CADA MES";		break;
				case "8-22"	:	return "DIAS 8 Y 22 DE CADA MES";		break;
				case "15-30":	return "DIAS 15 Y 30 DE CADA MES";		break;
				case "1-15"	:	return "DIAS 15 Y 1 DE CADA MES";		break;
				case "6-21"	:	return "DIAS 6 Y 21 DE CADA MES";		break;
				case "3-18"	:	return "DIAS 3 Y 18 DE CADA MES";		break;
				case "4-18"	:	return "DIAS 4 Y 18 DE CADA MES";		break;
				default:		return "NO DEFINIDO";
			}
		}else {
			switch($dia) {
				case "1"	:	return "DIA 1 DE CADA MES";	break;
				case "16"	:	return "DIA 16 CADA MES";			break;
				default	:	return "NO DEFINIDO";
			}	
		}
	}
	
	function getInteresPago2($cantidad, $npagos, $interes){
        $pago = ($cantidad/$npagos);
		$int = (($interes * $pago) / 100);
		return $int;
	}
	
	function getInteresPago($pago, $interes){
		$int = (($interes * $pago) / 100);
		return $int;
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
		}
	}
?>
