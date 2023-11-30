

<?php 
            include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/funciones.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(comprobarLogin($_POST)){
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
?>


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
            
            
            generaToken($_SESSION['token'],session_id());

            //comprobar credenciales y token y si no, error
            //el formulario te llevaría a homepage
            if(isset($_GET['login'])){
                if($_GET['login']=='false'){
                    echo mensajeError('No se ha iniciado sesión, o rol aun no asignado.');
                }
            }
            if (isset($_POST['guardar'])) {
                // Check if the form is submitted
                crearBD();
            }
            /*if ($_SERVER["REQUEST_METHOD"] == "POST") {
                comprobarLogin($_POST);
            }*/
            
            
            
            
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
                    <input type="password" class="form-control" id="pass" name="pass" placeholder="Contraseña">
                </div>
                    <!--<input type="hidden" class="form-control" id="token" name="token" value="</*?= $_SESSION['token'] ?*/>">-->
                    <button type="submit" class="mt-3 btn btn-primary">Iniciar Sesión</button>
                    </form>
                    <?php
                        if (comprobarBD() === false) {
                            echo '<button class="btn btn-primary my-3 mx-auto" data-toggle="modal" data-target="#agregarCoche">Agregar Base de Datos</button>';
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
