<?php
require_once("fun_global.php");

    $my_file = "correlacion.pdf";
    $my_path = "/cortes/";
    $my_name = "Corte Caja";
    $my_mail = "jmarincastro@gmail.com";
    $my_replyto = "jmarincastro@gmail.com";
    $my_subject = "Test email attachments.";
    $my_message = "Hallo,\r\ndo you like this script? I hope it will help.\r\n\r\ngr. Olaf";

    mail_attachment($my_file, $my_path, "recipient@mail.org", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);

?>
