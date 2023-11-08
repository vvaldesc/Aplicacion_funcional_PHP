<?php
    $cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
    $username = 'usrConcesionario';
    $password = 'pbazEMdm)vf/d43_';

function conexionPDO(){
    global $cadena_conexion,$username,$password;
    return new PDO($cadena_conexion, $username, $password);
}

function nullPDO(){
    $BD = null;
}