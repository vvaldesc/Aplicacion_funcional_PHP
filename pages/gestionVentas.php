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
                    <th>Vendedor</th>
                    <th>Coche</th>
                    <th>Cliente</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /*
                 * AQUI SE MUESTRAN TODAS LAS VENTAS
                 * 
                 * 
                //Un cliente no debería poder entrar aquí
                //Un admin puede ver y gestionar la informacion de todos los clientes
                 * 
                 * 
                 * 
                    $sentencia='SELECT clientes.nombre, clientes.DNI, coches.VIN, vendedores.DNI, vendedores.Nombre from clientes join coches on clientes.DNI = coches.VIN join vendedores vendedores.dni=coches.VIN';
                    
                    
                    
                    $tabla=extraerTablas($sentencia);
                    for($i=0;$i< count($row);$i++){
                        //No lo he comprobado
                        echo '<tr>
                                 <td>'.$tabla[0][1].'</td>
                                 <td>'.$tabla[0][1].'</td>
                                 <td>'.$tabla[0][2].'</td>
                                 <td>'.$tabla[0][3].'</td>
                                 <td>'.$tabla[0][4].'</td>
                                 <td><a class="btn btn-primary border" href="#"><i class="fa-solid fa-pencil"></i></a><a class="btn btn-danger border" href="#"><i class="fa-solid fa-trash"></i></i></a></td>
                            </tr>';
                    }
                */
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
                              <input type="text" class="form-control" id="precio" placeholder="Ejemplo: 25000.00" required>
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
