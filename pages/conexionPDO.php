<?php

function conexionPDO($sql){

$cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
//public PDO::__construct("driver","usr","pass","array_opcional");
$username = 'usrConcesionario';
$password = 'pbazEMdm)vf/d43_';


try {
    $BD=new PDO($cadena_conexion, $username, $password);

    //Consulta para ver el numero de tablas en concesionario a modo de prueba
    //$BD->closeCursor();
    
    $tablas = $BD->query($sql);
    if ($tablas) {
        $coches = Array();
        foreach ($tablas as $row) {
            $coches[]=$row;
        }
    } else {
        echo "Error en la consulta: " . $conn->error;
    }
    return $coches;
    
    
    $BD = null;
} catch (Exception $exc) {
    echo $exc->getMessage();
}
}



function crearTabla($BD,$tabla) {
    $sql = "CREATE TABLE ".$tabla." (columna1 varchar(20));";
    $stmt = $BD->exec($sql);
}

function eliminarTabla($BD,$tabla) {
    $sql = "DROP TABLE ".$tabla.";";
    $stmt = $BD->exec($sql);
}

//valores es un array asociativo columna => valor


function insertar($BD,$tabla,$valores) {
    
$columasSql = "";
$valoresSql = "";
    
    foreach ($valores as $clave => $valor){
        $columasSql.=$clave.", ";
        $valoresSql.=":".$clave.", ";
    }
    
$columasSql = substr($columasSql, 0, -2);
$valoresSql = substr($valoresSql, 0, -2);

    
    
    $sql = "INSERT INTO ".$tabla." (".$columasSql.") VALUES (".$valoresSql.");";
    $stmt = $BD->prepare($sql);
    
    
    foreach ($valores as $clave => $valor) {
        $stmt->bindParam(":" . $clave, $valor, PDO::PARAM_STR);
    }

    if ($stmt->execute()) {
        echo "Registro insertado con éxito.";
    } else {
        echo "Error al insertar el registro: " . $stmt->errorInfo()[2];
    }}

