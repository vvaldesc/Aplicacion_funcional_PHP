<?php
if (!function_exists('enviarMail')) {


/**
 * DESC:
 *      First array is supposed to be the POST superglobal variable
 *      wich has all the form info.
 *      Second array is supposed to be an indexed array of sting values wich
 *      form inputs don't have to be completed obligatory.
 * 
 *      This function checks if the indicated form inputs are different from "".
 *      It returns true if all obligatory inputs are completed.
 * 
 * @param type $datosForm
 * @param type $noClave
 * @return bool
 */
function checkForm($datosForm, $noClave = null) {//Recibe dos arrays, (asociativo,indexado)
    $bandera = true; // I assume that all essential parameters are filled

    $claves = array_keys($datosForm); // Store the keys of the associative array from the form (from the POST superglobal array)

    $i = 0;
            
    if (is_array($noClave) && $noClave!=null) { // If non essential array exists
        while ($bandera && $i < count($datosForm)) { // When $bandera is false, the loop ends, while verifying each form field's content
            $i2 = 0;
            while ($bandera && $i2 < count($noClave)) { // 'While' verifying that the form field does not match the array of non-essential form elements
                if ($claves[$i] != $noClave[$i2]) {
                    if ($datosForm[$claves[$i]] === "") { // Verification of the content of the form field
                        $bandera = false;
                    }
                }
                $i2++;
            }
            $i++;
        }
    } else{ // If essential array non't exists
        while ($bandera && $i < count($claves)) {
            if ($datosForm[$claves[$i]]==="") {
                $bandera = false;
            }
            $i++;
        }
    }
    
    return $bandera;
}

}
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