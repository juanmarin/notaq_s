<?php
$destinatario = "jmarincastro@gmail.com"; 
$asunto = "Cronjob from confianzp"; 
$cuerpo = "Cronjob Cada Hora";


//Envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//Dirección del remitente 
$headers .= "From: CronJob< confian1@confianzp.com>\r\n"; 

//Dirección de respuesta (Puede ser una diferente a la de pepito@mydomain.com)
$headers .= "Reply-To: confian1@confianzp.com\r\n"; 

//Ruta del mensaje desde origen a destino 
//$headers .= "Return-path: soporte@wapp.mx\r\n"; 

//direcciones que recibián copia 
$headers .= "Cc: alfonso.nv@gmail.com\r\n"; 

//Direcciones que recibirán copia oculta 
//$headers .= "Bcc: alfonso.nuva@gmail.com\r\n"; 

mail($destinatario,$asunto,$cuerpo,$headers);
/*if(mail) {
	echo "Email enviado correctamente";
	}else {
	echo "Error al enviar email";
	}
*/
?>
