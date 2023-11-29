<?php
/*
    Importamos las dependencias de PHPMailer
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
    $mail->Username ='concesionarioconce@gmail.com'; // Cambia esto
    $mail->Password = 'foad dfhp viwj vhmo'; //  // 'conce200#'
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->Timeout = 5;

    $mail->setFrom('concesionarioconce@gmail.com');
    $mail->addAddress($_SESSION['email']); // Cambia esto al destinatario real

    $mail->isHTML(false);
    $mail->Subject = "Inicio de sesion con tu cuenta";
    $mail->Body = "Se ha iniciado sesión en tu cuenta.";

    $mail->send();
    echo 'Correo enviado con éxito';
} catch (Exception $exc) {
    echo 'Error al enviar el correo. Detalles: ' . $exc->getMessage();
}

