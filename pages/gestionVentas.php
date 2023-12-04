<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/funciones.php';
    session_start();
    comprobarCookie($_SESSION, $_COOKIE);
    
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
    <?php 
        $mod = 'a';
        $nombreTabla='ventas';
        include $_SERVER['DOCUMENT_ROOT'] . '/Aplicacion_funcional_PHP/templates/header.php';
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_POST['cod_ventas'])){
                $valorInsert= array('COD_VENTAS' => $_POST['cod_ventas'], "DNI_vendedores"
                                . "" => ultimaPalabra($_POST['vendedor']), "VIN_coches" => ultimaPalabra($_POST['coche']),
                                "DNI_clientes" => ultimaPalabra($_POST['cliente']));
                formularioGestion($nombreTabla, $_POST, $valorInsert);
            }else{
                $mod = formularioGestion($nombreTabla, $_POST);
            }
            
        }
        
    ?>
    <div class="container mt-4">
        <h1 class="text-center mb-5">Gestión de Ventas</h1>
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
                    $cod_venta;
                    mostrarVentas($mod,$cod_venta);
                ?>
            </tbody>
        </table>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#agregarVenta">Agregar Venta</button>

        <div class="modal fade" id="agregarVenta" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
 <div class="modal-body">
                        <!-- Agregar Nuevo coche-->
                        <form method="POST" action="<?= $_SERVER["PHP_SELF"] ?>">
                            <div class="form-group">
                                <label for="vendedor">Seleccione un Vendedor</label>

                                <input type="hidden" id="cod_ventas" name="cod_ventas" value="<?php echo $cod_venta + 1; ?>">
                                <select class="col-xl-9" id="vendedor" name="vendedor">
                                    <?php
                                    imprimirSelects('SELECT NOMBRE , APELLIDOS, DNI FROM VENDEDORES');                      
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="coche">Seleccione un Coche</label>
                                <select class="col-xl-9" id="coche" name="coche">
                                    <?php
                                        imprimirSelects('SELECT Marca , Modelo , VIN FROM coches');
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cliente">Seleccione un Cliente</label>
                                <select class="col-xl-9" id="cliente" name="cliente">
                                    <?php
                                        imprimirSelects('SELECT Nombre , Apellidos , dni FROM clientes');
                                    ?>
                                </select>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
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
