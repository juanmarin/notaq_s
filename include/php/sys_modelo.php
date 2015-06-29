<?php
@session_start();
require_once "../conf/Config.php";
require_once "fun_global.php";

if(isset($_GET["action"])){
	$_POST["action"] = $_GET["action"];
}
switch($_POST["action"]){
	## CREAR USUARIO NUEVO EN EL SISTEMA -------------------------------------------
	case "usr_nuevo":
	## DEFINIMOS NIVEL   ----------------------------------------

		if($_POST["puesto"] == "COBRADOR"){
			$_POST["nivel"] = 3;
		}elseif($_POST["puesto"] == "ADMINISTRADOR"){
			$_POST["nivel"] = 0;			
			}

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
			$_SESSION["msg"] = '<tr><th colspan="2"><p class="error">Ya existe un usuario con ese username, intente con uno diferente.</p></th></tr>';
			#echo '<meta http-equiv="refresh" content="0;url=../../?pg=4b"> ';
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
			$_SESSION["msg"] = '<tr><th colspan="2"><p class="error">Las contraseÃ±as no coinciden, asegurese de escribir correctamente la contraseÃ±a al confirmarla.</p></th></tr>';
			#echo $_SESSION["msg"];
			#echo '<meta http-equiv="refresh" content="0;url=../../?pg=4b"> ';
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
			$_SESSION["msg"] = '<tr><th colspan="2"><p class="inportant">Usuario registrado con Ã©xito.</p></th></tr>';
			#echo $_SESSION["msg"];
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=4b"> ';
		}
		
		break;
	case "nota_nueva":
		date_default_timezone_set('America/Hermosillo');
		$sql = "SELECT id FROM cuentas WHERE estado = 0 AND cliente = ".$_POST["cl"];
		$res = mysql_query($sql);
		$cta = mysql_fetch_array($res);
		$ncta = $cta["id"];
		$sql = "INSERT INTO notas (cliente, nota, fecha) VALUES (".$ncta.", '".$_POST["nota"]."', '".date("Y-m-d H:i:s")."')";
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
		$_cadena = "INSERT INTO clientes (nombre, apellidop, apellidom, direccion, colonia, telefono, 
								celular, vivienda, Aval, activo, sector, empleo, dir_empl, c_empleo,
								tel_empl, nom_ref_1, cel_ref1, nom_ref_2, cel_ref2, c_cobrador)
		VALUES (
			'". $_POST["nombre"] ."',
			'". $_POST["apellidop"] ."',
			'". $_POST["apellidom"] ."',
			'". $_POST["dir"] ."',
			'". $_POST["col"] ."',
			'". $_POST["tel"] ."',
			'". $_POST["cel"] ."',
			'". $_POST["vivienda"] ."',
			'". $_POST["aval"] ."',
			'". $_POST["activo"] ."',
			'". $_POST["sector"] ."',
			'". $_POST["empleo"] ."',
			'". $_POST["dir_empl"]."',
			'". $_POST["c_empl"]."',
			'". $_POST["tel_empl"]."',
			'". $_POST["ref_1"]."',
			'". $_POST["cel_ref1"]."',
			'". $_POST["ref_2"]."',
			'". $_POST["cel_ref2"]."',
			'". $_POST["cobrador"] ."'
		)";
		$_query = mysql_query($_cadena);
		echo $_cadena;
		if($_query){
			$_SESSION["clid"] = mysql_insert_id();
			# CARGANDO IMAGEN DE CLIENTE -------------------------------------------------------------------------------------
			// Make sure the user actually
			// selected and uploaded a file
			if (isset($_FILES['image']) && $_FILES['image']['size'] > 0)
			{
				$sPhotoFileName = $_FILES['image']['name']; // get client side file name 
				if ($sPhotoFileName) // file uploaded 
				{
					$aFileNameParts = explode(".", $sPhotoFileName); $sFileExtension = end($aFileNameParts); // part behind last dot 
					if($sFileExtension != "jpg" && $sFileExtension != "JPEG" && $sFileExtension != "JPG") 
					{
						die ("Choose a JPG for the photo"); 
					}
					$nPhotoSize = $_FILES['image']['size']; // size of uploaded file 
					if($nPhotoSize == 0) 
					{
						die ("Sorry. The upload of $sPhotoFileName has failed. Search a photo smaller than 100K, using the button.");
					}
					// read photo 
					$sTempFileName = $_FILES['image']['tmp_name']; // temporary file at server side 
					$oTempFile = fopen($sTempFileName, "r");
					$sBinaryPhoto = fread($oTempFile, fileSize($sTempFileName)); // Try to read image 
					$nOldErrorReporting = error_reporting(E_ALL & ~(E_WARNING)); // ingore warnings 
					$oSourceImage = imagecreatefromstring($sBinaryPhoto); // try to create image 
					error_reporting($nOldErrorReporting);
					if(!$oSourceImage) // error, image is not a valid jpg 
					{
						die("Sorry. It was not possible to read photo $sPhotoFileName. Choose another photo in JPG format.");
					}
				}
				// CAMBIAR TAMAÑO DE IMAGEN --
				$nWidth = imagesx($oSourceImage);  // get original source image width 
				$nHeight = imagesy($oSourceImage); // and height 
				// create small thumbnail 
				$minsize=400;
				if($nWidth==$nHeight)
				{
					$nDestinationWidth = $minsize; 
					$nDestinationHeight = $minsize;
				}
				elseif($nWidth<$nHeight){
					$nDestinationWidth = $minsize; 
					$nDestinationHeight= ($minsize/$nWidth)*$nHeight;
				}else{
					$nDestinationHeight= $minsize; 
					$nDestinationWidth = ($minsize/$nHeight)*$nWidth;
				}
				$oDestinationImage = imagecreatetruecolor($nDestinationWidth, $nDestinationHeight); 
				imagecopyresized( $oDestinationImage, $oSourceImage, 0, 0, 0, 0, $nDestinationWidth, $nDestinationHeight, $nWidth, $nHeight); // resize the image 
				ob_start(); // /////////////////////////////// Start capturing stdout. 
				imageJPEG($oDestinationImage); // //////////// As though output to browser. 
				$sBinaryThumbnail = ob_get_contents(); // //// the raw jpeg image data. 
				ob_end_clean(); // /////////////////////////// Dump the stdout so it does not screw other output.
				// Create the query and insert
				// into our database.
				$sBinaryThumbnail = addslashes($sBinaryThumbnail);
				$query = "INSERT INTO clientefoto (idcliente,foto) VALUES (".$_SESSION["clid"].",'$sBinaryThumbnail')";
				//echo "<br />$query";
				$results = mysql_query($query);
				if($results){
					echo "<br />Checkpoint imagen</br />";
				}
			}
			if($_POST["aval"] ==1)
			{
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2db"> ';			
			}
			else{
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_SESSION["clid"].'"> ';
			}
		}
		break;
	case "cliente_nuevo2":
		$_cadena = "UPDATE clientes SET 
			R3nombre =		'". $_POST["r3nom"] ."',
			R3apellidop = 	'". $_POST["r3app"] ."', 
			R3apellidom = 	'". $_POST["r3apm"] ."', 
			R3dir =       	'". $_POST["r3dir"] ."', 
			R3col = 		'". $_POST["r3col"] ."', 
			R3tel = 		'".$_POST["r3tel"]."',
			R3vivienda = 	 ".$_POST["r3vivienda"]."
			Aval3 =           1
			WHERE id = ".$_SESSION["clid"];
		$_query = mysql_query($_cadena);
		if($_query){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_SESSION["clid"].'"> ';
		}else {
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_SESSION["clid"].'"> ';
		}
		break;
	case "cliente_nuevo3":
		$_cadena = "UPDATE clientes SET 
			R1nombre =		'". $_POST["r1nom"] ."',
			R1apellidop = 	'". $_POST["r1app"] ."', 
			R1apellidom = 	'". $_POST["r1apm"] ."', 
			R1dir =       	'". $_POST["r1dir"] ."', 
			R1col = 		'". $_POST["r1col"] ."', 
			R1tel = 		'".$_POST["r1tel"]."',
			R1vivienda = 	'".$_POST["r1vivienda"]."',
			Aval2 = 		 ".$_POST["aval2"].",
			Aval =           1
			WHERE id = ".$_SESSION["clid"];
		$_query = mysql_query($_cadena);
		if($_query){
			if($_POST["aval2"] ==1){
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2dc"> ';			
				}else {
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_SESSION["clid"].'"> ';
		}
	}
		break;
	case "cliente_nuevo4":
		$_cadena = "UPDATE clientes SET 
			R2nombre =		'". $_POST["r2nom"] ."',
			R2apellidop = 	'". $_POST["r2app"] ."', 
			R2apellidom = 	'". $_POST["r2apm"] ."', 
			R2dir = 		'". $_POST["r2dir"] ."', 
			R2col = 		'". $_POST["r2col"] ."', 
			R2tel = 		'".$_POST["r2tel"]."',
			R2vivienda = 	'".$_POST["r2vivienda"]."', 
			Aval3 =			 ".$_POST["aval3"]."	
			WHERE id = 		 ".$_SESSION["clid"];
		$_query = mysql_query($_cadena);
		if($_query){
			if($_POST["aval3"] ==1){
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2da"> ';			
			}else {
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_SESSION["clid"].'"> ';
			}
		}
		break;
	case "cte_elimina":
		# COMPROBAMOS USUARIO Y CONTRASENA
		$pas = sha1($_POST["c"]);
		$sql= 'SELECT username, password FROM mymvcdb_users WHERE username = "'.$_POST["u"].'" AND  password = "'.$pas.'" AND NIVEL = 0';
		$res = mysql_query($sql);
		if(mysql_num_rows($res)>0)
		{
			$sql = "DELETE FROM clientes WHERE id = ".$_POST["c"];
			$query = mysql_query($sql);
			$sql = "DELETE FROM clientefoto WHERE idcliente = ".$_POST["c"];
			mysql_query($sql);
			if($query){
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2"> ';
			}
			else{
				?>
				<script type="text/javascript" >
				alert("Permiso denegado.\nUsuario o contraseÃ±a incorrectos.\nIntente de nuevo.");
				</script>
				<?php
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cte"].'"> ';
			}
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
			vivienda = ".$_POST["vivienda"].",
			Aval = ".$_POST["aval_edit1"].",
			sector	=	'". $_POST["sector"] ."',
			empleo	=	'". $_POST["empleo"] ."',
			dir_empl	=	'". $_POST["dir_empl"]."',
			c_empleo	=	'". $_POST["c_empl"]."',
			tel_empl	=	'". $_POST["tel_empl"]."',
			nom_ref_1	=	'". $_POST["ref_1"]."',
			cel_ref1	=	'". $_POST["cel_ref1"]."',
			nom_ref_2	=	'". $_POST["ref_2"]."',
			cel_ref2	=	'". $_POST["cel_ref2"]."',
			c_cobrador	=	'". $_POST["cobrador"] ."'
			WHERE id = ".$_POST["cl"];
		$res = mysql_query($sql);
		if($res){
			if($_POST["aval_edit1"] ==1){
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2ba&cl='.$_POST["cl"].'"> ';		
				}else {
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		}
	}
		break;
		
	case "cliente_editar_2":
		$sql = "UPDATE clientes SET 
			R1nombre = '".$_POST["r1nom"]."',
			R1apellidop = '".$_POST["r1app"]."', 
			R1apellidom = '".$_POST["r1apm"]."', 
			R1dir = '".$_POST["r1dir"]."', 
			R1col = '".$_POST["r1col"]."', 
			R1tel = '".$_POST["r1tel"]."',
			R1vivienda = '".$_POST["r1vivienda"]."',
			Aval2 =  ".$_POST["aval_edit2"]."
			WHERE id = ".$_POST["cl"];
		$res = mysql_query($sql);
		if($res){
			if($_POST["aval_edit2"] ==1){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2bc&cl='.$_POST["cl"].'"> ';
		}else{
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		}
	}
		break;
	case "cliente_editar_3":
		$sql = "UPDATE clientes SET 
			R2nombre = '".$_POST["r2nom"]."',
			R2apellidop = '".$_POST["r2app"]."', 
			R2apellidom = '".$_POST["r2apm"]."', 
			R2dir = '".$_POST["r2dir"]."', 
			R2col = '".$_POST["r2col"]."', 
			R2tel = '".$_POST["r2tel"]."',
			R2vivienda = '".$_POST["r2vivienda"]."',
			Aval3 = ".$_POST["aval_edit3"]."
			WHERE id = ".$_POST["cl"];
		$res = mysql_query($sql);
		if($res){
			if($_POST["aval_edit3"] ==1) {
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2bd&cl='.$_POST["cl"].'"> ';
		}else{
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		}
	}
		break;

	case "cliente_editar_4":
		$sql = "UPDATE clientes SET 
			R3nombre = '".$_POST["r3nom"]."',
			R3apellidop = '".$_POST["r3app"]."', 
			R3apellidom = '".$_POST["r3apm"]."', 
			R3dir = '".$_POST["r3dir"]."', 
			R3col = '".$_POST["r3col"]."', 
			R3tel = '".$_POST["r3tel"]."',
			R3vivienda = '".$_POST["r3vivienda"]."' 
			WHERE id = ".$_POST["cl"];
		$res = mysql_query($sql);
		if($res){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2&cl='.$_POST["cl"].'"> ';
		}else{
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2bd&cl='.$_POST["cl"].'"> ';
		}
	case "cuenta_nueva":
		#echo "dia pago: " . $_POST["dias_pago"]. "<br />";
		if((($_POST["tipo_pago"] < 4) && ($_POST["dias_pago"] == "nd")) || ($_POST["tipo_pago"] == "nd") || ($_POST["cantidad"] == "") || ($_POST["plazo1"] == "") || ($_POST["monto1"] == "")){
			# no hacer nada porque estan mal los datos
		}else{
			# inicializar las variables
			switch($_POST["tipo_pago"]){
				case 1:	$tipo_pago = "SEMANAL";		break;
				case 2:	$tipo_pago = "CATORCENAL";	break;
				case 3:	$tipo_pago = "QUINCENAL";	break;   
				case 4:	$tipo_pago = "MENSUAL";		break;  		
			}
			$cantidad = $_POST["cantidad"];
			$monto1 = $_POST["monto1"];
			$monto2 = $_POST["monto2"]; 
			$plazo1 = $_POST["plazo1"];
			$plazo2 = $_POST["plazo2"];
			# mandamos llamar la funcion que nos traera los datos para crear la nueva cuenta
			$datosPrestamo = calculamonto($cantidad, $monto1, $monto2, $plazo1, $plazo2, $tipo_pago);
			//var_dump($datosPrestamo);
			$tiempo = $datosPrestamo['tiempo'];
			$interes = $datosPrestamo['interes'];
			$tipo_pago = revert_tipoPago($tipo_pago);

			## calcular total
			$total = $cantidad * (( ($interes * $tiempo)  / 100 ) + 1 );
			$npagos = $plazo1 + $plazo2;
			$pago = $total / $npagos; 
			if($_POST["tipo_pago"] == 4)
			{
				$diasPago = substr($_POST["fechapp"], -2);
			}else{
				$diasPago = $_POST["dias_pago"];
			}
			//echo "<br />Variable dias_pago: $diasPago <br />";		
			## creando cuenta 
			$_cadena = "INSERT INTO cuentas (cliente, fecha, cantidad, interes, tiempo, tipo_pago, dias_pago, total, npagos, pago, cobrador, observaciones)
				VALUES (
				 ". $_POST["cl"] .",
				'". $_POST["fecha"] ."',
				 ". $cantidad .",
				 ". $interes .",
				 ". $tiempo .",
				 ". $tipo_pago .",
				'". $diasPago ."',
				 ". $total .", 
				 ". $npagos .",
				 ". $pago .",
				'". $_POST["cobrador"] ."',
				'". $_POST["observ"]."'
			)";
			$res = mysql_query($_cadena);
			$cuenta = mysql_insert_id();
			$cnt = 0;
			$n = 1;
			if($tipo_pago == 1){
				$tipo = "week";
			}elseif($tipo_pago == 2) {
				$tipo = "week";
				$n = 2;
			}elseif($tipo_pago == 3) { 
				#$fechas = getFechasQuincenas($_POST["dias_pago"], $npagos, $_POST["fechapp"]);
				$dia = split("-", $diasPago);
				//echo "<br />Variable dias_pago: $diasPago <br />";
				//var_dump($dia);
				#$tipo = "week";
				#$n = 2;
			}else{
				$tipo = "month";
			}
			for($i=0;$i<$plazo1;$i++){
				if($cnt > 0){
					if($tipo_pago == 3){
						$a = (int)substr($prxpago, 0, -6);
						$m = (int)substr($prxpago, 5, -3);
						$d = (int)substr($prxpago, -2);
						if( $d >= 15 ){
							if($m == 12){$m = 1; $a++;}else{ $m++;}
							$d = $dia[0];
						}else{
							if($m == 2 && $dia[1] == 30) {
								$d = 28;
							}else{
								$d = $dia[1];
							}
						}
						if($m < 10){$m = "0".$m;}
						if($d < 10){$d = "0".$d;}
						$prxpago = $a . "-" . $m . "-" . $d;
						//echo $prxpago . " ~ " . $dia[0] . $dia[1] . "<br />";
					}else{
						$prxpago = date('Y-m-d', strtotime($prxpago.' + '.$n.' '.$tipo));
					}
				}else{
					$prxpago = $_POST["fechapp"];
				}
				$_SESSION["pp"] = $_POST["fechapp"];
				$sql = "INSERT INTO pagos (cliente, cuenta, fecha, pago, interes) 
				VALUES (
				".$_POST["cl"].",
				".$cuenta.", 
				'".$prxpago."',
				".$monto1.", 
				".($interes * $tiempo)."   
				)";
				mysql_query($sql);
				$cnt++;
			}
			#### INSERTANDO EL SEGUNDO MONTO A LA CUENTA ////
			if($monto2 && $plazo2 > 0)
			{
				for($i=0;$i<$plazo2;$i++)
				{
					if($cnt > 0)
					{
						if($tipo_pago == 3)
						{
							$a = (int)substr($prxpago, 0, -6);
							$m = (int)substr($prxpago, 5, -3);
							$d = (int)substr($prxpago, -2);
							if( $d >= 15 )
							{
								if($m == 12){$m = 1; $a++;}else{ $m++;}
								$d = $dia[0];
							}
							else
							{
								if($m == 2 && $dia[1] == 30)
								{
									$d = 28;
								}
								else
								{
									$d = $dia[1];
								}
							}
							if($m < 10){$m = "0".$m;}
							if($d < 10){$d = "0".$d;}
							$prxpago = $a . "-" . $m . "-" . $d;
							echo $prxpago . " ~ " . $dia[0] . $dia[1] . "<br />";
						}
						else
						{
							$prxpago = date('Y-m-d', strtotime($prxpago.' + '.$n.' '.$tipo));
						}
					}
					else
					{
						$prxpago = $_POST["fechapp"];
					}
					$_SESSION["pp"] = $_POST["fechapp"];
					$sql = "INSERT INTO pagos (cliente, cuenta, fecha, pago, interes) 
					VALUES (
					".$_POST["cl"].",
					".$cuenta.", 
					'".$prxpago."',
					".$monto2.", 
					".($interes * $tiempo)."   
					)";
					mysql_query($sql);
					$cnt++;
				}
			}
			//include_once "imprimeReciboCuenta.php";
		}
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		break;
	case "reimprimir_prestamo":
		$dia = $POST["dia"];
		$tipo = $POST["tipo"];
		//include_once "reimprimeReciboCuenta.php";
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		break;
	case "cuenta_pagar":
		##-variables enviadas 
		$cta 	= $_POST["c"];
		$abono 	= $_POST["pago"];
		$pid 	= $_POST["pid"];
		$cl 	= $_POST["cl"];
		$numpago= $_POST["numpago"];
		##-innformacion de cuenta
		$sql 	= "SELECT * FROM cuentas WHERE id = ".$cta;
		$res 	= mysql_query($sql);
		$c 	= mysql_fetch_array($res);
		$total 	= $c["total"];
		$tp 	= $c["tipo_pago"];
		$int 	= $c["interes"];
		$np 	= $c["tiempo"];
		##-Informacion del pago
		$pago 	= getCadena("pago","pagos","id",$pid);
		if( ($tp == 4) && ($np == 1) )
		{
			##-TIPO DE PAGO MENSUAL - 
			##-CANTIDAD A PAGAR --
			$sql = "SELECT * FROM pagos WHERE id = ".$pid;
			$res = mysql_query($sql);
			$p = mysql_fetch_array($res);
			$pago = $p["pago"];
			$fechap = $p["fecha"];
		 	## 
			if($pago == $abono)
			{
				if (hayRecargos($cta, $cl) == 0) {
		    	#-CUENTA SALDADA --
				$sql = "UPDATE cuentas SET estado = 1 WHERE id = ".$cta;
				#echo $sql . "<br />";
				mysql_query($sql);
				$sql = "UPDATE pagos SET estado = 1, pago_real = ".$abono." WHERE id = ".$pid;
				#echo $sql . "<br />";
				mysql_query($sql);
				
		    }else{
		    	$sql = "UPDATE pagos SET estado = 1, pago_real = ".$abono." WHERE id = ".$pid;
				#echo $sql . "<br />";
				mysql_query($sql);
		    }
				
			} 
			elseif( $abono < $pago )
			{
				#-ACTUALIZANDO PAGO --
				$saldo = $pago - $abono;
				$sql = "UPDATE pagos SET estado = 0, pago = ".$saldo." WHERE id = ".$pid;
				mysql_query($sql);
				#-ACTUALIZANDO CUENTA
				$ctasaldo = (($int / 100) + 1) * $saldo;
				$sql = "UPDATE cuentas SET total = ".$ctasaldo." WHERE id = ".$cta;
				mysql_query($sql);
				#-INSERTANGO ABONO
				$sql = "INSERT INTO abono (idpago, idcuenta, fecha, cargo, abono)values(".$pid.", ".$cta.", '".$fecha."', ".$pago.", ".$abono.")";
				mysql_query($sql);
			}
		}
		else
		{
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
				##-aplicar pago restante a demÃ¡s pagos
				setMontoRestante($cta, $abono);
			}elseif($abono == $total){
				if (hayRecargos($cta, $cl) == 0) {
					$sql = "UPDATE cuentas SET estado = 1 WHERE id = ".$cta;
					mysql_query($sql);
					$sql = "UPDATE pagos SET estado = 1, pago_real = ".$abono." WHERE id = ".$pid;
					mysql_query($sql);
				}else{
					$sql = "UPDATE pagos SET estado = 1, pago_real = ".$abono." WHERE id = ".$pid;
					mysql_query($sql);
				}
				
			}else{
				#-ACTUALIZAR CUENTA
				$saldo = $total - $abono;
				$sql = "UPDATE cuentas SET total = ".$saldo." WHERE id = ".$cta;
				mysql_query($sql);
				//echo $sql . "<br />";
				#-ACTUALIZAR PAGO		
				$npago = $pago - $abono;
				$sql = "UPDATE pagos SET pago = ".$npago." WHERE id = ".$pid;
				mysql_query($sql);
				//echo $sql . "<br />";
				#-AGREGAR EL ABONO
				$sql = "INSERT INTO abono (idpago, idcuenta, fecha, cargo, abono)values(".$pid.", ".$cta.", '".date("Y-m-d")."', ".$pago.", ".$abono.")";
				mysql_query($sql);
				//echo $sql . "<br />";
			}
		}
		$sql = "SELECT total FROM cuentas WHERE id = ".$cta;
		$res = mysql_query($sql);
		$s = mysql_fetch_array($res);
		if($s[0] == 0 && hayRecargos($cta, $cl) == 0){
			mysql_query("UPDATE cuentas SET estado = 1 WHERE id = ".$cta);
		}
		//include_once "imprimeReciboPago.php";
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$cl.'"> ';
		break;

	case "cuenta_saldar": 
		$sql = "SELECT cliente, total FROM cuentas WHERE id = ".$_POST["c"];
		#echo $sql . "<br />";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$saldo = $row["total"];
		$sql = "SELECT SUM(monto), cliente FROM recargos WHERE estado = 0 AND cuenta = ".$_POST["c"];
		#echo $sql . "<br />";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$recargos = $row[0];
		$monto = $saldo + $recargos;
		#echo number_format($monto, 2, ".", ",") . $_POST["total"] . "<br />";  
		if(number_format($monto, 2, ".", ",") == $_POST["total"]){
			$sql = "UPDATE cuentas SET estado=1, fecha_pago='".date("Y-m-d")."' WHERE id = ".$_POST["c"];
			$res = mysql_query($sql);

			$sql = "UPDATE pagos SET estado=1, fechaPago='".date("Y-m-d")."' WHERE estado=0 AND cuenta = ".$_POST["c"];
			$res = mysql_query($sql);

			//$sql = "UPDATE recargos SET estado=1, fechaPago='".date("Y-m-d")."' WHERE estado=0 AND cuenta = ".$_POST["c"];
			//$res = mysql_query($sql);

			$sql = "UPDATE clientes SET demanda=0 WHERE id = ".$_POST["cl"];
			$res = mysql_query($sql);

			$sql = "DELETE FROM demandas WHERE cliente_id = ".$_POST["cl"];
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
	#- ELIMINA USUARIO DEL SISTEMA ----------------------------------------------------
	case "usr_elimina";
		$pas 	= sha1($_POST["c"]);
		$u 	= $_POST["u"];
		$usrDel	= $_POST["usr"];
		eliminaUsuario($u, $pas, $usrDel);
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=4a"> ';
	break;

	case "cta_elimina":
		#- INICIAMOS VARIABLES -----------------------------------------------------------------------------------   
		$cte 	= $_POST["cte"];
		$cta 	= $_POST["cta"];
		$delCte = (isset($_POST["elimina"]))?$_POST["elimina"]:"";
		#- COMPROBAMOS EL USUARIO Y CONTRASEÃ‘A -------------------------------------------------------------------
		$pas 	= sha1($_POST["c"]);
		$sql 	= 'SELECT username, password FROM mymvcdb_users WHERE username = "'.$_POST["u"].'" AND  password = "'.$pas.'" AND NIVEL = 0';
		echo $sql;
		$res 	= mysql_query($sql);
		if(mysql_num_rows($res) > 0)
		{
			if($delCte == "yes")
			{
				$sqlc = "DELETE FROM clientes WHERE id = $cte";
				$queryc = mysql_query($sqlc);
				$sql = "DELETE FROM clientefoto WHERE idcliente = $cte";
				mysql_query($sql);
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2"> ';
			}
			else
			{
				#- LE DAMOS EN LA MADRE A TODO LO RELACIONADO CON ESA CUENTA Y ESE CLIENTE        
				$sql = "DELETE FROM cuentas WHERE id = $cta";
				$query = mysql_query($sql);
				if($query)
				{
					$sql = "DELETE FROM pagos WHERE cliente = $cte AND cuenta = $cta AND estado = 0";
					mysql_query($sql);
					$sql = "DELETE FROM recargos WHERE cliente = $cte AND cuenta = $cta";
					mysql_query($sql);
					$sql = "DELETE FROM notas WHERE cliete = $cte";
					mysql_query($sql);
					$sql = "DELETE FROM abonos WHERE idcuenta = $cta";
					mysql_query($sql);
					echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cte"].'"> ';
				}
			}
		}
		else
		{
			?>
			<script type="text/javascript" >
			alert("Permiso denegado.\nUsuario o contraseÃ±a incorrectos.\nIntente de nuevo.");
			</script>
			<?php
			if($delCte == yes)
			{
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2"> ';
			}else{
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cte"].'"> ';
			}
		}
	break;
        /*
	#- INICIAMOS VARIABLES -----------------------------------------------------------------------------------
	$cte = $_GET["cte"];
	$cta = $_GET["cta"];
	#- LE DAMOS EN LA MADRE A TODO LO RELACIONADO CON ESA CUENTA Y ESE CLIENTE        
	$sql = "DELETE FROM cuentas WHERE id = $cta";
	$query = mysql_query($sql);
	if($query){
	$sql = "DELETE FROM pagos WHERE cliente = $cte AND cuenta = $cta AND estado = 0";
	if($query){
	$sql = "DELETE FROM recargos WHERE cliente = $cte AND cuenta = $cta";
	if($query){
	$sql = "DELETE FROM notas WHERE cliete = $cta";
	if($query){
	echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_GET["cte"].'"> ';
	}
	}
	}
	}
	break;
	*/
	case "recargos":
		if($_POST["rec_pagar"])
		{
			//showPost($_POST);
			#-INICIANDO VARIABLES -------------------------------------------------------------------------------------------------
			$cuenta = $_POST["c"];
			$cliente = $_POST["cl"];
			$recargo_id = $_POST["recargo_id"];
			$abono = $_POST["recargo"];
			$f_recargo = $_POST["fecha_recargo"];
			#-OBTENIENDO MONTO DE RECARGOS ----------------------------------------------------------------------------------------
			$sql = "SELECT monto, monto_saldado FROM recargos WHERE id=".$recargo_id;
			$res = mysql_query($sql);
			$rec = mysql_fetch_array($res);
			$recargos = $rec[0];
			$saldados = $rec[1];
			echo "Recargos ".$recargos."</br>";
			echo "Saldados ".$saldados."</br>";
			#-VERIFICANDO SI CANTIDAD ABONADA CORRESPONDE AL TOTAL DE RECARGOS ----------------------------------------------------
			if($abono <= $recargos)
			{
				if($abono == $recargos)
				{
					$estado = 1;
					$restante = 0;
					$abono+=$saldados;
				}
				else 
				{
					$estado = 0;
					$restante  = $recargos - $abono;
					$abono+=$saldados;
				}
				$sql = "UPDATE recargos SET 
					fecha 		= '".date("Y-m-d")."', 
					monto_saldado 	= $abono, 
					estado 		= $estado,
					monto		= '$restante'
					WHERE id = $recargo_id";
				mysql_query($sql);
				##Verificando si despues del abono de recargos ya no hay mas por pagar.
				$sql = "SELECT SUM(monto) FROM recargos WHERE cuenta= $cuenta AND cliente = $cliente AND estado = 0";
				$res = mysql_query($sql);
				$rec = mysql_fetch_array($res);
				//echo "Recargos total".$rec[0];
				$sql = "SELECT total FROM cuentas id = $cuenta AND cliente = $cliente";
				$res1 = mysql_query($sql);
				$cta = mysql_fetch_array($res1);
				//echo "MONTO CUENTA ".$cta[0];
				if ($rec[0] == 0 && $cta[0] <= 0) {
					$sql = "UPDATE cuentas SET estado = 1 WHERE id = $cuenta";
					$res = mysql_query($sql);
					echo $sql;
				}
				//include_once("imprimeReciboRecargo.php");
			}
			//echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$cliente.'"> ';
		}
		elseif($_POST["rec_reimprime"])
		{
			//showPost($_POST);
			#-INICIANDO VARIABLES -----------------------------------------------------------------------------------------
			$cuenta = $_POST["c"];
			$cliente = $_POST["cl"];
			$pago_id = $_POST["pago_id"];
			$recargos = $_POST["recargo"];
			$f_recargo = $_POST["fecha_recargo"];
			//include_once("imprimeReciboRecargo.php");
			//echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$cliente.'"> ';
		}
		break;
		
	case "cuenta_solo_interes":
		$sql = "INSERT INTO pagos (cliente,cuenta,pago_real,fechaPago,interes,estado)VALUES(".$_POST["cl"].",".$_POST["c"].",".$_POST["cant"].",'".date("Y-m-d")."',0,1)";
		mysql_query($sql);
		$pid = recorreFechas($_POST["c"]);
		//echo $pid."<br>";
		$cliente = $_POST["cl"];
		$cuenta = $_POST["c"];
		$cantidad = $_POST["cant"];
		//include_once 'imprimeReciboInteres.php';
		echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$_POST["cl"].'"> ';
		break;
		
	case "cliente_demandas":
		foreach ($_POST['ids'] as $cl_id)
		{
			$sql = "UPDATE clientes SET 
			demanda = 1 
			WHERE id = ".$cl_id;
			$res = mysql_query($sql);
			if($res)
			{
				$demanda = "INSERT INTO demandas (cliente_id)VALUES(".$cl_id.")";
				$rest = mysql_query($demanda);
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2c"> ';
			}
		} 
		break;
		
	case "elimina_pago":
		foreach ($_POST['ids'] as $p_id){
			$sql = "UPDATE pagos SET
			pago_real =0,
			fechaPago = 0000-00-00,
			estado = 0
			WHERE id = " .$p_id;
			$res = mysql_query($sql);
			if($res)
			{
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=3e">';            
			}
		}
		break;
	case "pago_elimina":
		$p_id = $_POST["pid"];
		$pago = $_POST["pago"];
		$cl = $_POST["cl"];
		$cuenta = $_POST["c"];
		$sql = "UPDATE pagos SET 
			pago_real =0,
			fechaPago = 0000-00-00,
			estado = 0
			WHERE id = " .$p_id;
			$res = mysql_query($sql);
			if($res){
				$sql = "UPDATE cuentas SET total = (total+$pago) WHERE id = $cuenta"; 
				$res = mysql_query($sql);          
			}
			if($res){
				echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$cl.'">'; 
			}
		break;
	case "abono_elimina":
		$idabono = $_POST["idabono"];
		$cl = $_POST["cl"];
		$cta = $_POST["c"];
		$pid = $_POST["idpago"];
		$abono = $_POST["abono"];

		$sql ="DELETE FROM abono WHERE idabono = $idabono";
		$res = mysql_query($sql); 
		if($res){
			$sql = "UPDATE cuentas SET total = (total+$abono) WHERE id = $cta";
			$res = mysql_query($sql);
				
		}
		if($res){
			$sql = "UPDATE pagos SET pago = (pago+$abono) WHERE id = $pid";
			$res = mysql_query($sql);
		}
		if($res){
			echo '<meta http-equiv="refresh" content="0;url=../../?pg=2e&cl='.$cl.'">'; 
		}
		break;
		
	case "borrarnota":
		$sql = "DELETE FROM notas WHERE id = ".$_POST["n"];
		mysql_query($sql);
		$sql = "SELECT * FROM notas WHERE cliente = ".$_POST["c"];
		$res = mysql_query($sql);
		echo mysql_num_rows($res);
		break;		
		
	default:
		//Header("Location: ". HTTP_REFERER);
}
?>
