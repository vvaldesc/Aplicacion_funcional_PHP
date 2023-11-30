<?php
    session_start();
    $nombreParaCookie=$_SESSION["name"];
    $apellidoParaCookie=$_SESSION["apellidos"];
    $nombreCompleto=$nombreParaCookie.' '.$apellidoParaCookie;
    $fechaActualObjeto = new DateTime();
    $fechaActualString = $fechaActualObjeto->format('Y-m-d H:i:s');
    setcookie("nombreSesion", $_SESSION["name"] . " " . $_SESSION["apellidos"], time() + 300, 'localhost'); //la cookie dura 5 minutos
    setcookie("ultCone", $fechaActualString , time() + 300, 'localhost');
    
    unset($nombreParaCookie);    unset($apellidoParaCookie);    unset($nombreCompleto);
    unset($fechaActualObjeto);    unset($fechaActualString);    unset($fechaActualString);


include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/funciones.php';

    if (!isset($_COOKIE["ultCone"]) || isset($_GET["logOut"])) {
        cerrarSesion($_SESSION);
        header('Location: ../index.php');
    } else {
        comprobarInicio($_SESSION);
        //La cookie se actualiza, por tanto solo expira la sesión por inactividad
        setcookie("ultCone", date('Y-m-d H:i:s'), 300, '/'); //la cookie dura 10 minutos
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
    include_once ''; $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
        $formError=false;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (checkForm($_POST)){
                    insertar("clientes", array("DNI" => $_POST["dni"], "Nombre" => $_POST["nombre"],"Apellidos" => $_POST["domicilio"],"Domicilio" => "Calle Fernandez De los Rios, 9","FechaNac"=>$_POST["fechanac"]));
                }else{
                    $formError=true;
            }
    }
    ?>
</head>
                            <a class="nav-link" href="homepage.php?logOut=true">
                                <i class="fa-solid fa-car mx-2 bg-danger"></i>Cerrar sesión
                            </a>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
    <div class="container mt-4">
        <h1 class="text-center mb-5">Gestión de Clientes</h1>
        <!-- Caracteristicas de coches -->
        <table class="table">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Domicilio</th>
                    <th>Fecha de Nacimiento</th>
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
                
                    $sentencia='SELECT * FROM CLIENTES';
                                        
                    $tabla=extraerTablas($sentencia);
                    for($i=0;$i< count($tabla);$i++){
                        //No lo he comprobado
                        echo '<tr>
                                <td>'.$tabla[$i][0].'</td>
                                <td>'.$tabla[$i][1].'</td>
                                <td>'.$tabla[$i][2].'</td>
                                <td>'.$tabla[$i][3].'</td>
                                <td>'.$tabla[$i][4].'</td>
                                 <td><a class="btn btn-primary border" href="#"><i class="fa-solid fa-pencil"></i></a><a class="btn btn-danger border" href="#"><i class="fa-solid fa-trash"></i></i></a></td>
                            </tr>';
                    }
                
                
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
                      <form method="POST" action=<?php $_SERVER["PHP_SELF"] ?>>
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
