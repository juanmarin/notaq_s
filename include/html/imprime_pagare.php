<?php
/*
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Pagare Preview</title>
         <style type="text/css">
            body{width:100%;margin:auto;min-width:600px;max-width:900px}
            
            em{
                text-decoration: underline;
                }
            
            #topcont{margin-top:20px;margin-bottom:-20px;padding:30px;
                    -moz-box-shadow: inset 0 10px 22px -15px #000;
                    -webkit-box-shadow: inset 0 10px 22px -15px #000;
                     box-shadow: inset 0 10px 22px -15px #000;}
                     
            #topizq
                {width: 250px; float:left;}
            #topmed
                {width: 200px; padding-top: 1px; padding-left: 90px; float:left;}
            #topder
                {float:right; padding-top:1px;}
                
            #cuerpo{
                width: 850px;
                margin-left: 20px;
                margin-top: 80px;
                text-align: justify;               
                }
         </style>
        <style media="print" type="text/css">       
            #imprimir 
                {visibility:hidden}
        </style>
    </head>

<?php
    $fecha = date("d-m-Y");
    $cantidad = 5000;
    $cliente = "Juan Carlos Marin Castro";
?>
    <body>
        <div id="topcont">
            <p style="text-align: center">PAGAR&Eacute;</p>
            <div id="fecha">
                <p id="topizq">Hermosillo, Sonora a <? echo $fecha; ?></p>
                <p id="topmed">N&uacute;mero 1/1</p>
                <p id="topder">Bueno por <?echo $cantidad; ?></p>
            </div>
        </div>
            <div id="cuerpo">
                <p>Por este Pagare, Debo y Pagare incondicionalmente a la orden de <em>Jose Afredo Perez Lopez</em>, a su vencimiento el dia <? echo $fecha; ?>.
                Lugar de pago: Hermosillo, Sonora la cantidad de (<? echo $cantidad; ?>).
                Valor recibido a mi entera satisfaccion. Este pagare es unico y causara intereses moratiorios al tipo de 10% mensual, pagadero en
                esta ciudad. Nombre del subscriptor: <strong><? echo $cliente; ?> </strong>.</p>
            </div>
       
<form id="form1" name="form1" method="post" action="">
<input type="button" name="imprimir" id="imprimir" value="Imprimir" onclick="window.print();" />
</form>
   
    </body>
</html>