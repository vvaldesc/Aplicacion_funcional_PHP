<?php
/*
require  $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require_once $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/vendor/phpmailer/phpmailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/vendor/phpmailer/phpmailer/src/SMTP.php';
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

$mail = new PHPMailer(true); //Objeto de la clase PHPMailer
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'concesionarioconce@gmail.com'; // Cambia esto
    $mail->Password = 'conce200#'; // Cambia esto
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->Timeout = 5;

    $mail->setFrom('concesionarioconce@gmail.com');
    $mail->addAddress('javier.diazmolina@riberadeltajo.es'); // Cambia esto al destinatario real

    $mail->isHTML(false);
    $mail->Subject = "Inicio de sesión con tu cuenta";
    $mail->Body = "Inicio de sesión en concesionario";

    $mail->send();
    echo 'Correo enviado con éxito';
} catch (Exception $exc) {
    echo 'Error al enviar el correo. Detalles: ' . $exc->getMessage();
}

