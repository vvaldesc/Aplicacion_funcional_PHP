<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/PHPMailer/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/PHPMailer/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/PHPMailer/SMTP.php';
if (!function_exists('enviarMail')) {

    function enviarMail($mailDeDni) {

        try {

            //Vamos a enviar mail a desti

            $mail = new PHPMailer(true); //Objeto de la clase PHPMailer

            $mail->isSMTP();
            $mail->Host = 'smtp.yopmail.com'; //Tipo de host: gmail en este caso
            $mail->SMTPAuth = true; //Autentificación activada


            $mail->Username = $mailDeDni; //Tu gmail
            $mail->Password = ''; //Tu contraseña de aplicación de gamil
            $mail->SMTPSecure = 'tls'; //Tipo de seguridad
            $mail->Port = 587; //Puerto de smtp
            $mail->Timeout = 5;

            $mail->setFrom('concesionario@yopmail.com'); //Gmail desde el que se envía el mensaje

            $mail->addAddress($mailDeDni); //El email que recibe el correo

            $mail->isHTML(false); //El mensaje enviado es HTML

            $mail->Subject = "Inicio de sesión con tu cuenta"; //Asunto del mensaje
            $mail->Body = "Inicio de sesión en concsionario"; //Cuerpo del mensaje                    
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }



    }

}
//No se si esto es necesario - Víctor
if (!function_exists('mensajeError')) {

    function mensajeError($message) {

        return '<nav class="navbar bg-body-tertiary bg-danger rounded m-2">
            <div class="container-fluid">
                <p>
                    ' . $message . '
                </p>
            </div>
        </nav>';
    }

}
if (!function_exists('generaToken')) {

    function generaToken(&$token, $session_id) {
        $hora = date('H:i');
        $token = hash('sha256', $hora . $session_id);
    }

}
