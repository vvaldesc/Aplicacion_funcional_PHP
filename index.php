<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/styleLinks.php' ?>
        <title></title>
    </head>
    <body>
       <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
     
        <div class="container mt-4">
            <!-- container -->

            <?php
            
            include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
            include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/funciones.php';
            
            session_start();
            
            generaToken($_SESSION['token'],session_id());

            //comprobar credenciales y token y si no, error
            //el formulario te llevaría a homepage

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_POST["pass"]) && isset($_POST["usr"]) ) {
                    
                    if($_POST["pass"]!='' && $_POST["usr"]!=''){
                        
                        try {
                            $BD = conexionPDO();
                            //Sentencia SQL
                            $sql="SELECT * FROM vendedores WHERE DNI = '".$_POST['usr']."';";
                            // AL SER USUARIO CLAVE UNICA LA PRIMERA CONDICIÓN ES PRÁCTICAMENTE INNECESARIA
                            // ESTO NO LO HE COMPROBADO TODAVÍA
                            $contrasena= hash('sha256', $_POST['pass']);
                            $tabla= extraerTablas($sql);
                            if (count($tabla) == 1 && $tabla[0][6]==$contrasena) {
                               
                                $_SESSION['rol']=$tabla[0][5];
                                $_SESSION['name']=$tabla[0][1];
                                $_SESSION['apellidos']=$tabla[0][2];
                                $_SESSION['email']=$tabla[0][7];
                                
                                //variable manual (CUIDADO)
                                enviarMail($_SESSION['email']="concesionarioconce@gmail.com");
                                                                
                                header('Location: ./pages/homepage.php');
                            } else {
                                echo mensajeError("La contraseña o el usuario no es correcto o BD no creada");
                            }
                        } catch (Exception $exc) {
                            echo $exc->getTraceAsString();
                        }
                                        }else{
                        echo mensajeError("Formulario no rellenado");
                    }
                }
            }
            
            //Esta funcion no es muy acertada para implementar el boton crearBD
            comprobarBD();
            ?>
            

            <h1 class="text-center">Inicio de sesión</h1>

            <form method="POST" class="text-center mt-4 p-4 border d-flex align-items-center flex-column" style="width: 400px; margin: auto" action='<?php $_SERVER["PHP_SELF"] ?>'>

                <div class="form-group m-2" style="width: 300px">
                    <label for="usr">Usuario</label>
                    <input type="text" class="form-control" id="usr" name="usr" placeholder="Usuario">
                    <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>"> 
                </div>
                <div class="form-group m-2" style="width: 300px">
                    <label for="pass">Contraseña</label>
                    <input type="text" class="form-control" id="pass" name="pass" placeholder="Contraseña">
                </div>
                    <!--<input type="hidden" class="form-control" id="token" name="token" value="</*?= $_SESSION['token'] ?*/>">-->
                    <button type="submit" class="mt-3 btn btn-primary">Iniciar Sesión</button>
                    <button type="button" class="mt-3 btn btn-primary">Crear BD</button>
            </form>

        </div>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/footer.php' ?>
    </body>
</html>
