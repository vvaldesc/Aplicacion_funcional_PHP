<?php

$cadena_conexion = 'mysql:dbname=<base de datos>;host=<ip o nombre>';
//public PDO::__construct("driver","usr","pass","array_opcional");



try {
    $BD=new PDO($cadena_conexion, $username, $password);
    
    $BD->closeCursor();
    
    $BD = null;
} catch (Exception $exc) {
    echo $exc->getMessage();
}


