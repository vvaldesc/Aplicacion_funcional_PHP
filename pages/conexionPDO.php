<?php

function conexionPDO(){

$cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
//public PDO::__construct("driver","usr","pass","array_opcional");
$username = 'usrConcesionario';
$password = 'pbazEMdm)vf/d43_';



try {
    $BD=new PDO($cadena_conexion, $username, $password);
    $BD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Consulta para ver el numero de tablas en concesionario a modo de prueba
    //$BD->closeCursor();
    
    $sql="SHOW TABLES";
    $tablas = $BD->query($sql);
    $instancias = $tablas->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($instancias as $fila){
        echo "tabla";
    }
    
    //echo count($tablas);
    
    echo "Conexion";
    
    
    $BD = null;
} catch (Exception $exc) {
    echo $exc->getMessage();
}
}

