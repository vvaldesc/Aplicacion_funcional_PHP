<?php



if (!function_exists('enviarMail')) {

    function enviarMail($mailDeDni) {
        
        //HAGO ESTO PARA QUE SE CARGUEN LAS DEPENDENCIAS DE PHP MAILER SOLO CUANDO HAYA QUE ENVIAR UN MAIL
        //DE CASO CONTRARIO SE CARGARÍAN CADA VEZ QUE SE USA UNA FUNCIÓN
        
        // Ruta al archivo PHP que deseas incluir
        $rutaPHPMailer = $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/enviarMailDependencias.php';

        // Verificar si el archivo existe antes de incluirlo
        if (file_exists($rutaPHPMailer)) {
            // Incluir el archivo
            include $rutaPHPMailer;
        } else {
            // Manejar el caso en que el archivo no existe
            echo 'El archivo no existe.';
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