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
    <title>Gestión de empleados - Concesionario</title>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/styleLinks.php' ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" >
    <?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
    ?>
</head>
<body>    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
    
    <?php 
    $mod = 'a';
    $formError = false;
    $nombreTabla='vendedores';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['dni'])){
            $valorInsert= array("DNI" => $_POST["dni"], "Nombre" => $_POST["nombre"], "Apellidos" => $_POST["apellidos"], "FechaAlta" => $_POST["fechaAlta"], "FechaNac" => $_POST["fechanac"], "Rol" => $_POST["rol"], "contrasena" => hash('sha256', $_POST["contrasena"]), 'Email' => $_POST["mail"]);
             formularioGestion($nombreTabla, $_POST, $valorInsert);       
        }
        else{
            $mod = formularioGestion($nombreTabla, $_POST);
        }
    }
    ?>    
    <div class="container mt-4">
        <h1 class="text-center mb-5">Gestión de Empleados</h1>
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
                
                //EN ROL ROL JEFE, MUESTRA LOS EMMPLEADOS(VENDEDORES)
                
                //Un cliente no debería poder entrar aquí
                //Un admin puede ver y gestionar la informacion de todos los clientes
                mostrarEmpleados($mod);
                ?>
                
            </tbody>
        </table>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#agregarCoche">Agregar Empleado</button>

        <div class="modal fade" id="agregarCoche" >
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Agregar Empleado</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <!-- Agregar Nuevo Empleado-->
                      <form method="POST" action=<?php $_SERVER["PHP_SELF"] ?>>
                          <div class="form-group">
                              <label for="dni">DNI</label>
                              <input value="<?= $formError ? $_POST["dni"] : "" ?>"  name="dni" type="text" class="form-control" id="DNI" placeholder="dni" required>
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
                          <div class="form-group d-flex flex-column">
                              <label for="rol">Rol</label>
                              <select class="form-select form-select-sm" id="rol" name="rol">
                                  <option value="junior">Junior</option>
                                  <option value="admin">Admin</option>
                              </select>

                          </div>
                          <div class="form-group">
                              <label for="rol">E-mail</label>
                              <input value="<?= $formError ? $_POST["mail"] : "" ?>"  name="mail" type="email" class="form-control" id="mail" placeholder="E-mail" required>
                          </div>
                          <div class="form-group">
                              <label for="rol">Contraseña</label>
                              <input name="contrasena" type="password" class="form-control" id="contrasena" placeholder="Contraseña" required>
                          </div>
                          <div class="form-group">
                              <input name="fechaAlta" type="hidden" value="<?php echo date('Y-m-d'); ?>" class="form-control" id="fechaAlta">
                          </div>
                          <div class="form-group">
                              <label for="fechanac">Fecha de nacimiento</label>
                              <input name="fechanac" type="date" class="form-control" id="fechanac" required>
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

    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/footer.php' ?>
    <!-- JavaScript y jQuery para habilitar los componentes de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
