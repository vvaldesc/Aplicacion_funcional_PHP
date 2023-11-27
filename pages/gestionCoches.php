<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Coches - Concesionario</title>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/styleLinks.php' ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php
        include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
        session_start();
    ?>
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
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
                    <th>Cod_vendedor</th>
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
                        //No lo he comprobado
                        echo '<tr>
                                <td>'.$tabla[$i][0].'</td>
                                <td>'.$tabla[$i][1].'</td>
                                <td>'.$tabla[$i][2].'</td>
                                <td>'.$tabla[$i][3].'</td>
                                <td>'.$tabla[$i][4].'</td>
                                <td>'.$tabla[$i][5].'</td>
                                <td>'.$tabla[$i][6].'</td>
                                <td>'.$tabla[$i][7].'</td> 
                                 <td><a class="btn btn-primary border" href="#"><i class="fa-solid fa-pencil"></i></a><a class="btn btn-danger border" href="#"><i class="fa-solid fa-trash"></i></i></a></td>
                            </tr>';
                        $vendedores[]=$tabla[$i][7];
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
                      <form method="POST" action="#">
                          <div class="form-group">
                              <label for="vin">VIN</label>
                              <input type="text" class="form-control" id="vin" placeholder="Ejemplo: Bastidor" required>
                          </div>
                          <div class="form-group">
                              <label for="matricula">Matricula</label>
                              <input type="text" class="form-control" id="matricula" placeholder="Ejemplo: 0625FFF" required>
                          </div>
                          <div class="form-group">
                              <label for="marca">Marca</label>
                              <input type="text" class="form-control" id="marca" placeholder="Ejemplo: Toyota" required>
                          </div>
                          <div class="form-group">
                              <label for="modelo">Modelo</label>
                              <input type="text" class="form-control" id="modelo" placeholder="Ejemplo: Camry" required>
                          </div>
                          <div class="form-group">
                              <label for="año">Año</label>
                              <input type="number" class="form-control" id="año" placeholder="Ejemplo: 2023" required>
                          </div>
                          <div class="form-group">
                              <label for="precio">Precio</label>
                              <input type="text" class="form-control" id="precio" placeholder="Ejemplo: 25000" required>
                          </div>
                          <div class="form-group">
                              <label for="km">KM</label>
                              <input type="text" class="form-control" id="km" placeholder="Ejemplo: 150000" required>
                          </div>
                          <div class="form-group d-flex flex-column">
                              <label for="vendedor">Vendedor</label>
                              <div class="form-group">
                                  <select class="col-xl-9" id="vendedor" name="vendedor">
                                  <?php
                                    for ($i = 0; $i < count($vendedores); $i++) {
                                        echo '<option value="' . $vendedores[$i] . '">' . $vendedores[$i] . '</option>';
                                    }
                                  ?>
                              </select>
                              <?php
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
                                        $tabla= extraerTablas('SELECT Nombre,Apellidos FROM clientes;');
                                        foreach ($tabla as $key => $value) {
                                            echo '<option value="' . $value[0].' '.$value[1] . '">' . $value[0].' '.$value[1] . '</option>';
                                        }
                                    ?> 
                                  </select>
                                  <a href="./gestionClientes.php.php"><i class="fa-solid fa-circle-plus"></i></a>
                                  
                              </div>
                                
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <input type="submit" class="btn btn-primary" value="Guardar">
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
