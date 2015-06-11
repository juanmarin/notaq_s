<?php @session_start();
/*
*/
	//AQUI ES DONDE SE DA LA MAGIA!
	require_once("include/php/fun_global.php"); 
	require_once("include/php/sys_db.class.php");
	if($_SESSION["hola"] == 1){} else {$_SESSION["hola"] = 0;}
	$_SESSION["REQUIRED1"] = $user->userData[0];
	$_SESSION["U_NIVEL"] = $user->userData[10];
	$_SESSION["DEPTO"] = $user->userData[7];
	$_SESSION["USERNAME"] = $user->userData[1];
	if($_SESSION["U_NIVEL"] == 0){$_SESSION["hash"] = $user->randomPass(100);}
	//echo var_dump($user);
?>
<html>
<head>
<title>NOTAq</title>
<meta name="author" content="Juan Marin" />
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="content-style-type" content="text/css" />

<link rel="stylesheet" href="estilo/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="estilo/global.css" type="text/css" media="all" />
<link rel="stylesheet" href="estilo/alerts.css" type="text/css" media="all" />
<link rel="stylesheet" href="estilo/themes/base/ui.all.css" type="text/css" media="all" />
<link rel="stylesheet" href="estilo/thickbox.css" type="text/css" media="all" />
<link rel="stylesheet" href="estilo/jquery.cleditor.css" type="text/css" media="all" />
<!--[if IE]>
	<style>
		@import url("estilo/IEfixes.css");
	</style>
