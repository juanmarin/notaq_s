<?php
require_once('AttachMailer.php'); 

##Obteniendo datos del cobrador para enviar el correo##
$sql = "SELECT nombre, email FROM mymvcdb_users WHERE username = '".$cobrador."'";
	$res = mysql_query($sql);
	$rec = mysql_fetch_array($res);
	$cob_mail = $rec['1'];
	$cob_name = $rec['0'];


##Arreglo contenedor de los destinatarios
#/*
 #*ARREGLO CONTENDERDOR DE DESTINATARIOs
 #* Agregar aqui si se desean mas destinos del correo
 #* "key"=>"email",
 #*/

	$destinos=array("uno"=>"pacozozaya@gmail.com","dos"=>"jmarincastro34@gmail.com","sup"=>"manuels.dominguez@gmail.com","cob"=>"$cob_mail");

/*
 * Ciclo para rrecorrer el arreglo completo agregar
 * Los destinatarios al correo
 */

foreach($destinos as $x => $x_value) {
	$mailer = new AttachMailer("reportes@confianzp.com", "$x_value", "Reporte de cobros realizados al dia", "<h1>Reporte de cobros realizados por cobrador:".$cob_name."!</h1>");
	$mailer->attachFile("$titulo");
		if (!$mailer->send()) {
			echo "Error: No se pudo enviar el correo.
			Favor de intenar mas tarde";
		}else{
			echo "Correo enviado satisfactoriamente";
		}
}

?>
