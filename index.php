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
        if (isset($_POST)) {
            //
        }
        
        
        
        ?>

        <form method="POST" action=<?php $_SERVER["PHP_SELF"] ?>>

            <div class="form-group">
                <label for="nombre">Usuario</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
            </div>
            <div class="form-group">
                <label for="ape">Contraseña</label>
                <input type="text" class="form-control" id="ape" name="apellidos" placeholder="Apellidos">
            </div>

        <button type="submit" class="btn btn-primary">Submit</button>

        </form>
            
        </div>

    </body>
</html>
