<?php 
    include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/funciones.php';
    session_start();
    $formError=comprobarCookieInicio($_POST,$_SESSION);
    if (isset($_POST['guardar'])) {
        //Aquí entra si hemos accedido a crear la BD
        crearBD();
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
        <div class="container mt-4">
            <?php
            generaToken($_SESSION['token'],session_id());
            //comprobar credenciales y token y si no, error
            //el formulario te llevaría a homepage
            ?>
            <h1 class="text-center">Inicio de sesión</h1>
            <form method="POST" class="text-center mt-4 p-4 border d-flex align-items-center flex-column" style="width: 400px; margin: auto" action='<?php $_SERVER["PHP_SELF"] ?>'>
                <div class="form-group m-2" style="width: 300px">
                    <label for="usr">Usuario</label>
                    <input value="<?= $formError ? $_POST["usr"] : "" ?>" type="text" class="form-control" id="usr" name="usr" placeholder="Usuario">
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
                    cearBDModal();
                }
            ?>
        </div>
        <!-- JavaScript y jQuery para habilitar los componentes de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>
