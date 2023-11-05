<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title></title>
    </head>
    <body>
        
        <!-- container -->
        <div style="width: 1000px; margin: auto">
        
        <?php //comprobar credenciales y token y si no, error
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
        
        
        
        ?>

        <form method="POST" action=<?php $_SERVER["PHP_SELF"] ?>>

            <div class="form-group">
                <label for="usr">Usuario</label>
                <input type="text" class="form-control" id="usr" name="usr" placeholder="Usuario">
            </div>
            <div class="form-group">
                <label for="pass">Contraseña</label>
                <input type="text" class="form-control" id="pass" name="pass" placeholder="Contraseña">
            </div>

        <button type="submit" class="btn btn-primary">Submit</button>

        </form>
            
        </div>

    </body>
</html>
