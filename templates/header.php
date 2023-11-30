
<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <ul class="nav nav-pills">
        <?php
        $enlaces = [
            'Coches' => 'gestionCoches.php',
            'Clientes' => 'gestionClientes.php',
            'Inicio' => 'homepage.php',
            'Empleados' => 'gestionEmpleados.php',
            'Ventas' => 'gestionVentas.php',
        ];

        // Obtén el nombre del archivo actual sin la ruta
        $nombreArchivo = basename($_SERVER['PHP_SELF']);
        
        // Genera dinámicamente los enlaces
        foreach ($enlaces as $texto => $ruta) {
            $claseActiva = ($nombreArchivo === $ruta) ? 'active' : '';
            echo '<li class="nav-item"><a href="' . $ruta . '" class="nav-link ' . $claseActiva . '">' . $texto . '</a></li>';
        }
        ?>
    </ul>
</header>