<![endif]-->
<script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/ui/ui.core.js"></script>
<script type="text/javascript" src="js/ui/effects.core.js"></script>
<script type="text/javascript" src="js/ui/effects.slide.js"></script>
<script type="text/javascript" src="js/ui/ui.datepicker.js"></script>  
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script type="text/javascript" src="js/jquery.cleditor.min.js"></script>
<script type="text/javascript" src="js/global.js"></script>
</head>
<body>
	<div id="main">
		<div id="barra" class="sombra esqRedondas_b">
			<div id="logo">NOTAq</div>
			<div id="vmenu">
				<ul class="menul">
					<?php
					$pg = (isset($_GET["pg"]))?$_GET["pg"]:0;
					switch($pg)
					{
						case 3:
							if($_SESSION["U_NIVEL"] == 0){
								?>
    								<li><a href="?pg=3a" class="_diario">Reporte Diario</a></li>
    								<li><a href="?pg=3b" class="_fechas">Reporte por Fechas</a></li>
    								<li><a href="?pg=3c" class="_visitas">Lista Cobranza</a></li>
								<li><a href="?pg=3f" class="_visitas">Reporte Recargos</a></li>
								<li><a href="?pg=3d" class="_estado">Historial Credito</a></li>
								<li><a href="?pg=3e" class="_pagos">Control Pagos</a></li>
								<li><a href="?pg=3g" class="_excel">Reportes en excel</a></li>
								<?php
							} elseif ($_SESSION["U_NIVEL"] == 3) {
								?>
								<li><a href="?pg=3a" class="_diario">Reporte Diario</a></li>
    								<li><a href="?pg=3b" class="_fechas">Reporte por Fechas</a></li>
    								<li><a href="?pg=3c" class="_visitas">Lista Cobranza</a></li>
								<li><a href="?pg=3f" class="_visitas">Reporte Recargos</a></li>
								<?php
							}
							break;
						case 4:
							?>         
							<li><a href="?pg=4" class="_usuario">Informaci&oacute;n personal</a></li>
							<?php
							if($_SESSION["U_NIVEL"] == 0){
								?>
								<li><a href="?pg=4a" class="_todos">Lista de usuarios</a></li>
								<li><a href="?pg=4b" class="_agregar">Agregar usuario</a></li>
								<li><a href="?pg=4c" class="_backup">Respaldar Base de Datos</a></li>
								<?php
							}
							break;
						default:
							if ($_SESSION["U_NIVEL"] == 0) {
								?>         
								<li><a href="?pg=2a" class="_buscar">Buscar cliente</a></li>
								<li><a href="?pg=2" class="_todos">Todos los clientes</a></li>
								<li><a href="?pg=2cb" class="_activos">Clientes activos</a></li>
								<li><a href="?pg=2c" class="_morosos">Clientes vencidos</a></li>
								<li><a href="?pg=2cc" class="_demanda">Clientes en demanda</a></li>
								<li><a href="?pg=2ca" class="_inactivos">Clientes inactivos</a></li>
								<li><a href="?pg=2d" class="_agregar">Agregar cliente</a></li>
								<?php
							} elseif ($_SESSION["U_NIVEL"] == 3) {
								?>         
								<li><a href="?pg=2a" class="_buscar">Buscar cliente</a></li>
								<li><a href="?pg=2" class="_todos">Mis clientes</a></li>
								<li><a href="?pg=2cb" class="_activos">Mis Clientes activos</a></li>
								<li><a href="?pg=2c" class="_morosos">Mis Clientes vencidos</a></li>
								<?php
							}
					}
					?>
				</ul>
			</div>
			<?php
			if(isset($_GET["pg"]) && $_GET["pg"] == "2e"){
			?>
				<div id="notas" class="sombra">
				<div id="n_title">Notas de cliente</div>
				<ul class="notas">
					<?php
					if(isset($_GET["cl"])){
						require_once("include/php/sys_db.class.php");
						require_once("include/conf/Config_con.php");
						$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
						//- OBTENER NUMERO DE CUENTA ACTUAL --
						$getCliente = (isset($_GET["cl"]))?$_GET["cl"]:"";
						$sql = "SELECT id FROM cuentas WHERE estado = 0 AND cliente = ".$getCliente."";
						$res = $db->query($sql);
						if($db->numRows() > 0)
						{
							unset($_SESSION["nohaycuenta"]);
							$cta = $db->fetchNextObject($res);
							$ncta = $cta->id;
							$sql = "SELECT * FROM notas WHERE cliente = $ncta ORDER BY id DESC";
							$res = $db->query($sql);
							if($db->numRows() > 0){
								while($nt = $db->fetchNextObject($res))
								{
									echo '	
									<li id="note_'.$nt->id.'">
										<input type="checkbox" class="checkboxnota" rel="'.$nt->id.'" />
										<span>'.$nt->fecha.'</span><br />
										'.$nt->nota.'
									</li>';
								}
							}else{
								echo 'No hay notas para este cliente.';
							}
						} else {
							$_SESSION["nohaycuenta"] = 1;
							echo 'Deba haber una cuenta para agregar notas';
						}
					}
					?>
				</ul>
				<input type="button" id="btnborranotas" value="Borrar notas seleccionadas" style="padding:2px 20px;font-size:12px;font-weight:700;" />
				</div>
				<?php
				}elseif(isset($_GET["pg"]) && $_GET["pg"] == "5"){
					?>
					<div id="notas" class="sombra">
					<div id="n_title">Reporte de Inversion</div>
					<ol>
					<?php
					require_once("include/php/sys_db.class.php");
					require_once("include/php/fun_global.php");
					require_once("include/conf/Config_con.php");
					$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
					$result = $db->query("SELECT *
						       FROM cuentas
						       WHERE estado = 0");
					while ($ln = $db->fetchNextObject($result)){
					//// Datos de las cuentas individuales ///// 
					//capital de cada cuenta
					$capital = $ln->capital;

					// obteniendo el interes neto basado en el interes bruto y el tiempo del prestamo
					$intNeto = ((($ln->interes*$ln->tiempo)/100)+1);
		
					// calculando el total del prestamo dado el tiempo, el interes y el capital
					$totPrestado = $ln->cantidad * (( ($ln->interes * $ln->tiempo) / 100 ) + 1);

					//calculando lo abonado a la cuenta dado el total del prestamo($totPrestado) restandole el saldo($ln->total)
					$abono = ($totPrestado - $ln->total);

					//Teniendo el abono ya se puede calcular cuanto corresponde al capital
					$aboCap = ($abono/$intNeto);
			
					//Ahora calculo lo que le corresponde al interes
					$intPagado = ($abono - $aboCap);

					///////// <------- ACUMULADORES DE TOTALES -----> //////////////////////////////
					//Sumatoria del Capital
					$capitalNeto += $ln->cantidad;

					//acumulador del total con intereses prestado
					$capInteres += $totPrestado;
			
					// acumulador de los saldos equivale al interes + capital invertido
					$saldos += $ln->total;

					//Acumulador del capital abonado
					$capAbonado += $aboCap;

					//Acumulador del interes cobrado
					$intAbonado += $intPagado;	
				}
				//// Buscando informacion de demandas ///
				$db3 = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
				$demandas = $db3->query("SELECT 
						demandas.cliente_id,
						cuentas.id,  
						cuentas.cliente,
						cuentas.cantidad,
						cuentas.interes,
						cuentas.tiempo, 
						cuentas.total 
					FROM 
						demandas, 
						cuentas 
					WHERE 
						demandas.cliente_id = cuentas.cliente
					GROUP BY
						cuentas.id;");
				while ($demandados = $db3->fetchNextObject($demandas)){
					//// Datos de las cuentas individuales ///// 
					//capital de cada cuenta
					$capital = $ln->capital;

					// obteniendo el interes neto basado en el interes bruto y el tiempo del prestamo
					$dintNeto = ((($demandados->interes*$demandados->tiempo)/100)+1);
					//echo $dintNeto."<br>";
					// calculando el total del prestamo dado el tiempo, el interes y el capital
					$dtotPrestado = $demandados->cantidad * (( ($demandados->interes * $demandados->tiempo) / 100 ) + 1);
					//echo $dtotPrestado."<br>";

					//calculando lo abonado a la cuenta dado el total del prestamo($totPrestado) restandole el saldo($ln->total)
					$dabono = ($dtotPrestado - $demandados->total);
					//echo $dabono."<br>";
					//Teniendo el abono ya se puede calcular cuanto corresponde al capital
					$daboCap = ($dabono/$dintNeto);
					//echo $daboCap."<br>";
					//Ahora calculo lo que le corresponde al interes
					$dintPagado = ($dabono - $daboCap);
					//echo $dintPagado."<br>";
					//echo "*********************************************************.<br>";
					///////// <------- ACUMULADORES DE TOTALES -----> //////////////////////////////
					//Sumatoria del Capital
					$dcapitalNeto += $demandados->cantidad;

					//acumulador del total con intereses prestado
					$dcapInteres += $dtotPrestado;
			
					// acumulador de los saldos equivale al interes + capital invertido
					$dsaldos += $demandados->total;

					//Acumulador del capital abonado
					$dcapAbonado += $daboCap;

					//Acumulador del interes cobrado
					$dintAbonado += $dintPagado;	
				}
				$totDemandado = ($dcapitalNeto - $dcapAbonado);
				echo "Capital Invertido: $"; echo number_format(($capitalNeto - $capAbonado)-($totDemandado), 2). "</br>";
				echo "Capital + Interes: $"; echo number_format(($saldos - $dsaldos), 2). "</br>";
				echo "Captital Recuperado: $"; echo number_format($capAbonado, 2). "</br>";
				echo "Interes Cobrado: $"; echo number_format($intAbonado, 2)."<br>"."<br>";
				echo "****Montos en demandas****"."<br/>";
				echo "Capital Demanda: $"; echo number_format($totDemandado, 2). "<br>";
				echo "Capital + Interes: $"; echo number_format($dsaldos, 2). "</br>";
				echo "Captital Recuperado: $"; echo number_format($dcapAbonado, 2). "</br>";
				echo "Interes Cobrado: $"; echo number_format($dintAbonado, 2)."<br>"."<br>";		
				?>
				</div>
				<?php
			}
			?>
		</div>
		<div id="contenedor">
			<div id="hmenu">
				<ul class="menuh">
				<?php
					if ($_SESSION["U_NIVEL"] == 0) {
						?>
							<li><a href="?pg=1">Portada</a></li>
							<li><a href="?pg=2">Clientes</a></li>
							<li><a href="?pg=3a">Reportes</a></li>
							<li><a href="?pg=5">Inversionistas</a></li>
							<li><a href="?pg=4">Panel</a></li>
							<li><a href="?logout=1">Salir</a></li>
						<?php
					} elseif($_SESSION["U_NIVEL"] == 3) {
						?>
							<li><a href="?pg=1">Portada</a></li>
							<li><a href="?pg=4">Mi cuenta</a></li>
							<li><a href="?pg=3a">Reportes</a></li>
							<li><a href="?logout=1">Salir</a></li>
						<?php
					}
					
				?>
				</ul>
			</div>
			<div id="divalert">
				<div id="ALERTAS">
					<?php
					if (($tipo_login != 3) and ($user->is_loaded())){
						if ($_SESSION["hola"] == 0){
							$_SESSION["hola"] = 1;
							?>
							<p class="note">Hola <?php echo $user->userData[6] . ((isset($_SESSION["bienvenida"]))?$_SESSION["bienvenida"]:""); ?>, bienvenido.</p>
							<?php
						}
					}
					echo (isset($loginmsg))?$loginmsg:'';
					?>
				</div>
			</div>
			<div id="contenido">
				<?php
				$pg=(isset($_GET["pg"]))?$_GET["pg"]:"";
				switch($pg){
					case "2":	require_once("include/html/pg_clientes.php");				break;
					case "2a":	require_once("include/html/pg_clientes_buscar.php");			break;
					case "2b":	require_once("include/html/pg_clientes_editar.php");			break;
					case "2c":	require_once("include/html/pg_clientes_morosos.php");			break;
					case "2ca":	require_once("include/html/pg_clientes_inactivos.php");			break;
					case "2cb":	require_once("include/html/pg_clientes_activos.php");			break;
					case "2cc":	require_once("include/html/pg_clientes_demandas.php");			break;
					case "2ba":	require_once("include/html/pg_clientes_editar2.php");			break;
					case "2bc":	require_once("include/html/pg_clientes_editar3.php");			break;
					case "2bd":	require_once("include/html/pg_clientes_editar4.php");			break;
					case "2d":	require_once("include/html/pg_clientes_agregar.php");			break;
					case "2da":	require_once("include/html/pg_clientes_agregar2.php");			break;
					case "2db":	require_once("include/html/pg_clientes_agregar3.php");			break;
					case "2dc":	require_once("include/html/pg_clientes_agregar4.php");			break;
					case "2e":	require_once("include/html/pg_cliente_cuenta.php");			break;
					case "3":	require_once("include/html/pg_reportes.php");				break;
					case "3a":	require_once("include/html/pg_reporte_diario.php");			break;
					case "3e":	require_once("include/html/pg_elimina_pago.php");			break;
					case "3b":	require_once("include/html/pg_reporte_fechas.php");			break;
					case "3c":	require_once("include/html/pg_reporte_cobranza.php");			break;
					case "3d":	require_once("include/html/pg_reporte_historial_cred.php");		break;
					case "3da":	require_once("include/html/pg_reporte_historial_cred_cl.php");		break;
					case "3f":	require_once("include/html/pg_reporte_recargos.php");			break;
					case "3g":	require_once("include/html/pg_reporte_excel.php");			break;
					case "4":	require_once("include/html/pg_panel.php");				break;
					case "4a":	require_once("include/html/pg_panel_usuarios.php");			break;
					case "4b":	require_once("include/html/pg_panel_usuario_agregar.php");		break;
                			case "4c":	require_once("include/html/pg_panel_db_backup.php");			break;
					case "4d":	require_once("include/html/pg_panel_prestamos.php");			break;
					case "5":	require_once("include/html/pg_reporte_inversionistas.php");		break;
					default:	//--
							if($user->is_loaded()){
								require_once("include/html/pg_sys_principal.php");
							} else {
								require_once("include/html/pg_sys_inicio.php");
							}
				}
				?>
			
			</div>
		</div>
		<div class="clear"></div>
</div>
</body>
</html>
