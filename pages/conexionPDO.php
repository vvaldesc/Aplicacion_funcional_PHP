<?php

function conexionPDO(){
    $cadena_conexion = 'mysql:dbname=concesionario;host=localhost';
    //public PDO::__construct("driver","usr","pass","array_opcional");
    $username = 'usrConcesionario';
    $password = 'pbazEMdm)vf/d43_';
    $BD = new PDO($cadena_conexion, $username, $password);
    return $BD;
}
function extraerTablas($sql) {
    try {
        $BD= conexionPDO();
        $tablas = $BD->query($sql);
        if ($tablas) {
            $instancias = Array();
            foreach ($tablas as $row) {
                $instancias[] = $row;
            }
            return $instancias;
        } else {
            echo "Error en la consulta: " . $BD->error;
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}
function crearBD()  {
    try{
        $BD= conexionPDO();
        $coches='coches';
        $tablas = $BD->query("SHOW TABLES LIKE '$coches'");
        if ($tablas->rowCount() == 0) {
                crearTabla($coches, array("Marca" => "varchar(20)", "Modelo" => "varchar(20)", "Ano" => "varchar(20)", "Precio" => "integer"), $BD);
                insertar($coches, array("Marca" => "Ford", "Modelo" => "Fiesta", "Ano" => 2007, "Precio" => 2500), $BD);
        } else {
            echo "La tabla $coches ya existe, no es necesario crearla.";
        }
    }catch(Exception $exc){
        echo $exc->getMessage();
    }
    
}
function crearTabla($tabla, $columnas) {
    try {
        $BD= conexionPDO();
        $result = $BD->query("SHOW TABLES LIKE '$tabla'");
        if ($result->rowCount() == 0) {
            $columnasSql = "";
            foreach ($columnas as $column => $tipo) {
                $columnasSql .= "".$column." ".$tipo.", ";
            }
            //Esta función elimina la ultima coma
            $columnasSql= rtrim($columnasSql, ', ');
            $sql = "CREATE TABLE $tabla ($columnasSql)";
            $BD->exec($sql);
        } else {
            echo "La tabla $tabla ya existe, no es necesario crearla.";
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}

function eliminarTabla($tabla) {


    try {
        $BD= conexionPDO();
        $sql = "DROP TABLE " . $tabla . ";";
        $stmt = $BD->exec($sql);
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}

//valores es un array asociativo columna => valor


function insertar($tabla, $valores) {

    try {
        //Parámetros en caso de que no haya
        $BD= conexionPDO();
        //para mostrar errores
        $BD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //dos futuras cadenas que contendran la parte de columnas y de valores con la sintaxis sql
        //$columasSql = "";
        //$valoresSql = "";

        /*foreach ($valores as $clave => $valor) {
            $columasSql .= $clave . ", ";
            $valoresSql .= ":" . $clave . ", ";
        }*/

        //Quito el " ," del final
        //$columasSql = substr($columasSql, 0, -2);
        //$valoresSql = substr($valoresSql, 0, -2);
        
        //RESULTA QUE IMPLODE HACE LO QUE ME HE TIRADO DOS HORAS HACIENDO
        $columnasSql = implode(", ", array_keys($valores));
        $valoresSql = ":" . implode(", :", array_keys($valores));

        
        $sql = "INSERT INTO " . $tabla . " (" . $columnasSql. ") VALUES (" . $valoresSql . ");";
        
        //stmt se convierte en un array
        $stmt = $BD->prepare($sql);

        
        foreach ($valores as $clave => $valor) {
            // Esto sustituye las claves por sus respectivas columnas
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
