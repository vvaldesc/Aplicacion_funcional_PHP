<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/funciones.php';
    session_start();
    comprobarCookie($_SESSION,$_COOKIE);
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de empleados - Concesionario</title>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/styleLinks.php' ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
    ?>
</head>
<body>    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
    
    <?php 
    $mod = 'a';
    $formError = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['datos'])) {
            modificarTabla('vendedores', 'Nombre', $_POST["nombre"], 'DNI', $_POST["DNI"]);
            modificarTabla('vendedores', 'Apellidos', $_POST["apellido"], 'DNI', $_POST["DNI"]);
            modificarTabla('vendedores', 'FechaAlta', $_POST["fechaAlta"], 'DNI', $_POST["DNI"]);
            modificarTabla('vendedores', 'FechaNac', $_POST["fechaNac"], 'DNI', $_POST["DNI"]);
            modificarTabla('vendedores', 'Rol', $_POST["rol"], 'DNI', $_POST["DNI"]);
            modificarTabla('vendedores', 'Email', $_POST["email"], 'DNI', $_POST["DNI"]);
        } else {
            if (isset($_POST['clear'])) {
                eliminarDatos('vendedores', 'DNI', $_POST['clear']);
            } else {
                if (isset($_POST['mod'])) {
                    $mod = $_POST['mod'];
                } else {
                    if (checkForm($_POST)) {
                        insertar("vendedores", array("DNI" => $_POST["dni"], "Nombre" => $_POST["nombre"], "Apellidos" => $_POST["apellidos"], "FechaAlta" => $_POST["fechaAlta"], "FechaNac" => $_POST["fechanac"], "Rol" => $_POST["rol"], "contrasena" => hash('sha256', $_POST["contrasena"]), 'Email' => $_POST["mail"]));
                    } else {
                        $formError = true;
                    }
                }
            }
        }
    }
    ?>    
    <div class="container mt-4">
        <h1 class="text-center mb-5">Gestión de Empleados</h1>
        <!-- Caracteristicas de coches -->
        <table class="table">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Fecha de alta</th>
                    <th>Fecha de nacimiento</th>
                    <th>Rol</th>
                    <th>E-Mail</th>

                </tr>
            </thead>
            <tbody>
                <?php
                
                //EN ROL ROL JEFE, MUESTRA LOS EMMPLEADOS(VENDEDORES)
                
                
                //Un cliente no debería poder entrar aquí
                //Un admin puede ver y gestionar la informacion de todos los clientes
                    $sentencia='SELECT * FROM vendedores';
                    
                    
                    
                    
                    
                     $tabla=extraerTablas($sentencia);
                    for($i=0;$i< count($tabla);$i++){
                        if($mod==$i){
                            echo '<form method="POST" class="border w-100" action="'.$_SERVER["PHP_SELF"].'">';
                            echo '<input type="hidden" id="datos" name="datos" value="">';
                            echo '<input type="hidden" id="vin" name="DNI" value="'.$tabla[$i][0].'">';
                            echo '<tr>
                                     <td>'.$tabla[$i][0].'</td>
                                     <td><input value="'.$tabla[$i][1].'" type="text" name="nombre"  class="form-control" id="nombre" placeholder="Ejemplo: Federico" required></td>
                                     <td> <input value="'.$tabla[$i][2].'" type="text" name="apellido"  class="form-control" id="apellido" placeholder="Ejemplo: Garcia Garcia" required></td>
                                     <td><input value="'.$tabla[$i][3].'" type="date" name="fechaAlta"  class="form-control" id="fechaAlta" required></td>
                                     <td><input value="'.$tabla[$i][4].'" type="date" name="fechaNac"  class="form-control" id="fechaNac"  required></td>
                                     <td><select class="form-select form-select-sm w-100" id="rol" name="rol">
                                            <option value="junior">Junior</option>
                                            <option value="admin">Admin</option>
                                    </select></td>
                                     <td><input value="'.$tabla[$i][7].'" type="text" name="email"  class="form-control" id="email" placeholder="Ejemplo: hola@hola.es" required></td>
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
                                <td>'.$tabla[$i][7].'</td>
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
                              <input value="<?= $formError ? $_POST["mail"] : "" ?>"  name="mail" type="text" class="form-control" id="mail" placeholder="E-mail" required>
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
