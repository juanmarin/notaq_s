<?php
session_start();
//header('Content-Type: text/html; charset=UTF-8'); 
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<title>Pagare Preview</title>
	<style type="text/css">
		/*** INICIO FRAME ***/
		#contenedor{width:795px;margin:auto;}
		.con_barra{width:800px;}
		#con_ta{width:49px;height:49px;background:url(frm_topleft.jpg);float:left;}
		#con_tb{width:702px;height:29px;padding-top:20px;background:url(frm_topcenter.jpg);float:left;}
		#con_tb p.titulo{font-size:16px;}
		#con_tc{width:49px;height:49px;background:url(frm_topright.jpg);float:left;}

		#con_ma{width:800px;background:url(frm_centerleft.jpg) left repeat-y;}
		#con_mb{width:702px;padding-right:49px;float:right;background:url(frm_centerright.jpg) right repeat-y;}

		#con_ba{width:49px;height:49px;background:url(frm_bottomleft.jpg);float:left;}
		#con_bb{width:702px;height:49px;background:url(frm_bottomcenter.jpg);float:left;}
		#con_bc{width:49px;height:49px;background:url(frm_bottomright.jpg);float:left;}
		/*** FINAL  FRAME ***/
		
		.clear{clear:both;}
		body{font-family:sans-serif;font-size:12px;color:#000;}
		em{font-weight:bold;text-transform:uppercase;}
		.strong{text-transform:uppercase;font-weight: normal;}
		p{line-height:23px;text-align:justify;}
		p.titulo{font-weight:bold;text-decoration:underline;text-align:center;}
		#encabezado{width:702px;font-weight:bold;margin-top:30px;margin-bottom:20px;}
		.fechahora{float:left;width:315px;}
		.numeohoja{float:left;width:72px;text-align:center;}
		.bueno_por{float:left;width:315px;text-align:right;}
		 li{text-align:justify;}
	</style>
	<style media="print" type="text/css">       
		#imprimir {visibility:hidden;margin:auto;}
	</style>
	<script language="JavaScript">
	function imprime(){
		window.print();
		document.location.href='../?pg=2e&cl=<?php echo $_POST["cl"]; ?>';
	}
	</script>
</head>

<?php
function getFechaLarga(){
	$dia = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$mes = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiempre", "Octubre", "Noviembre", "Diciembre");
	echo $dia[date("w")].", ".date("j")." de ".$mes[date("n")]." del ".date("Y");
}

function getFecha2($fecha, $show = 1){
		#OBTENER FECHA DESMEMBRADA 
		$diasem = date("w", strtotime($fecha));
		$d = substr($fecha, -2);
		$m = (int)substr($fecha, 5, -3);
		$a = substr($fecha, 0, -6);
		#DEFINIR CADENAS 
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("X", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiempre", "Octubre", "Noviembre", "Diciembre");
		if($show == 1){
			echo $dias[$diasem] . ", " . $d . " de " . $meses[$m] . " del " . $a;
		}else {
			return $dias[$diasem] . ", " . $d . " de " . $meses[$m] . " del " . $a;
		}
	}

function moneda2($num){
	return number_format($num, 2, ".", ",");
}


// END FUNCTIONS

require_once("../include/php/fun_global.php"); 
require_once("../include/php/sys_db.class.php");
require_once("../include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);

## INICIANDO VARIABLES ---
$cuenta = $_POST["c"];
$nota1 = $_POST["nota1"];
$nota2 = $_POST["nota2"];
$nota3 = $_POST["nota3"];
$fecha = date("Y-m-d");

## INFORMACION DE LA CUENTA
$sql = "SELECT * FROM cuentas WHERE id = ".$cuenta;
$res = $db->query($sql);
$c = $db->fetchNextObject($res); 
$cliente = $c->cliente;
$saldo = $c->total;
$cantidad = $c->cantidad;
$interes = $c->interes;
$meses = $c->tiempo;
$dias_p = $c->dias_pago;
$dp = $c->fecha;
$tp = $c->tipo_pago;
$total = $cantidad * ((($interes * $meses) / 100) + 1);
$multa = ($cantidad * 0.10);
$fec_plazo = date("Y-m-d", strtotime("$fecha +1 month"));

if($c->tipo_pago == 1) {
	$tipo_credito = "SEMANAL";
}elseif($c->tipo_pago == 2) {
	$tipo_credito = "CATORCENA";
}elseif($c->tipo_pago == 3) {
	$tipo_credito = "QUINCENAL";
}else {
	$tipo_credito = "MENSUAL";
}

## INFORMACION DEL CLIENTE --
$res = $db->query("SELECT * FROM clientes WHERE id = ".$cliente." LIMIT 0, 1");
$c = $db->fetchNextObject($res); 
$nombre = $c->nombre." ".$c->apellidop." ".$c->apellidom;
$ref1 = $c->R1nombre." ".$c->R1apellidop." ".$c->R1apellidom;
$ref2 = $c->R2nombre." ".$c->R2apellidop." ".$c->R2apellidom;
$ref3 = $c->R3nombre." ".$c->R3apellidop." ".$c->R3apellidom;

$aval1 = $c->Aval;
$aval2 = $c->Aval2;
$aval3 = $c->Aval3;

if($aval1 == 1) {
    $verbo = "Debemos y Pagaremos";
	$verbo2 = "nuestra";
}else {
    $verbo = "Debo y Pagare";
	$verbo2 = "mi";
}

## DIA DEL PRIMER PAGO --
$sql = "SELECT * FROM pagos WHERE cuenta = ".$cuenta ." ORDER BY id ASC";
$res = $db->query($sql);
$c = $db->fetchNextObject($res); 
$pp = $c->fecha;
?>

<body>
	<div id="contenedor">
		<div class="con_barra">
			<div id="con_ta"></div>
			<div id="con_tb">
				<p class="titulo">PAGAR&Eacute;</p>
			</div>
			<div id="con_tc"></div>
			<div class="clear"></div>
		</div>
		<div class="con_barra">
			<div id="con_ma">
				<div id="con_mb">
					<!-- INICIO CONTENIDO DEL SITIO -->
					<div id="encabezado">
						<div class="fechahora">	&nbsp;								</div>
						<div class="numeohoja">	N&uacute;mero 1/1						</div>
						<div class="bueno_por">	Bueno por: $ <?php echo($tipo_credito == "MENSUAL")? moneda($cantidad*2) : moneda($total); ?>	</div>
						<div class="clear">										</div>
					</div>

					<p style="font-weight:bold;">Hermosillo, Sonora, a <? echo getFechaLarga($fecha); ?></p>
					<p>Por este Pagare, <?php echo $verbo; ?> incondicionalmente a la orden de <em>Jose Alfredo Perez Lopez</em>, a su vencimiento el dia
					<? echo getFecha2($fec_plazo); ?>. Lugar  de  pago:  Hermosillo,   Sonora la cantidad   de  (<?php echo($tipo_credito == "MENSUAL")? numtoletras($cantidad*2) : numtoletras($total); ?>).  Valor
					recibido a <?php echo $verbo2; ?> entera satisfaccion. Este pagare es unico y causara intereses moratiorios al tipo de 10% mensual,  pagadero  en
					esta ciudad. Nombre del suscriptor: <em><? echo $nombre; ?> </em>.</p>
					
					<?php
					if($aval1 == 0){	
					?>
					<div style="width:100%;">
					<div style="width:32%;text-align:center;margin-left:33%;">
					<em>FIRMA DEL DEUDOR</em> <br />  <br />
					_____________________________ <br />
					<em><?php echo $nombre; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<br />

					<?php
					}elseif($aval1 =! 0 && $aval2 == 0){
					?>
					<div style="width:100%;">
					<div style="width:32%;text-align:center;margin-left:33%;">
					<em>FIRMA DEL DEUDOR</em> <br />  <br />
					_____________________________ <br />
					<em><?php echo $nombre; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<br />
					<br />					   
					<div style="width:100%;">
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br /> <br />
					_____________________________ <br />
					<em><?php echo $ref1; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<?php
					}elseif($aval1 =! 0 && $aval2 =!0 && $aval3 == 0){
					?>  
					<div style="width:100%;">
					<div style="width:32%;text-align:center;margin-left:33%;">
					<em>FIRMA DEL DEUDOR</em> <br />  <br />
					_____________________________ <br />
					<em><?php echo $nombre; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<br />					   
					<div style="width:100%;">
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br /> <br />
					_____________________________ <br />
					<em> <?php echo $ref1; ?></em>
					</div>
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br /> <br />
					_____________________________ <br />
					<em><?php echo $ref2; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<?php
					}elseif($aval1 =!0 && $aval2 =!0 && $aval3 =!0){// Por ultimo si las 3 variables son distintas de 0 entonces tenemos 3 avales	
					?> 
					<div style="width:100%;">
					<div style="width:32%;text-align:center;margin-left:33%;">
					<em>FIRMA DEL DEUDOR</em> <br />  <br />
					_____________________________ <br />
					<em> <?php echo $nombre; ?></em>
					</div>
					<div class="clear"></div>
					</div> 
					<br />  
					<div style="width:100%;">
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br /> <br />
					_____________________________ <br />
					<em><?php echo $ref1; ?></em>
					</div>
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br />   <br />
					_____________________________ <br />
					<em>	<?php echo $ref2; ?></em>
					</div>
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br />  <br />
					_____________________________ <br />
					<em>	<?php echo $ref3; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<?php
					}
					?>

					<p class="titulo"><em>DATOS DEL PRESTAMO</em></p>
					<ul>
						<li>CANTIDAD SOLICITADA:<em> $ <?php echo moneda($cantidad); ?></em></li>
						<li>DEUDA TOTAL:<em> $ <?php echo($tipo_credito == "MENSUAL")? moneda($cantidad*2) : moneda($total); ?></em></li>
						<li>PLAZO DEL CREDITO:<em> <?php echo $meses; ?> meses.</em></li>
						<li>DIAS DE PAGO:<em> <?php if($tp < 4){getDiaSemana($dias_p, $tp);}else{echo 'Días '.$dias_p.' de cada mes.';} ?></em></li>
						<li>1ER PAGO:<em> <?php echo getFecha($pp); ?></em></li>
						<li>VALOR DE CADA MULTA:<em> $ <?php echo moneda2($multa); ?></em></li>				
					</ul>
					<br>
					
					<p class="titulo">*** NOTAS IMPORTANTES FAVOR DE LEERLAS ***</p>
					<ul>
						
                                                
         <li>En caso de tres o m&aacute; pagos sin abonar el siguiente pagare pasara a <strong>Demanda Judicial,</strong> en el Poder Judicial del Estado de Sonora. </li>
         <li>El prestamo personal que ha solicitado causar&aacute; un <strong>inter&eacute;s ordinario o multa a razon del 10% </strong> si no abona el dia establecido y que en catidad
             lo es de <strong> $ <?php echo moneda2($multa); ?></strong></li>
         <li>Usted tiene el derecho de que al momento de liquidar su cuenta se le haga un <strong>50% de descuento</strong> en los intereses siempre y cuando <strong>no haya tenido mas de tres multas</strong></li>
         <li><strong>ACEPTO</strong> las condiciones del presente Cr&eacute;dito personal que he solicitado como: <strong>La Deuda Total,
						El Plazo, Los Dias de Pagos, as&iacute; como las Multas</strong> que generaria si no pagara en el el dia que me toque 
						dar el abono. Quedando enterado de los alcances juridicos que implica el dejar de pagar el credito solicitado.</li>	
						<?php echo(isset($_POST["nota1"]) && $_POST["nota1"] != '')? '<li><em></em> '.$_POST["nota1"].'</li>' : ''; ?>
						<?php echo(isset($_POST["nota2"]) && $_POST["nota2"] != '')? '<li><em></em> '.$_POST["nota2"].'</li>' : ''; ?>
						<?php echo(isset($_POST["nota3"]) && $_POST["nota3"] != '')? '<li><em></em> '.$_POST["nota3"].'</li>' : ''; ?>
					</ul>
					<br>		
       	<?php
					if($aval1 == 0){	
					?>
					<div style="width:100%;">
					<div style="width:32%;text-align:center;margin-left:33%;">
					<em>FIRMA DEL DEUDOR</em> <br />  <br />
					_____________________________ <br />
					<em><?php echo $nombre; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<br />

					<?php
					}elseif($aval1 =! 0 && $aval2 == 0){
					?>
					<div style="width:100%;">
					<div style="width:32%;text-align:center;margin-left:33%;">
					<em>FIRMA DEL DEUDOR</em> <br />  <br />
					_____________________________ <br />
					<em><?php echo $nombre; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<br />
					<br />					   
					<div style="width:100%;">
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br /> <br />
					_____________________________ <br />
					<em><?php echo $ref1; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<?php
					}elseif($aval1 =! 0 && $aval2 =!0 && $aval3 == 0){
					?>  
					<div style="width:100%;">
					<div style="width:32%;text-align:center;margin-left:33%;">
					<em>FIRMA DEL DEUDOR</em> <br />  <br />
					_____________________________ <br />
					<em><?php echo $nombre; ?></em>>
					</div>
					<div class="clear"></div>
					</div>
					<br />					   
					<div style="width:100%;">
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br /> <br />
					_____________________________ <br />
					<em> <?php echo $ref1; ?></em>
					</div>
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br /> <br />
					_____________________________ <br />
					<em><?php echo $ref2; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<?php
					}elseif($aval1 =!0 && $aval2 =!0 && $aval3 =!0){// Por ultimo si las 3 variables son distintas de 0 entonces tenemos 3 avales	
					?> 
					<div style="width:100%;">
					<div style="width:32%;text-align:center;margin-left:33%;">
					<em>FIRMA DEL DEUDOR</em> <br />  <br />
					_____________________________ <br />
					<em> <?php echo $nombre; ?></em>
					</div>
					<div class="clear"></div>
					</div> 
					<br />  
					<div style="width:100%;">
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br /> <br />
					_____________________________ <br />
					<em><?php echo $ref1; ?></em>
					</div>
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br />   <br />
					_____________________________ <br />
					<em>	<?php echo $ref2; ?></em>
					</div>
					<div style="width:32%;text-align:center;float:left;">
					FIRMA AVAL   <br />  <br />
					_____________________________ <br />
					<em>	<?php echo $ref3; ?></em>
					</div>
					<div class="clear"></div>
					</div>
					<?php
					}
					?>
      
      

					<!-- FINAL CONTENIDO DEL SITIO -->
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="con_barra">
			<div id="con_ba"></div>
			<div id="con_bb"></div>
			<div id="con_bc"></div>
			<div class="clear"></div>
		</div>
		
	</div>
	<div id="imprimir" style="text-align:center;">
	<input type="button" name="imprimir" value="Imprimir" onclick="imprime();">
	</div>
	</body>
</html>
