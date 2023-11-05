<?php

function conexionPDO(){

$cadena_conexion = 'mysql:dbname=Concesionario;host=localhost';
//public PDO::__construct("driver","usr","pass","array_opcional");
$username = 'usrConcesionario';
$password = 'pbazEMdm)vf/d43_';



try {
    $BD=new PDO($cadena_conexion, $username, $password);
    $BD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Consulta para ver el numero de tablas en concesionario a modo de prueba
    //$BD->closeCursor();
    $sql ="SELECT COUNT(*) as tablas_totales from concesionario where table_schema =: concsionario";
    $stmt = $BD->query($sql);
    $stmt -> execute();
    print_r($stmt);
    
    
    $BD = null;
} catch (Exception $exc) {
    echo $exc->getMessage();
}
}

