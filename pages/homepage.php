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
        //cerrarSesion($_SESSION);
        //header('Location: ../index.php');
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
    <title>Inicio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
    <header>
        <h1>Concesionarios García</h1>
    </header>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-sm-3 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item my-1">
                            <a class="nav-link active" href="#">
                                <i class="fa-solid fa-user rounded-circle mx-2 border px-1 pt-1"></i>Inicio
                            </a>
                        </li>
                        <li class="nav-item m-1">
                            <a class="nav-link" href="./gestionVentas.php">
                                <i class="fa-solid fa-shop mx-2"></i>Gestión de Ventas
                            </a>
                        </li>
                        <li class="nav-item m-1">
                            <a class="nav-link" href="./gestionCoches.php">
                                <i class="fa-solid fa-car mx-2"></i>Gestión de Coches
                            </a>
                        </li>
                        
                        <!-- No debería ser visible para clientes a partir de aquí -->
                        <?php
                        if ($_SESSION['rol'] == 'admin'){
                            echo'
                                <li class="nav-item m-1">
                                    <a class="nav-link" href="gestionClientes.php">
                                        <i class="fa-solid fa-children mx-2"></i>Gestion de Clientes
                                    </a>
                                </li>
                                <li class="nav-item m-1">
                                    <a class="nav-link" href="gestionEmpleados.php">
                                        <i class="fa-solid fa-user-nurse mx-2"></i>Gestion de Empleados
                                    </a>
                                </li>
                              ';
                        }   
                        ?>
                        <li class="nav-item m-1">
                            <a class="nav-link" href="homepage.php?logOut=true">
                                <i class="fa-solid fa-car mx-2 bg-danger"></i>Cerrar sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-8 ms-sm-auto px-md-4">
                <div class="col-xl-5 col-sm-7 mb-5 mx-auto">
                    <div class="bg-white rounded shadow-sm py-5 px-4">
                        <img src="../assets/img/jefe.jpg" alt="" width="100" class="img-fluid rounded-circle mb-3 mx-auto d-block  img-thumbnail shadow-sm">
                        <h2 class="mb-3 mx-auto col-lg-14 text-center">Bienvenido <span class="display-4"><?php echo isset($_COOKIE["nombreSesion"]) ? $_COOKIE["nombreSesion"] : "error no existe la cookie nombreSesion"; ?></span></h2>
                        <p class="small text-uppercase text-muted text-center">Última conexión <span><?php echo isset($_COOKIE["ultCone"]) ? $_COOKIE["ultCone"] : "error no existe la cookie ultCone"; ?></p>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/footer.php' ?>
    <!-- Agrega el enlace a Bootstrap JavaScript (asegúrate de que la URL sea correcta) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

