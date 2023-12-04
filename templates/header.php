
<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <ul class="nav nav-pills">
        <?php
        $enlaces = [
            'Clientes' => 'gestionClientes.php',
            'Coches' => 'gestionCoches.php',
            'Inicio' => 'homepage.php',
            'Ventas' => 'gestionVentas.php',
            'Empleados' => 'gestionEmpleados.php',
        ];
        // Obtén el nombre del archivo actual sin la ruta
        $nombreArchivo = basename($_SERVER['PHP_SELF']);
        // Genera dinámicamente los enlaces
        $disabled='';
        if($_SESSION['rol']!='admin'){
            array_shift($enlaces);
            array_pop($enlaces);
        }
        foreach ($enlaces as $texto => $ruta) {
            $claseActiva = ($nombreArchivo === $ruta) ? 'active' : '';
            echo '<li class="nav-item"><a href="' . $ruta . '" class="nav-link ' . $claseActiva . '">' . $texto . '</a></li>';
        }
        echo '<li class="nav-item"><a class="nav-link" href="homepage.php?logOut=true">
                                <p class="mx-2 text-danger">Cerrar sesión</p>
                            </a></li>';
        ?>
    </ul>
</header>