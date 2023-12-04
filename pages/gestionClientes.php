<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/funciones.php';
    session_start();
    comprobarCookie($_SESSION,$_COOKIE);
    if ($_SESSION['rol'] !== 'admin') {
        header("Location: homepage.php");
        exit(); // Ensure that no further code is executed after the redirect
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Coches - Concesionario</title>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/styleLinks.php' ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php
        $mod = 'a';
        $formError = false;
        $nombreTabla='clientes';
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['dni'])){
                $valorInsert=  array("DNI" => $_POST["dni"], "Nombre" => $_POST["nombre"],"Apellidos" => $_POST["domicilio"],"Domicilio"
                                     => "Calle Fernandez De los Rios, 9","FechaNac"=>$_POST["fechanac"]);
                formularioGestion($nombreTabla, $_POST, $valorInsert);
            }
            else{
                $mod = formularioGestion($nombreTabla, $_POST);
            }
        }
        
?>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
    <div class="container mt-4">
        <h1 class="text-center mb-5">Gestión de Clientes</h1>
        <!-- Caracteristicas de coches -->
        <table class="table">
            <thead>
                <tr>
                    <?php
                        vercolumnas($nombreTabla);
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                /*
                 * AQUI SE MUESTRAN TODOS LOS CLIENTES, SOLO JEFE
                 * 
                 * 
                */
                /*Un cliente no debería poder entrar aquí
                Un admin puede ver y gestionar la informacion de todos los clientes
                    $sentencia='SELECT * FROM usuarios where rol is not admin';
                    
                */    
                
                mostrarClientes($mod);
                    
                
                
                ?>
                
            </tbody>
        </table>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#agregarCoche">Agregar Cliente</button>

        <div class="modal fade" id="agregarCoche" >
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Agregar Cliente</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <!-- Agregar Nuevo coche-->
                      <form method="POST" action='<?php $_SERVER["PHP_SELF"] ?>'>
                          <div class="form-group">
                              <label for="dni">DNI</label>
                              <input value="<?= $formError ? $_POST["dni"] : "" ?>"  name="dni" type="text" class="form-control" id="dni" placeholder="dni" required>
                          </div>
                          <div class="form-group">
                              <label for="modelo">Nombre</label>
                              <input value="<?= $formError ? $_POST["nombre"] : "" ?>"  name="nombre" type="text" class="form-control" id="nombre" placeholder="Nombre" required>
                          </div>
                          <div class="form-group">
                              <label for="apellidos">Apellidos</label>
                              <input value="<?= $formError ? $_POST["apellidos"] : "" ?>"  name="apellidos" type="text" class="form-control" id="apellidos" placeholder="Apellidos" required>
                          </div>
                          <div class="form-group">
                              <label for="domicilio">Domicilio</label>
                              <input value="<?= $formError ? $_POST["domicilio"] : "" ?>"  name="domicilio" type="text" class="form-control" id="domicilio" placeholder="Domicilio" required>
                          </div>
                          <div class="form-group">
                              <label for="fechanac">Fecha de nacimiento</label>
                              <input name="fechanac" type="date" class="form-control" id="domicilio" required>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                              <input type="submit" class="btn btn-primary" value="Guardar">
                          </div>
                      </form>
                  </div>

              </div>
          </div>
      </div>
    </div>


    <!-- JavaScript y jQuery para habilitar los componentes de Bootstrap -->
    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/footer.php' ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>
