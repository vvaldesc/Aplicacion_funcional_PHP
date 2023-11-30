<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/libraries/conexionPDO.php';

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

        if (is_array($noClave) && $noClave != null) { // If non essential array exists
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
        } else { // If essential array non't exists
            while ($bandera && $i < count($claves)) {
                if ($datosForm[$claves[$i]] === "") {
                    $bandera = false;
                }
                $i++;
            }
        }

        return $bandera;
    }

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

//No se si esto es necesario - Víctor

    function mensajeError($message) {

        return '<nav class="navbar bg-body-tertiary bg-danger rounded m-2">
                <div class="container-fluid">
                    <p>
                        ' . $message . '
                    </p>
                </div>
            </nav>';
    }

/**
 * Genera el token en funcion de fecha y la hora.
 * 
 * @param array $POST Array que contiene los datos del formulario login.
 * 
 */
    function generaToken(&$token, $session_id) {
        $hora = date('H:i');
        $token = hash('sha256', $hora . $session_id);
    }

/**
 * Comprueba si el usuario y contraseña son correctos para acceder a la aplicación.
 * 
 * @param array $POST Array que contiene los datos del formulario login.
 * 
 */
    function comprobarLogin($post) {
        $valido = false;

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


                        $valido = true;

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
        return $valido;
    }

    function comprobarInicio($sesion) {
        if (!$sesion['rol']) {
            header('Location: ../index.php?login=false');
        }
    }

    function ultimaPalabra($dato) {
        $palabras = explode(' ', $dato);
        return end($palabras);
    }

    function imprimirSelects($sql) {
        $vendedores = extraerTablas($sql);
        foreach ($vendedores as $key => $value) {
            echo '<option value="';
            for ($i = 0; $i < count($vendedores[0]) / 2; $i++) {
                echo ' ' . $value[$i];
            }
            echo '">';
            for ($i = 0; $i < count($vendedores[0]) / 2; $i++) {
                echo ' ' . $value[$i];
            }
            echo '</option>';
        }
    }

    function cerrarSesion(&$sesion) {
        $sesion = array();
        session_destroy();
        setcookie("nombreSesion", 123, time() - 1000, "localhost");
        setcookie("ultCone", 123, time() - 1000, "localhost");
        setcookie("PHPSESSID", 123, time() - 1000, "localhost");
    }

    function comprobarCookie($session, $cookie){
        if(getcwd()!='C:\xampp\htdocs\Aplicacion_funcional_PHP'){
            comprobarInicio($session);
        }
        ;
        if(isset($session["name"]) && isset($session["apellidos"])){
            $nombreParaCookie=$session["name"];
            $apellidoParaCookie=$session["apellidos"];
            $nombreCompleto=$nombreParaCookie.' '.$apellidoParaCookie;
            $fechaActualObjeto = new DateTime();
            $fechaActualString = $fechaActualObjeto->format('Y-m-d H:i:s');
            setcookie("nombreSesion", $session["name"] . " " . $session["apellidos"], time() + 300, 'localhost'); //la cookie dura 5 minutos
            setcookie("ultCone", $fechaActualString , time() + 300, 'localhost');

            unset($nombreParaCookie);    unset($apellidoParaCookie);    unset($nombreCompleto);
            unset($fechaActualObjeto);    unset($fechaActualString);    unset($fechaActualString);
            if (!isset($cookie["ultCone"]) || isset($_GET["logOut"])) {
                cerrarSesion($session);
                if(getcwd()!='C:\xampp\htdocs\Aplicacion_funcional_PHP'){
                    echo 'hola';
                    header('Location:../index.php');
                }

            } else {
                //La cookie se actualiza, por tanto solo expira la sesión por inactividad
                setcookie("ultCone", date('Y-m-d H:i:s'), 300, '/'); //la cookie dura 10 minutos
            }
                        
        }
    }
//Valido la contraseña
    function validarContraseña($pass) {
        // Verificar longitud mínima
        if (strlen($pass) < 5) {
            return false;
        }

        // Verificar que contenga al menos una letra y un número
        if (!preg_match('/[A-Za-z]/', $pass) || !preg_match('/[0-9]/', $pass)) {
            return false;
        }

        // Todas las verificaciones pasaron, la contraseña es válida
        return true;
    }

    function validarDNI($dni) {
        // Eliminar posibles espacios en blanco al principio o al final
        $dni = trim($dni);

        // Verificar que el DNI tiene una longitud válida
        if (strlen($dni) !== 9) {
            return false;
        }

        // Extraer la letra y los números del DNI
        $letra = strtoupper(substr($dni, -1));
        $numeros = substr($dni, 0, -1);

        // Verificar que los números son válidos
        if (!is_numeric($numeros)) {
            return false;
        }

        // Calcular la letra esperada
        $letraEsperada = substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros % 23, 1);

        // Verificar que la letra sea correcta
        if ($letra !== $letraEsperada) {
            return false;
        }

        // Si todas las verificaciones pasaron, el DNI es válido
        return true;
    }
    
    function comprobarCookieInicio($post,$session){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(comprobarLogin($post)){
                $nombreParaCookie = $_SESSION["name"];
                $apellidoParaCookie = $_SESSION["apellidos"];
                $nombreCompleto = $nombreParaCookie . ' ' . $apellidoParaCookie;
                $fechaActualObjeto = new DateTime();
                $fechaActualString = $fechaActualObjeto->format('Y-m-d H:i:s');

                setcookie("nombreSesion", $nombreCompleto, time() + 300, 'localhost'); //la cookie dura 5 minutos
                setcookie("ultCone", $fechaActualString, time() + 300, 'localhost');

                unset($nombreParaCookie);
                unset($apellidoParaCookie);
                unset($nombreCompleto);
                unset($fechaActualObjeto);
                unset($fechaActualString);
                unset($fechaActualString); 
            }
        }
    }
    function imprimirTablas($tabla){
        for($i=0;$i< count($tabla);$i++){
            echo '<tr>
                     <td>'.$tabla[$i][0].'</td>
                     <td>'.$tabla[$i][1].'</td>
                     <td>'.$tabla[$i][2].'</td>
                     <td>'.$tabla[$i][3].'</td>
                     <td>'.$tabla[$i][4].'</td>
                     <td>'.$tabla[$i][5].'</td>
                     <td>'.$tabla[$i][7].'</td>
                     <td><a class="btn btn-primary border" href="#"><i class="fa-solid fa-pencil"></i></a><a class="btn btn-danger border" href="#"><i class="fa-solid fa-trash"></i></i></a></td>
                </tr>';
        }
    }