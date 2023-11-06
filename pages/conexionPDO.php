<?php

function conexionPDO($sql, $BD = null) {

    try {
        if ($BD == null) {
            $cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
            //public PDO::__construct("driver","usr","pass","array_opcional");
            $username = 'usrConcesionario';
            $password = 'pbazEMdm)vf/d43_';
            $BD = new PDO($cadena_conexion, $username, $password);
        }


        $tablas = $BD->query($sql);
        if ($tablas) {
            $instancias = Array();
            foreach ($tablas as $row) {
                $instancias[] = $row;
            }
            return $instancias;
        } else {
            echo "Error en la consulta: " . $conn->error;
        }

        $BD = null;
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}

function crearBD() {

    $cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
    //public PDO::__construct("driver","usr","pass","array_opcional");
    $username = 'usrConcesionario';
    $password = 'pbazEMdm)vf/d43_';
    $BD = new PDO($cadena_conexion, $username, $password);

    crearTabla("coches", array("Marca" => "varchar(20)", "Modelo" => "varchar(20)", "Ano" => "varchar(20)", "Precio" => "integer"), $BD);
    insertar("coches", array("Marca" => "Ford", "Modelo" => "Fiesta", "Ano" => 2007, "Precio" => 2500), $BD);
}

//LES PUEDES PASAR LOS PARÁMETROS BD O NO HACERLO, ES PREFERIBLE PASARLO
function crearTabla($tabla, $columnas, $BD = null) {

    try {

        //Compruebo si existe la tabla, esto no funciona bien
        
        /*$sentencia = "SELECT * FROM COCHES";
        $instancias = conexionPDO($sentencia); //aqui debo comprobar si existe la tabla a crear
        
        if (count($instancias)==0) {
            echo "existe";*/
            
        //} else {

            $columasSql = "";
            $tiposSql = "";

            foreach ($columnas as $columna => $tipo) {
                $columasSql .= $columna . " " . $tipo . ",";
            }

            $columasSql = substr($columasSql, 0, -1);
            //$tiposSql = substr($tiposSql, 0, -2);

            if ($BD == null) {
                $cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
                //public PDO::__construct("driver","usr","pass","array_opcional");
                $username = 'usrConcesionario';
                $password = 'pbazEMdm)vf/d43_';
                $BD = new PDO($cadena_conexion, $username, $password);
            }


            $sql = "CREATE TABLE " . $tabla . " (" . $columasSql . ");";
            $stmt = $BD->exec($sql);
        //}
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}

function eliminarTabla($tabla, $BD = null) {


    try {
        if ($BD == null) {
            $cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
            //public PDO::__construct("driver","usr","pass","array_opcional");
            $username = 'usrConcesionario';
            $password = 'pbazEMdm)vf/d43_';
            $BD = new PDO($cadena_conexion, $username, $password);
        }
        $sql = "DROP TABLE " . $tabla . ";";
        $stmt = $BD->exec($sql);
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}

//valores es un array asociativo columna => valor


function insertar($tabla, $valores, $BD = null) {



    try {

        if ($BD == null) {
            $cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
            //public PDO::__construct("driver","usr","pass","array_opcional");
            $username = 'usrConcesionario';
            $password = 'pbazEMdm)vf/d43_';
            $BD = new PDO($cadena_conexion, $username, $password);
        }
        
        //para mostrar errores
        $BD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $columasSql = "";
        $valoresSql = "";

        foreach ($valores as $clave => $valor) {
            $columasSql .= $clave . ", ";
            $valoresSql .= ":" . $clave . ", ";
        }

        $columasSql = substr($columasSql, 0, -2);
        $valoresSql = substr($valoresSql, 0, -2);

        $sql = "INSERT INTO " . $tabla . " (" . $columasSql . ") VALUES (" . $valoresSql . ");";
        $stmt = $BD->prepare($sql);

        foreach ($valores as $clave => $valor) {
            // Use the correct named placeholders
            $stmt->bindValue(":" . $clave, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . $stmt->errorInfo()[2];
        }
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}
