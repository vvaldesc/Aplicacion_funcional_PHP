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
    <title>Gestión de Coches - Concesionario</title>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/styleLinks.php' ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php
    include_once ''; $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
        //session_start(); Este sobra
    ?>
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
    <?php 
        $mod='a';
        $formError=false;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['datos'])){
                modificarTabla('coches', 'matricula', $_POST["matricula"],'VIN',$_POST["vin"] );
                modificarTabla('coches', 'marca', $_POST["marca"],'VIN',$_POST["vin"] );
                modificarTabla('coches', 'modelo', $_POST["modelo"],'VIN',$_POST["vin"] );
                modificarTabla('coches', 'ano', $_POST["ano"],'VIN',$_POST["vin"] );
                modificarTabla('coches', 'km', $_POST["km"],'VIN',$_POST["vin"] );
                
            }else{
                if(isset($_POST['clear'])){
                    eliminarDatos('coches', 'VIN', $_POST['clear']);
                }else{
                    if(isset($_POST['mod'])){
                    $mod=$_POST['mod'];
                }else{
                    if (checkForm($_POST)){
                    insertar("coches", array("VIN" => $_POST["vin"], "Matricula" => $_POST["matricula"],"Marca" => $_POST["marca"], "Modelo" => $_POST["modelo"], "Ano" => $_POST["año"], "Precio" => $_POST["precio"], "Km" => $_POST["km"]));
                    }else{
                        $formError=true;
                    }
                }
            
        }}
        }
    
    ?>

    <div class="container mt-4">
        <h1 class="text-center mb-5">Gestión de Coches</h1>
        <!-- Caracteristicas de coches -->
        <table class="table">
            <thead>
                <tr>
                    <th>VIN</th>
                    <th>Matricula</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Precio</th>
                    <th>Km</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //EMPLEADOS Y JEFE PUEDEN VER LOS COCHES
                    //si la sesion corresponde a un admin, puede ver todos los coches y toda la info sobre cada uno
                
                    $sentencia='SELECT * FROM coches';
                    
                    //si la sesion corresponde a un cliente, este puede ver sus propios coches, y eliminaría el apartado de propietario
                    //de cada coche, ya que todos van a ser su coche
                    
                    //$sentencia = 'SELECT * FROM COCHES WHERE DNI IS (SELECT DNI FROM USUARIOS WHERE USUARIO IS (USUARIO DE LA SESION))'
                    
                    
                    $tabla=extraerTablas($sentencia);
                    for($i=0;$i< count($tabla);$i++){
                        if($mod==$i){
                            echo '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
                            echo '<input type="hidden" id="datos" name="datos" value="">';
                            echo '<input type="hidden" id="vin" name="vin" value="'.$tabla[$i][0].'">';
                            echo '<tr>
                                     <td>'.$tabla[$i][0].'</td>
                                     <td><input value="'.$tabla[$i][1].'" type="text" name="matricula"  class="form-control" id="matricula" placeholder="Ejemplo: 0625FFF" required></td>
                                     <td> <input value="'.$tabla[$i][2].'" type="text" name="marca"  class="form-control" id="marca" placeholder="Ejemplo: Toyota" required></td>
                                     <td><input value="'.$tabla[$i][3].'" type="text" name="modelo"  class="form-control" id="modelo" placeholder="Ejemplo: Camry" required></td>
                                     <td><input value="'.$tabla[$i][4].'" type="number" name="ano"  class="form-control" id="año" placeholder="Ejemplo: 2023" required></td>
                                     <td><input value="'.$tabla[$i][5].'" type="text" name="precio"  class="form-control" id="precio" placeholder="Ejemplo: 25000" required></td>
                                     <td><input value="'.$tabla[$i][6].'" type="text" name="km"  class="form-control" id="km" placeholder="Ejemplo: 150000" required></td>
                                    </tr>';
                            echo '<button class="btn btn-primary border" type="submit">Modificar Tabla</button>';
                            echo '</form>';
                        }else{
                            echo '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
                            echo '<input type="hidden" id="mod" name="mod" value="'.$i.'">';
                            echo '<tr>
                                <td>'.$tabla[$i][0].'</td>
                                <td>'.$tabla[$i][1].'</td>
                                <td>'.$tabla[$i][2].'</td>
                                <td>'.$tabla[$i][3].'</td>
                                <td>'.$tabla[$i][4].'</td>
                                <td>'.$tabla[$i][5].'</td>
                                <td>'.$tabla[$i][6].'</td>
                                <td><button class="btn btn-primary border" type="submit"><i class="fa-solid fa-pencil"></i></button>
                                ';
                            echo '</form>';
                            echo '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">
                                    <input type="hidden" id="clear" name="clear" value="'.$tabla[$i][0].'">
                                    <td><button class="btn btn-danger border" type="submit"><i class="fa-solid fa-trash"></i></button></td>
                                </form>';
                            echo '</tr>';
                    }
                    }
                
                ?>
                
            </tbody>
        </table>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#agregarCoche">Agregar Coche</button>

        <div class="modal fade" id="agregarCoche" >
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Agregar Coche</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <!-- Agregar Nuevo coche-->
                      <form method="POST" action="<?php $_SERVER["PHP_SELF"] ?>">
                          <div class="form-group">
                              <label for="vin">VIN</label>
                              <input  value="<?= $formError ? $_POST["vin"] : "" ?>" type="text" name="vin" class="form-control" id="vin" placeholder="Ejemplo: Bastidor" required>
                          </div>
                          <div class="form-group">
                              <label for="matricula">Matricula</label>
                              <input value="<?= $formError ? $_POST["vin"] : "" ?>" type="text" name="matricula"  class="form-control" id="matricula" placeholder="Ejemplo: 0625FFF" required>
                          </div>
                          <div class="form-group">
                              <label for="marca">Marca</label>
                              <input value="<?= $formError ? $_POST["vin"] : "" ?>" type="text" name="marca"  class="form-control" id="marca" placeholder="Ejemplo: Toyota" required>
                          </div>
                          <div class="form-group">
                              <label for="modelo">Modelo</label>
                              <input value="<?= $formError ? $_POST["vin"] : "" ?>" type="text" name="modelo"  class="form-control" id="modelo" placeholder="Ejemplo: Camry" required>
                          </div>
                          <div class="form-group">
                              <label for="año">Año</label>
                              <input value="<?= $formError ? $_POST["vin"] : "" ?>" type="number" name="año"  class="form-control" id="ano" placeholder="Ejemplo: 2023" required>
                          </div>
                          <div class="form-group">
                              <label for="precio">Precio</label>
                              <input value="<?= $formError ? $_POST["vin"] : "" ?>" type="text" name="precio"  class="form-control" id="precio" placeholder="Ejemplo: 25000" required>
                          </div>
                          <div class="form-group">
                              <label for="km">KM</label>
                              <input value="<?= $formError ? $_POST["vin"] : "" ?>" type="text" name="km"  class="form-control" id="km" placeholder="Ejemplo: 150000" required>
                          </div>
                          <div class="form-group d-flex flex-column">
                              <label for="vendedor">Vendedor</label>
                              <div class="form-group">
                                  <select class="col-xl-9" id="vendedor" name="vendedor">
                                  <?php
                                        $tabla= extraerTablas('SELECT Nombre,Apellidos,DNI FROM vendedores;');
                                        foreach ($tabla as $key => $value) {
                                            echo '<option value="' . $value[0].' '.$value[1] . ' '.$value[2] . '">' . $value[0].' '.$value[1] .' ' .$value[2] .'</option>';
                                        }
                                  ?>
                              </select>
                              <?php
                                //Boton añadir vendedor
                                if($_SESSION['rol']=='admin'){
                                    echo '<a href="./gestionEmpleados.php"><i class="fa-solid fa-circle-plus"></i></a>';
                                }
                              ?>
                              </div>                    
                          </div>
                          <div class="form-group d-flex flex-column">
                              <label for="cliente">Cliente</label>
                              <div class="form-group">
                                  <select class="col-xl-9" id="cliente" name="Cliente">
                                    <?php
                                        $tabla= extraerTablas('SELECT Nombre,Apellidos,DNI FROM clientes;');
                                        foreach ($tabla as $key => $value) {
                                            echo '<option value="' . $value[0].' '.$value[1] . ' '.$value[2] . '">' . $value[0].' '.$value[1] . ' ' .$value[2] .' </option>';
                                        }
                                    ?> 
                                  </select>
                                  <a href="./gestionClientes.php"><i class="fa-solid fa-circle-plus"></i></a>
                                  
                              </div>
                                
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
