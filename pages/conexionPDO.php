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
    
    crearTabla($BD);
    
    $sql="SHOW TABLES";
    $tablas = $BD->query($sql);
    $instancias = $tablas->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($instancias as $fila){
        echo "Tabla ".$fila;
    }
        
    echo "Conexion correcta";
    
    eliminarTabla($BD);
    
    $BD = null;
} catch (Exception $exc) {
    echo $exc->getMessage();
}
}


function crearTabla($BD) {
    $sql = "CREATE TABLE PRUEBA (columna1 varchar(20));";
    $stmt = $BD->exec($sql);
}

function eliminarTabla($BD) {
    $sql = "DROP TABLE PRUEBA;";
    $stmt = $BD->exec($sql);
}

