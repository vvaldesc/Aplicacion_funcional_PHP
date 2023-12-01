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
    <title>Gestión de Coches - Concesionario</title>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/styleLinks.php' ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
    ?>
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php' ?>
    <?php 
        $mod='a';
        $formError=false;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['datos'])) {
        modificarTabla('coches', 'matricula', $_POST["matricula"], 'VIN', $_POST["vin"]);
        modificarTabla('coches', 'marca', $_POST["marca"], 'VIN', $_POST["vin"]);
        modificarTabla('coches', 'modelo', $_POST["modelo"], 'VIN', $_POST["vin"]);
        modificarTabla('coches', 'ano', $_POST["ano"], 'VIN', $_POST["vin"]);
        modificarTabla('coches', 'km', $_POST["km"], 'VIN', $_POST["vin"]);
    } else {
        if (isset($_POST['clear'])) {
            eliminarDatos('coches', 'VIN', $_POST['clear']);
        } else {
            if (isset($_POST['mod'])) {
                $mod = $_POST['mod'];
            } else {
                if (checkForm($_POST) && validarVIN($_POST["vin"]) && validarMatricula($_POST["matricula"])) {
                    try {
                        insertar("coches", array("VIN" => $_POST["vin"], "Matricula" => $_POST["matricula"], "Marca" => $_POST["marca"], "Modelo" => $_POST["modelo"], "Ano" => $_POST["año"], "Precio" => $_POST["precio"], "Km" => $_POST["km"]));
                    } catch (Exception $exc) {
                        echo $exc->getTraceAsString();
                    }
                } else {
                    $formError = true;
                }
            }
        }
    }
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
mostrarCoches($mod)
                
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
                              <input value="<?= $formError ? $_POST["matricula"] : "" ?>" type="text" name="matricula"  class="form-control" id="matricula" placeholder="Ejemplo: 0625FFF" required>
                          </div>
                          <div class="form-group">
                              <label for="marca">Marca</label>
                              <input value="<?= $formError ? $_POST["marca"] : "" ?>" type="text" name="marca"  class="form-control" id="marca" placeholder="Ejemplo: Toyota" required>
                          </div>
                          <div class="form-group">
                              <label for="modelo">Modelo</label>
                              <input value="<?= $formError ? $_POST["modelo"] : "" ?>" type="text" name="modelo"  class="form-control" id="modelo" placeholder="Ejemplo: Camry" required>
                          </div>
                          <div class="form-group">
                              <label for="año">Año</label>
                              <input value="<?= $formError ? $_POST["año"] : "" ?>" type="number" name="año"  class="form-control" id="ano" placeholder="Ejemplo: 2023" required>
                          </div>
                          <div class="form-group">
                              <label for="precio">Precio</label>
                              <input value="<?= $formError ? $_POST["precio"] : "" ?>" type="text" name="precio"  class="form-control" id="precio" placeholder="Ejemplo: 25000" required>
                          </div>
                          <div class="form-group">
                              <label for="km">KM</label>
                              <input value="<?= $formError ? $_POST["km"] : "" ?>" type="text" name="km"  class="form-control" id="km" placeholder="Ejemplo: 150000" required>
                          </div>
                          <div class="form-group d-flex flex-column">
                              <label for="vendedor">Vendedor</label>
                              <div class="form-group">
                                  <select class="col-xl-9" id="vendedor" name="vendedor">
                                  <?php
                                    imprimirSelects('SELECT Nombre,Apellidos,DNI FROM vendedores;');
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
