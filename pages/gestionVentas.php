<?php
    session_start();
    
    $fechaActualObjeto = new DateTime();
    $fechaActualString = $fechaActualObjeto->format('Y-m-d H:i:s');
    setcookie("nombreSesion", $_SESSION["name"] . " " . $_SESSION["apellidos"], time() + 300, 'localhost'); //la cookie dura 5 minutos
    setcookie("ultCone", $fechaActualString , time() + 300, 'localhost');

include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/funciones.php';

    if (!isset($_COOKIE["ultCone"])) {
        cerrarSesion($_SESSION);
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
        include_once $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/libraries/conexionPDO.php';
    ?>
</head>
<body>
    <?php 
    $mod='a';
    include $_SERVER['DOCUMENT_ROOT'].'/Aplicacion_funcional_PHP/templates/header.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if(isset($_POST['datos'])){
            modificarTabla('ventas', 'DNI_vendedores', ultimaPalabra($_POST['vendedor']),'cod_ventas',$_POST['cod_venta'] );
            modificarTabla('ventas', 'VIN_coches', ultimaPalabra( $_POST['coche']),'cod_ventas',$_POST['cod_venta'] );
            modificarTabla('ventas', 'DNI_clientes', ultimaPalabra( $_POST['cliente']),'cod_ventas',$_POST['cod_venta'] );
        }else{
            if(isset($_POST['clear'])){
                eliminarDatos('ventas', 'cod_ventas', $_POST['clear']);
            }else{
            if(isset($_POST['mod'])){
                $mod=$_POST['mod'];
            }else{
                insertar('ventas',array('COD_VENTAS' => $_POST['cod_ventas'],"DNI_vendedores"
                    . ""=>ultimaPalabra( $_POST['vendedor']) ,"VIN_coches" => ultimaPalabra( $_POST['coche']),
                    "DNI_clientes" => ultimaPalabra( $_POST['cliente'])));
                
            }
        }
        }
    }     
           
    ?>
    <div class="container mt-4">
        <h1 class="text-center mb-5">Gestión de Ventas</h1>
        <!-- Caracteristicas de coches -->
        <table class="table">
            <thead>
                <tr>
                    <th>Cod_Ventas</th>
                    <th>Vendedor</th>
                    <th>Coche</th>
                    <th>Cliente</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /*
                 * AQUI SE MUESTRAN TODAS LAS VENTAS
                 * 
                 * 
                //Un admin puede ver y gestionar la informacion de todos los clientes
                 * y un vendedor también.
                 * 
                 * 
                 * */
                    $sentencia='SELECT * from ventas';
                    
                    
                    
                    $tabla=extraerTablas($sentencia);
                    for($i=0;$i< count($tabla);$i++){
                        $cod_venta=$tabla[$i][0];
                        
                        //No lo he comprobado
                        if($mod==$i){
                            echo '<form class="mx-0" method="POST" action="'.$_SERVER["PHP_SELF"].'">';
                            echo '<input type="hidden" id="datos" name="datos" value="">';
                            echo '<input type="hidden" id="cod_venta" name="cod_venta" value="'.$tabla[$i][0].'">';
                            echo '<tr>
                                     <td>'.$tabla[$i][0].'</td>
                                     <td><select class="col-xl-9" id="vendedor" name="vendedor">
                                    ';
                            imprimirSelects('SELECT NOMBRE , APELLIDOS, DNI FROM VENDEDORES');    
                            echo '       
                                </select></td>
                                     <td><select class="col-xl-9" id="coche" name="coche">
                                    ';
                            imprimirSelects('SELECT Marca , Modelo , vin FROM coches');
                                    
                            echo   '</select></td>
                                     <td><select class="col-xl-9" id="cliente" name="cliente">
                                    ';
                            imprimirSelects('SELECT Nombre , Apellidos , dni FROM clientes');
                                    
                            echo    '</select></td>
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
                                     <td class="mx-0"><button class="btn btn-primary mx-0 border" type="submit"><i class="fa-solid fa-pencil"></i></button></td>
                            ';
                            echo '</form>';
                            echo '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">
                                    <input type="hidden" id="clear" name="clear" value="'.$tabla[$i][0].'">
                                    <td><button class="btn btn-danger mx-0 border" type="submit"><i class="fa-solid fa-trash"></i></button></td>
                                </form>';
                            echo '</tr>';
                        }
                        
                    }
                    
                
                ?>
                
            </tbody>
        </table>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#agregarVenta">Agregar Coche</button>

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
                        <form method="POST" action="<?php $_SERVER["PHP_SELF"] ?>">
                            <div class="form-group">
                                <label for="marca">Seleccione un Vendedor</label>

                                <input type="hidden" id="cod_ventas" name="cod_ventas" value="<?php echo $cod_venta + 1; ?>">
                                <select class="col-xl-9" id="vendedor" name="vendedor">
                                    <?php
                                    imprimirSelects('SELECT NOMBRE , APELLIDOS, DNI FROM VENDEDORES');                      
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="modelo">Seleccione un Coche</label>
                                <select class="col-xl-9" id="coche" name="coche">
                                    <?php
                                        imprimirSelects('SELECT Marca , Modelo , VIN FROM coches');
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="año">Seleccione un Cliente</label>
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
