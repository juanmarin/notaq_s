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
$mail->Username = "juan.marin@confianzp.com";
$mail->Password = "V1ct0r1a";

$mail->From = "juan.marin@confianzp.com";
	$mail->FromName = "Juan Mairin";
	$mail->Subject = "Enviar Mail con PHPMailer desde local";
	$mail->AltBody = "";
	$mail->MsgHTML("<h1>Test Mail Messages Attached!</h1>");
$mail->AddAttachment("$attachment");
//$mail->AddAttachment("adjunto.txt");

$mail->AddAddress("jmarincastro34@gmail.com", "Juan Mairin");
$mail->IsHTML(true);
$mail->Send();
?>