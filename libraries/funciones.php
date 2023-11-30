<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
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

    function enviarMail() {
        
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
/**
 * Genera el token en funcion de fecha y la hora.
 * 
 * @param array $POST Array que contiene los datos del formulario login.
 * 
 */
if (!function_exists('generaToken')) {

    function generaToken(&$token, $session_id) {
        $hora = date('H:i');
        $token = hash('sha256', $hora . $session_id);
    }

}
/**
 * Comprueba si el usuario y contraseña son correctos para acceder a la aplicación.
 * 
 * @param array $POST Array que contiene los datos del formulario login.
 * 
 */
if (!function_exists('comprobarLogin')) {

    function comprobarLogin($post) {


        if (isset($post["pass"]) && isset($post["usr"])) {

            if ($post["pass"] != '' && $post["usr"] != '') {

                try {
                    $BD = conexionPDO();
                    //Sentencia SQL
                    $sql = "SELECT * FROM vendedores WHERE DNI = '" . $_POST['usr'] . "';";
                    // AL SER USUARIO CLAVE UNICA LA PRIMERA CONDICIÓN ES PRÁCTICAMENTE INNECESARIA
                    // ESTO NO LO HE COMPROBADO TODAVÍA
                    $contrasena = hash('sha256', $_POST['pass']);
                    $tabla = extraerTablas($sql);
                    if (count($tabla) == 1 && $tabla[0][6] == $contrasena) {

                        $_SESSION['rol'] = $tabla[0][5];
                        $_SESSION['name'] = $tabla[0][1];
                        $_SESSION['apellidos'] = $tabla[0][2];
                        $_SESSION['email'] = $tabla[0][7];

                        //variable manual (CUIDADO)
                        enviarMail();

                        header('Location: ./pages/homepage.php');
                    } else {
                        echo mensajeError("La contraseña o el usuario no es correcto");
                    }
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            } else {
                echo mensajeError("Formulario no rellenado");
            }
        }
    }

}
if(!function_exists('comprobarInicio')){
    function comprobarInicio($sesion){
        if(!$sesion['rol']){
            header('Location: ../index.php?login=false');
        }
    }
}

if(!function_exists('ultimaPalabra')){
    function ultimaPalabra($dato){
        $palabras = explode(' ', $dato);
        return end($palabras);
   }   
}

if(!function_exists('imprimirSelects')){
    function imprimirSelects($sql){
        $vendedores = extraerTablas($sql);
            foreach ($vendedores as $key => $value) {
                echo '<option value="' ;
                for($i=0;$i< count($vendedores[0])/2;$i++){
                    echo ' '.$value[$i];
                }
                echo  '">' ;
                for($i=0;$i< count($vendedores[0])/2;$i++){
                    echo ' '. $value[$i];
                }
                echo '</option>';
            }
        }   
}

if(!function_exists('cerrarSesion')){
    function cerrarSesion(&$sesion){
        $sesion=array();
        session_destroy();
        setcookie("nombreSesion",123,time()-1000,"/");
        setcookie("ultCone",123,time()-1000,"/");
    }
}
