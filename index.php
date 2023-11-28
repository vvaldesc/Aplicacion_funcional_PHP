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
            
            if (isset($_POST['guardar'])) {
                // Check if the form is submitted
                crearBD();
            }
            
             
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
            //if (comprobarBD());
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
                    </form>
                    <?php
                        if (comprobarBD() === false) {
                            echo '<button class="btn btn-primary my-3" data-toggle="modal" data-target="#agregarCoche">Agregar Base de Datos</button>';
                            echo '<div class="modal fade" id="agregarCoche">
                                    <div class="modal-dialog">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <h5 class="modal-title">Crear BD</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>
                                              </div>
                                              <form method="POST" action="'.$_SERVER['PHP_SELF'].'">
                                                <div class="modal-body">
                                                    <!-- Agregar Nuevo coche-->
                                                    <h2>Usted no tiene la base de datos en su sistema. ¿Desea Guardarla?</h2>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    <input type="submit" name="guardar" class="btn btn-primary" value="Guardar">
                                                </div>
                                              </form>
                                          </div>
                                      </div>
                                  </div>';
                        }

                        

                    ?>

        </div>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/footer.php' ?>
        <!-- JavaScript y jQuery para habilitar los componentes de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>
