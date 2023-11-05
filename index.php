<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title></title>
    </head>
    <body>
        <div class="container mt-4">
        <!-- container -->
        
        <?php
        include './pages/conexionPDO.php';
        //comprobar credenciales y token y si no, error
        //el formulario te llevaría a homepage
        
        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            if(isset($_POST["pass"])){
                
                //si usr y pass son correctos...
                session_start();
                $_SESSION["usuario"] = $_POST["usr"];
                header('Location:pages/homepage.php');
            
                
            }else{
                header('Location:');
            }
        }
        
        conexionPDO();
        
        
        
        ?>
        
        <h1 class="text-center">Inicio de sesión</h1>

        <form method="POST" class="text-center mt-4 p-4 border d-flex align-items-center flex-column" style="width: 400px; margin: auto" action=<?php $_SERVER["PHP_SELF"] ?>>
            
            <div class="form-group m-2" style="width: 300px">
                <label for="usr">Usuario</label>
                <input type="text" class="form-control" id="usr" name="usr" placeholder="Usuario">
            </div>
            <div class="form-group m-2" style="width: 300px">
                <label for="pass">Contraseña</label>
                <input type="text" class="form-control" id="pass" name="pass" placeholder="Contraseña">
            </div>

        <button type="submit" class="btn btn-primary">Submit</button>

        </form>
            
        </div>

    </body>
</html>
