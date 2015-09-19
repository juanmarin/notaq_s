<?php
require("class.phpmailer.php");
require("class.smtp.php");
/*
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->Username = "jmarincastro34@gmail.com";
$mail->Password = "kebbduzer";

$mail->From = "jmarincastro34@gmail.com";
	$mail->FromName = "Juan Mairin";
	$mail->Subject = "Enviando Mail con PHPMailer";
	$mail->AltBody = "";
	$mail->MsgHTML("<h1>Test mail message!</h1>");
$mail->AddAttachment("adjunto.txt");

$mail->AddAddress("jmarincastro@hotmail.com", "Nombre Destinatario");
$mail->IsHTML(true);
$mail->Send();
*/
///// SIN GMAIL.COM ////

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
//$mail->SMTPSecure = "ssl";
$mail->Host = "mail.confianzp.com";
$mail->Port = 25;
$mail->Username = "reportes@confianzp.com";
$mail->Password = "V1ct0r1a";

$mail->From = "reportes@confianzp.com";
	$mail->FromName = "Corte de Caja";
	$mail->Subject = "Reporte de cobros realizados al dia: date("d-m-Y")  ";
	$mail->AltBody = "";
	$mail->MsgHTML("<h1>Reporte de cobros realizados por cobrador: $cobrador !</h1>");
$mail->AddAttachment("$titulo");

$mail->AddAddress("jmarincastro34@gmail.com", "Juan Marin");
$mail->AddAddress("pacozozaya@gmail.com ", "Francisco Zozaya");
$mail->IsHTML(true);
if (!$mail->Send()) {
	echo "Error: No se pudo enviar el correo.
	Favor de intenar mas tarde";
}
?>
