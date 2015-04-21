<?php
session_start();
?>
<html>
<thead>
	<title>Modelo</title>
	<style>
		<!-- body{background:#247;} -->
	</style>
</thead>
<body>
<?php
foreach($_POST as $var => $val){
	#echo $var . " - " . $val . "<br />";
}
require_once "../conf/Config.php";
require_once "fun_global.php";
if($_GET["action"]){
	$_POST["action"] = $_GET["action"];
}
switch($_POST["action"]){
	case "usr_nuevo":
		$sql = "SELECT username FROM mymvcdb_users WHERE username = '".$_POST["uname"]."'";
		$res = mysql_query($sql);
		if(mysql_num_rows($res) > 0)
		{
			$_SESSION["nu_nom"] = $_POST["nombre"]; 
			$_SESSION["nu_dep"] = $_POST["departamento"]; 
			$_SESSION["nu_pue"] = $_POST["puesto"]; 
			$_SESSION["nu_niv"] = $_POST["nivel"]; 
			$_SESSION["nu_ema"] = $_POST["email"]; 
			$_SESSION["nu_tel"] = $_POST["telefono"]; 
			$_SESSION["nu_una"] = $_POST["uname"];
			$_SESSION["msg"] = '<tr><th colspan="2"><p class="error">El usuario seleccionado ya existe, intente con uno diferente.</p></th></tr>';
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=4b"> ';
		}
		elseif($_POST["conu"] != $_POST["confnu"])
		{
			$_SESSION["nu_nom"] = $_POST["nombre"]; 
			$_SESSION["nu_dep"] = $_POST["departamento"]; 
			$_SESSION["nu_pue"] = $_POST["puesto"]; 
			$_SESSION["nu_niv"] = $_POST["nivel"]; 
			$_SESSION["nu_ema"] = $_POST["email"]; 
			$_SESSION["nu_tel"] = $_POST["telefono"]; 
			$_SESSION["nu_una"] = $_POST["uname"];
			$_SESSION["msg"] = '<tr><th colspan="2"><p class="error">Las contraseñas no coinciden, asegurese de escribir correctamente la contraseña al confirmarla.</p></th></tr>';
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=4b"> ';
		}
		else
		{
			$sql = "INSERT INTO mymvcdb_users (nombre, departamento, puesto, nivel, email, telefono, username, password, active, activationHash) 
					VALUES 
					(	
						'".$_POST["nombre"]."', 
						'".$_POST["departamento"]."', 
						'".$_POST["puesto"]."', 
						".$_POST["nivel"].", 
						'".$_POST["email"]."', 
						'".$_POST["telefono"]."', 
						'".$_POST["uname"]."', 
						'".sha1($_POST["conu"])."', 
						1, 
						'".$_SESSION["hash"]."'
					)";
			$res = mysql_query($sql);
			unset($_SESSION["nu_nom"]); 
			unset($_SESSION["nu_dep"]); 
			unset($_SESSION["nu_pue"]); 
			unset($_SESSION["nu_niv"]); 
			unset($_SESSION["nu_ema"]); 
			unset($_SESSION["nu_tel"]); 
			unset($_SESSION["nu_una"]);
			$_SESSION["msg"] = '<tr><th colspan="2"><p class="inportant">Usuario registrado con éxito.</p></th></tr>';
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=4b"> ';
		}
		break;
	case "nota_nueva":
		$sql = "INSERT INTO notas (cliente, nota, fecha) VALUES (".$_POST["cl"].", '".$_POST["nota"]."', '".date("Y-m-d")."')";
		$res = mysql_query($sql);
		if($res){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		}
		break;
	case "usr_editar":
		$_cadena = "UPDATE mymvcdb_users SET 
			username = '".$_POST["uname"]."', 
			nombre = '".$_POST["nombre"]."', 
			puesto = '".$_POST["puesto"]."', 
			departamento = '".$_POST["departamento"]."', 
			telefono = '".$_POST["telefono"]."', 
			email = '".$_POST["email"]."', 
			nivel = ".$_POST["nivel"]." 
			WHERE userID = ".$_POST["id"];
		$_query = mysql_query($_cadena);
		if($_POST["conu"] == $_POST["confnu"]){
			$sql = "SELECT password FROM mymvcdb_users WHERE userID = ".$_SESSION["REQUIRED1"];
			$res = mysql_query($sql);
			$r = mysql_fetch_array($res);
			if(sha1($_POST["conac"]) == $r["password"]){
				$sql = "UPDATE mymvcdb_users SET password = '".sha1($_POST["confnu"])."' WHERE userID = ".$_SESSION["REQUIRED1"];
				mysql_query($sql);
				mysql_close();
			}
		}
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=4"> ';
		break;
	case "cliente_nuevo":
		$_cadena = "INSERT INTO clientes (nombre, apellidop, apellidom, direccion, colonia, telefono, celular, rfc, vivienda)
		VALUES (
			'". $_POST["nombre"] ."',
			'". $_POST["apellidop"] ."',
			'". $_POST["apellidom"] ."',
			'". $_POST["dir"] ."',
			'". $_POST["col"] ."',
			'". $_POST["tel"] ."',
			'". $_POST["cel"] ."',
			'". $_POST["rfc"] ."',
			". $_POST["vivienda"] ."
		)";
		$_query = mysql_query($_cadena);
		if($_query){
			$_SESSION["clid"] = mysql_insert_id();
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2da"> ';
		}
		break;
	case "cliente_nuevo2":
		$_cadena = "UPDATE clientes SET 
			empresa = '". $_POST["empresa"] ."',
			epuesto = '". $_POST["puesto"] ."', 
			edireccion = '". $_POST["dir"] ."', 
			ecolonia = '". $_POST["col"] ."', 
			etelefono = '". $_POST["tel"] ."', 
			propio = ".$_POST["propio"].",
			activo = 1 
			WHERE id = ".$_SESSION["clid"];
		$_query = mysql_query($_cadena);
		if($_query){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2db"> ';
		}
		break;
	case "cliente_nuevo3":
		$_cadena = "UPDATE clientes SET 
			R1nombre = '". $_POST["r1nom"] ."',
			R1apellidop = '". $_POST["r1app"] ."', 
			R1apellidom = '". $_POST["r1apm"] ."', 
			R1dir = '". $_POST["r1dir"] ."', 
			R1col = '". $_POST["r1col"] ."', 
			R1tel = '".$_POST["r1tel"]."',
			R1vivienda = ".$_POST["r1vivienda"]." 
			WHERE id = ".$_SESSION["clid"];
		$_query = mysql_query($_cadena);
		if($_query){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2dc"> ';
		}
		break;
	case "cliente_nuevo4":
		$_cadena = "UPDATE clientes SET 
			R2nombre = '". $_POST["r2nom"] ."',
			R2apellidop = '". $_POST["r2app"] ."', 
			R2apellidom = '". $_POST["r2apm"] ."', 
			R2dir = '". $_POST["r2dir"] ."', 
			R2col = '". $_POST["r2col"] ."', 
			R2tel = '".$_POST["r2tel"]."',
			R2vivienda = ".$_POST["r2vivienda"]." 
			WHERE id = ".$_SESSION["clid"];
		$_query = mysql_query($_cadena);
		if($_query){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_SESSION["clid"].'"> ';
		}
		break;
	case "lscl_elimina":
		$sql = "DELETE FROM clientes WHERE id = ".$_GET["cl"];
		$query = mysql_query($sql);
		if($query){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2"> ';
		}
		break;
	case "cliente_editar_1":
		$sql = "UPDATE clientes SET 
			nombre = '".$_POST["nombre"]."', 
			apellidop = '".$_POST["apellidop"]."', 
			apellidom = '".$_POST["apellidom"]."', 
			direccion = '".$_POST["dir"]."', 
			colonia = '".$_POST["col"]."', 
			telefono = '".$_POST["tel"]."', 
			celular = '".$_POST["cel"]."', 
			rfc = '".$_POST["rfc"]."', 
			vivienda = ".$_POST["vivienda"]." 
			WHERE id = ".$_POST["cl"];
		$res = mysql_query($sql);
		if($res){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2ba&cl='.$_POST["cl"].'"> ';
		}else{
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2b&cl='.$_POST["cl"].'"> ';
		}
		break;
	case "cliente_editar_2":
		$sql = "UPDATE clientes SET 
			empresa = '".$_POST["empresa"]."', 
			epuesto = '".$_POST["puesto"]."', 
			edireccion = '".$_POST["dir"]."', 
			ecolonia = '".$_POST["col"]."', 
			etelefono = '".$_POST["tel"]."', 
			propio = '".$_POST["propio"]."' 
			WHERE id = ".$_POST["cl"];
		$res = mysql_query($sql);
		if($res){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2&cl='.$_POST["cl"].'"> ';
		}else{
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2b&cl='.$_POST["cl"].'"> ';
		}
		break;
	case "cliente_editar_3":
		$sql = "UPDATE clientes SET 
			R1nombre = '".$_POST["r1nom"]."',
			R1apellidop = '".$_POST["r1app"]."', 
			R1apellidom = '".$_POST["r1apm"]."', 
			R1dir = '".$_POST["r1dir"]."', 
			R1col = '".$_POST["r1col"]."', 
			R1tel = '".$_POST["r1tel"]."',
			R1vivienda = '".$_POST["r1vivienda"]."' 
			WHERE id = ".$_POST["cl"];
		$res = mysql_query($sql);
		if($res){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2bd&cl='.$_POST["cl"].'"> ';
		}else{
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2b&cl='.$_POST["cl"].'"> ';
		}
		break;

	case "cliente_editar_4":
		$sql = "UPDATE clientes SET 
			R2nombre = '".$_POST["r2nom"]."',
			R2apellidop = '".$_POST["r2app"]."', 
			R2apellidom = '".$_POST["r2apm"]."', 
			R2dir = '".$_POST["r2dir"]."', 
			R2col = '".$_POST["r2col"]."', 
			R2tel = '".$_POST["r2tel"]."',
			R2vivienda = '".$_POST["r2vivienda"]."' 
			WHERE id = ".$_POST["cl"];
		$res = mysql_query($sql);
		if($res){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2&cl='.$_POST["cl"].'"> ';
		}else{
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2bd&cl='.$_POST["cl"].'"> ';
		}
	case "cuenta_nueva":
		#echo "dia pago: " . $_POST["dias_pago"]. "<br />";
		if	(
				(($_POST["tipo_pago"] < 4) && ($_POST["dias_pago"] == "nd")) || 
				($_POST["tipo_pago"] == "nd") || 
				($_POST["cantidad"] == "") || 
				($_POST["interes"] == "") || 
				($_POST["tiempo"] == "") 
			){
			# no hacer nada porque estan mal los datos
		}else{
			## calcular total
			$total = $_POST["cantidad"] * (( ($_POST["interes"] * $_POST["tiempo"])  / 100 ) + 1 );
			$npagos = getPagos($total, $_POST["tiempo"], $_POST["tipo_pago"]);
			$pago = $total / $npagos; 
			if($_POST["tipo_pago"] == 4){
				$diasPago = substr($_POST["fechapp"], -2);
			}else{
				$diasPago = $_POST["dias_pago"];
			}			
			## creando cuenta 
			$_cadena = "INSERT INTO cuentas (cliente, fecha, cantidad, interes, tiempo, tipo_pago, dias_pago, total, npagos, pago, observaciones)
			VALUES (
				". $_POST["cl"] .",
				'". $_POST["fecha"] ."',
				". $_POST["cantidad"] .",
				". $_POST["interes"] .",
				". $_POST["tiempo"] .",
				". $_POST["tipo_pago"] .",
				'". $diasPago ."',
				". $total .", 
				". $npagos .",
				". $pago .",
				'". $_POST["observ"]."'
			)";
			#echo $_cadena;
			$res = mysql_query($_cadena);
			$cuenta = mysql_insert_id();
			$cnt = 0;
			$n = 1;
			if($_POST["tipo_pago"] == 1){
				$tipo = "week";
			}elseif($_POST["tipo_pago"] == 2) { 
				$tipo = "week";
				$n = 2;
			}elseif($_POST["tipo_pago"] == 3) { 
				#$fechas = getFechasQuincenas($_POST["dias_pago"], $npagos, $_POST["fechapp"]);
				$dia = split("-", $_POST["dias_pago"]);
				#$tipo = "week";
				#$n = 2;
			}else {
				$tipo = "month";
			}
			for($i=0;$i<$npagos;$i++) {
				if($cnt > 0){
					if($_POST["tipo_pago"] == 3){
						$a = (int)substr($prxpago, 0, -6);
						$m = (int)substr($prxpago, 5, -3);
						$d = (int)substr($prxpago, -2);
						if( $d > 15 ){
							if($m == 12){$m = 1; $a++;}else{ $m++;}
							$d = $dia[0];
						}else{
							if($m == 2 && $dia[1] == 30) {
								$d = 28;
							}else {
								$d = $dia[1];
							}
						}
						if($m < 10){$m = "0".$m;}
						if($d < 10){$d = "0".$d;}
						$prxpago = $a . "-" . $m . "-" . $d;
						echo $prxpago . " ~ " . $dia[0] . $dia[1] . "<br />";
					}else {
						$prxpago = date('Y-m-d', strtotime($prxpago.' + '.$n.' '.$tipo));
					}
				}else {
					$prxpago = $_POST["fechapp"];
				}
				$sql = "INSERT INTO pagos (cliente, cuenta, fecha, pago, interes) 
				VALUES (
					".$_POST["cl"].",
					".$cuenta.", 
					'".$prxpago."',
					".$pago.", 
					".($_POST["interes"] * $_POST["tiempo"])."   
				)";
				//echo $sql . "<br />";
				mysql_query($sql);
				$cnt++;
			}
			include_once "imprimeReciboCuenta.php";
		}
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		break;
	case "cuenta_pagar":
		##-variables enviadas 
		$cta = $_POST["c"];
		$abono = $_POST["pago"];
		$pid = $_POST["pid"];
		$cl = $_POST["cl"];
		$numpago = $_POST["numpago"];
		##-innformacion de cuenta
		$sql = "SELECT * FROM cuentas WHERE id = ".$cta;
		$res = mysql_query($sql);
		$c = mysql_fetch_array($res);
		$pago = $c["pago"];
		$total = $c["total"];
		##-aplicando pago en cuenta y pagos
		if($abono >= $pago){
			## calculando saldo
			$saldo = $total - $abono;
			## calcula pagos 
			$sql = "SELECT SUM(pago) FROM pagos WHERE estado = 0 AND cuenta = ".$cta." AND id <= ".$pid;
			$res = mysql_query($sql);
			$sp = mysql_fetch_array($res);
			$sumapagos = $sp[0];
			## ACTUALIZAR LA CUENTA CON EL NUEVO SALDO 
			$sql = "UPDATE cuentas SET total = ".$saldo." WHERE id = ".$cta;
			mysql_query($sql);
			## CARGAR EL ABONO EN LOS PAGOS 
			$sql = "UPDATE pagos SET estado = 1, fechaPago = '".date("Y-m-d")."', pago_real = ".$abono." WHERE id = ".$pid;
			mysql_query($sql);
			## ACTUALIZAR EL ESTADO DE LOS APGOS SALDADOS 
			$sql = "UPDATE pagos SET estado = 3, fechaPago = '".date("Y-m-d")."', pago_real = 0 WHERE cuenta = ".$cta." AND estado = 0 AND  id < ".$pid;
			mysql_query($sql);
			##-restar a ultimo pago
			$abono -= $sumapagos;
			##-aplicar pago restante a demás pagos
			setMontoRestante($cta, $abono);
		}elseif($abono == $total){
			$sql = "UPDATE cuentas SET estado = 1 WHERE id = ".$cta;
			mysql_query($sql);
			$sql = "UPDATE cuentas SET estado = 1, pago_real = ".$abono." WHERE id = ".$pid;
			mysql_query($sql);
		}
		$sql = "SELECT total FROM cuentas WHERE id = ".$cta;
		$res = mysql_query($sql);
		$s = mysql_fetch_array($res);
		if($s[0] == 0){
			mysql_query("UPDATE cuentas SET estado = 1 WHERE id = ".$cta);
		}
		include_once "imprimeReciboPago.php";
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$cl.'"> ';
		break;
	case "cuenta_saldar": 
		$sql = "SELECT total FROM cuentas WHERE id = ".$_POST["c"];
		#echo $sql . "<br />";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$saldo = $row["total"];
		$sql = "SELECT SUM(monto) FROM recargos WHERE estado = 0 AND cuenta = ".$_POST["c"];
		#echo $sql . "<br />";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$recargos = $row[0];
		$monto = $saldo + $recargos;
		#echo number_format($monto, 2, ".", ",") . $_POST["total"] . "<br />";  
		if(number_format($monto, 2, ".", ",") == $_POST["total"]){
			$sql = "UPDATE cuentas SET estado=1, fecha_pago='".date("Y-m-d")."' WHERE id = ".$_POST["c"];
			#echo $sql . "<br />zzz";
			$res = mysql_query($sql);
			$sql = "UPDATE pagos SET estado=1, fechaPago='".date("Y-m-d")."' WHERE estado=0 AND cuenta = ".$_POST["c"];
			#echo $sql . "<br />";
			$res = mysql_query($sql);
			$sql = "UPDATE recargos SET estado=1, fechaPago='".date("Y-m-d")."' WHERE estado=0 AND cuenta = ".$_POST["c"];
			#echo $sql . "<br />";
			$res = mysql_query($sql);
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		}else{
			if(($_POST["usuario"] != "") && ($_POST["autorizacion"] != "")){
				$sql = "SELECT nivel FROM mymvcdb_users WHERE username = '".$_POST["usuario"]."' AND password = '".sha1($_POST["autorizacion"])."'";
    			$res = mysql_query($sql);
				$u = mysql_fetch_array($res);
				if(mysql_num_rows($res) > 0){
					if($u[0] == 0){
						$monto = str_replace(",", "", $_POST["total"]);
						$sql = "UPDATE cuentas SET estado = 2, monto_saldado = ".$monto.", fecha_pago = '".date("Y-m-d")."' WHERE id = ".$_POST["c"];
						echo $sql . "<br />";
						$res = mysql_query($sql);
						if($res){
							$sql = "UPDATE pagos SET estado = 2, fechaPago='".date("Y-m-d")."' WHERE cuenta = ".$_POST["c"]." AND estado = 0";
							$res = mysql_query($sql);
						}
					}
				}
			}
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		}
		break;
	case "cta_elimina":
    
        #- INICIAMOS VARIABLES -----------------------------------------------------------------------------------
        $cte = $_GET["cte"];
        $cta = $_GET["cta"];
        #- LE DAMOS EN LA MADRE A TODO LO RELACIONADO CON ESA CUENTA Y ESE CLIENTE        
        $sql = "DELETE FROM cuentas WHERE id = $cta";
		$query = mysql_query($sql);
        #echo "cuenta" .$cta ."<br />";
        echo $sql ."<br />";
		if($query){
            $sql = "DELETE FROM pagos WHERE cliente = $cte AND cuenta = $cta AND estado = 0";
        echo $sql ."<br />";
        if($query){
            $sql = "DELETE FROM recargos WHERE cliente = $cte AND cuenta = $cta";
        echo $sql ."<br />";
        if($query){
            $sql = "DELETE FROM notas WHERE cliete = $cte";
        echo $sql ."<br />";
        if($query){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_GET["cte"].'"> ';
		}
        }
        }
        }
		break;	
	case "recargos_pagar":
		#showPost($_POST);
		#-INICIANDO VARIABLES -----------------------------------------------------------------------------------------
		$cuenta = $_POST["c"];
		$cliente = $_POST["cl"];
		$abono = $_POST["recargo"];
		$usr = $_POST["usuario"];
		$pas = $_POST["autorizacion"];
		
		#-OBTENIENDO MONTO DE RECARGOS --------------------------------------------------------------------------------
		$sql = "SELECT SUM(monto) FROM recargos WHERE estado=0 AND cuenta = ".$cuenta;
		$res = mysql_query($sql);
		$rec = mysql_fetch_array($res);
		$recargos = $rec[0];
		
		#-VERIFICANDO SI CANTIDAD ABONADA CORRESPONDE AL TOTAL DE RECARGOS --------------------------------------------
		if($abono == $recargos){
			$sql = "UPDATE recargos SET monto_saldado = ".$monto.", estado = 1, fechaPago = '".date("Y-m-d")."' WHERE estado = 0 AND cuenta = ".$cuenta." LIMIT 0, 1";
			mysql_query($sql);
			$sql = "UPDATE recargos SET estado = 3 WHERE estado = 0 AND cuenta = ".$cuenta;
			mysql_query($sql);
		} else {
			$sql = "SELECT nivel FROM mymvcdb_users WHERE username = '".$usr."' AND password = '".sha1($pas)."'";
			$res = mysql_query($sql);
			if(mysql_num_rows($res) > 0){
				$u = mysql_fetch_array($res);
				$niv = $u["nivel"];
				if($niv < 2){
					$sql = "UPDATE recargos SET monto_saldado = ".$monto.", estado = 2, fechaPago = '".date("Y-m-d")."' WHERE estado = 0 AND cuenta = ".$cuenta." LIMIT 0, 1";
					mysql_query($sql);
					$sql = "UPDATE recargos SET estado = 3 WHERE estado = 0 AND cuenta = ".$cuenta;
					mysql_query($sql);
				} else {
					?>
					<script type="text/javascript" >
						alert("Permiso denegado.\n
						El usuario no tiene permiso para asignar descuentos en recargos.");
					</script>
					<?php
				}
			} else {				
				?>
				<script type="text/javascript" >
					alert("Permiso denegado.\n
					Usuario o contraseña incorrectos.");
				</script>
				<?php
			}
		}
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$cliente.'"> ';
		break;
	case "recargos_condonar":
		$sql="SELECT nivel FROM mymvcdb_users WHERE username = '".$_POST["usuario"]."' AND password = '".sha1($_POST["autorizacion"])."'";
		$res=mysql_query($sql);
		$au = mysql_fetch_array($res);
		if((mysql_num_rows($res) > 0) && ($au[0] == 0)){
			$sql="UPDATE recargos SET estado = 3 WHERE estado=0 AND cuenta=".$_POST["c"];
			mysql_query($sql);
		} else {
			?>
			<script type="text/javascript" >
				alert("Permiso denegado.\n
				Usuario o contraseña incorrectos.\n
				Verifique que el usuario tenga permiso para esta operación.");
			</script>
			<?php
		}
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		break;
	case "recargos_pagos":
		$sql="SELECT nivel FROM mymvcdb_users WHERE username = '".$_POST["usuario"]."' AND password = '".sha1($_POST["autorizacion"])."'";
		$res=mysql_query($sql);
		$au = mysql_fetch_array($res);
		if((mysql_num_rows($res) > 0) && ($au[0] == 0)){
			## obtener el tipo de pago 
			$sql="SELECT tipo_pago, dias_pago FROM cuentas WHERE id=".$_POST["c"];
			$res=mysql_query($sql);
			$c=mysql_fetch_array($res);
			$tp = $c[0];
			$dp = $c[1];
			echo $sql . "<br />";
			echo $tp . " / " . $dp . "<br />";
			
			## obtener la ultima fecha de pago 
			$sql = "SELECT fecha FROM pagos WHERE cuenta = ".$_POST["c"];
			$res = mysql_query($sql);
			while($p = mysql_fetch_array($res)){
				$fecha = $p[0];
				echo $p["fecha"] . "<br />";
			}
			echo $sql . "<br />";
			echo $fecha . "<br />";
			## obtener fecha para el nuevo pago que se va a agregar
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
			$prxpago = $fecha;
			if($tp == 3){
				$dp = split("-", $dp);
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
			}else {
				$prxpago = date('Y-m-d', strtotime($prxpago.' + '.$n.' '.$tipo));
			}
			
			## calcular monto de los recargos 
			$sql="SELECT SUM(monto) FROM recargos WHERE estado=0 AND cuenta=".$_POST["c"];
			$res = mysql_query($sql);
			$r=mysql_fetch_array($res);
			$recargos=$r[0];
			echo $sql . "<br />";
			
			## insertar el nuevo pago 
			$sql="INSERT INTO pagos (cliente, cuenta, fecha, pago, interes) 
				VALUES 
				(".$_POST["cl"].", ".$_POST["c"].", '".$prxpago."', ".$recargos.", 0)";
			mysql_query($sql);
			echo $sql;
			
			## cambiar estado de los recargos (estado=3 significa que el recargo ha sido desplazado como un pago mas) 
			$sql="UPDATE recargos SET estado = 4 WHERE estado=0 AND cuenta=".$_POST["c"];
			mysql_query($sql);
		}
		#echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		break;
	case "cuenta_solo_interes":
		$sql = "INSERT INTO pagos (cliente, cuenta, pago_real, fechaPago, interes, estado) 
			VALUES (".$_POST["cl"].", ".$_POST["c"].", ".$_POST["cant"].", '".date("Y-m-d")."', 0, 1)";
		mysql_query($sql);
		recorreFechas($_POST["c"]);
		$cliente = $_POST["cl"];
		$cuenta = $_POST["c"];
		$cantidad = $_POST["cant"];
		include_once 'imprimeReciboInteres.php';
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		break;
	default:
		//Header("Location: ". HTTP_REFERER);
}
?>
</body>
</html>
