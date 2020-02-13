<?php

require("get_email.php");
require_once("lib/ComposerLibs/vendor/autoload.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$login_demandeur = $_POST['login_demandeur'];
$objet = $_POST['objet'];
$corps = $_POST['notes'];


$email = new GetEmail();
$get_email = $email->getEmail($login_demandeur);

$mail = new PHPMailer(true);
try
{
    error_log("----- BLOC TRY DU MAILER -----");
    //Recipients
    $mail->SMTPDebug = 2;
    $mail->setFrom('gestion@asalee.fr', 'Portail PSA');
    $mail->addAddress($get_email);

    //Content
    $mail->isHTML(true);
    $mail->Subject = $objet;
    $mail->Body    = $corps;

    $mail->send();
    error_log("----- MAIL SENT -----");
}
catch (Exception $e)
{
    error_log("----- ERROR -----\n" . $e->getMessage());
    error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
}



