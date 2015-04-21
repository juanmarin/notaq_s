<php
session_start();
header('Content-Type: text/html; charset=iso-8859-1'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
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

function numtoletras($xcifra)
{////// FUNCION CONVERTIR NUMEROS A LETRAS
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
{ // esta función regresa un subfijo para la cifra
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

##### Funcion para calcular fecha de vencimiento del pagare ######
function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0)
{

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
$dp = $c->fecha;
$tp = $c->tipo_pago;
$pp = $c->fecha_pago;
$total = $cantidad * ((($interes * $meses) / 100) + 1);
$multa = ($cantidad * 0.10);
$fec_plazo = dateadd($fecha,0,$meses,0,0,0,0);

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
					<p>Por este Pagare, Debo y Pagare incondicionalmente a la orden de <em>Jose Alfredo Perez Lopez</em>, a su vencimiento el dia
					<? echo getFecha2($fec_plazo); ?>. Lugar  de  pago:  Hermosillo,   Sonora la cantidad   de  (<?php echo($tipo_credito == "MENSUAL")? numtoletras($cantidad*2) : numtoletras($total); ?>).  Valor
					recibido a mi entera satisfaccion. Este pagare es unico y causara intereses moratiorios al tipo de 10% mensual,  pagadero  en
					esta ciudad. Nombre del subscriptor: <em><? echo $nombre; ?> </em>.</p>
					
					<div style="width:100%;">
						<div style="width:32%;text-align:center;margin-left:33%;">
          <em>FIRMA DEL DEUDOR</em> <br />  <br />
							_____________________________ <br />
							<?php echo $nombre; ?>
						</div>
						<div class="clear"></div>
					</div>
					<br />
					<div style="width:100%;">
						<div style="width:32%;text-align:center;float:left;">
						     FIRMA AVAL   <br /> <br />
							_____________________________ <br />
							<?php echo $ref1; ?>
						</div>
						<div style="width:32%;text-align:center;float:left;">
						     FIRMA AVAL   <br />   <br />
							_____________________________ <br />
							<?php echo $ref2; ?>
						</div>
						<div style="width:32%;text-align:center;float:left;">
						     FIRMA AVAL   <br />  <br />
							_____________________________ <br />
							<?php echo $ref3; ?>
						</div>
						<div class="clear"></div>
					</div>

					<p class="titulo"><em>DATOS DEL PRESTAMO</em></p>
					<ul>
						<li>CANTIDAD SOLICITADA:<em> $ <?php echo moneda($cantidad); ?></em></li>
						<li>DEUDA TOTAL:<em> $ <?php echo($tipo_credito == "MENSUAL")? moneda($cantidad*2) : moneda($total); ?></em></li>
						<li>PLAZO DEL CREDITO:<em> <?php echo $meses; ?> meses.</em></li>
						<li>DIAS DE PAGO:<em> <?php echo getDiaSemana2($dp, $tp); ?></em></li>
						<li>1ER PAGO:<em> <?php echo getFecha2($pp); ?></em></li>
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

					<div style="width:100%;">
						<div style="width:32%;text-align:center;margin-left:33%;">
						     <em>FIRMA DEL DEUDOR</em> <br />   <br />
							_____________________________ <br />
							<?php echo $nombre; ?>
						</div>
						<div class="clear"></div>
					</div>
					
					<br>
					
					<div style="width:100%;">
						<div style="width:32%;text-align:center;float:left;">
                                                       FIRMA AVAL  <br />   <br />
							_____________________________ <br />
							<?php echo $ref1; ?>

						</div>
						<div style="width:32%;text-align:center;float:left;">
						     FIRMA AVAL  <br />    <br />
							_____________________________ <br />
							<?php echo $ref2; ?>
						</div>
						<div style="width:32%;text-align:center;float:left;">
						     FIRMA AVAL  <br />     <br />
							_____________________________ <br />
							<?php echo $ref3; ?>
						</div>
						<div class="clear"></div>
					</div>

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
