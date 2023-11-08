<?php
include $_SERVER["DOCUMENT_ROOT"]."/Aplicacion_funcional_PHP/libraries/conexion.php";
include $_SERVER["DOCUMENT_ROOT"]."/Aplicacion_funcional_PHP/libraries/funciones.php";



//Creo variable global con los parámetros necesarios para la conexión PDO
//$BD = conexionPDO();


function extraerTablas($sql) {
    try {
        $BD = conexionPDO();
        $cursorSql = $BD->query($sql);
        if ($cursorSql) {
            $tabla = Array();
            foreach ($cursorSql as $row) {
                $tabla[] = $row; //podría ser un array_push?
            }
            return $tabla;
            //esto podría ser una excepción
        } else {
            echo "Error en la consulta: " . $BD->error;
        }
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }
}

//Creación de tablas inciciales
function crearBD() {
    
        //FALTA AÑADIR ALGUNOS PREPARES, CREO QUE SOLO HAY UNO
        //NO ESTARÍA MAL HACER UNA FUNCION DE ACTUALIZAR COLUMNAS DE UN REGISTRO (USAR EXTRAERTABLAS() DENTRO DE ESTA FUNCIÓN SERÍA LO SUYO)
        //Y SUS RESPECTIVAS EXCEPCIONES
    
    
        try {
            eliminarTabla("coches");
            crearTabla("coches", array("VIN" => "varchar(20)", "Marca" => "varchar(20)", "Modelo" => "varchar(20)", "Ano" => "varchar(20)", "Precio" => "integer"), array("VIN"));
            insertar("coches", array("VIN" => "23456GFDB", "Marca" => "Ford", "Modelo" => "Fiesta", "Ano" => 2007, "Precio" => 2500));
            
            //En insertar la letra ñ da error (puede ser la función bindValues)
            eliminarTabla("usuarios");
            crearTabla("usuarios", array("Usuario" => "varchar(20)", "Contrasena" => "varchar(20)", "Rol" => "varchar(20)"), array("Usuario"));
            insertar("usuarios", array("Usuario" => "vvaldesc", "Contrasena" => "12345", "Rol" => "junior"));
            insertar("usuarios", array("Usuario" => "jdiazm", "Contrasena" => "admin", "Rol" => "admin"));
           
            eliminarTabla("vendedores");
            crearTabla("vendedores", array("Usuario" => "varchar(20)", "Contrasena" => "varchar(20)", "Rol" => "varchar(20)"), array("Usuario"));
            insertar("vendedores", array("Usuario" => "vvaldesc", "Contrasena" => "12345", "Rol" => "junior"));
            insertar("vendedores", array("Usuario" => "jdiazm", "Contrasena" => "admin", "Rol" => "jefe"));            
            
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
}

//javi no me borres esto
function crearTabla($tabla, $columnas, $primaryKeys=array()) {


    //$BD= conexionPDO();
    $BD = conexionPDO();

    $result = extraerTablas("SHOW TABLES LIKE '$tabla'");
    if (count($result)==0) {
        $columnasSql = "";
        foreach ($columnas as $column => $tipo) {
            if (!empty($primaryKeys)) {
                $enc=false;     $i=0;
                while (!$enc && $i<count($primaryKeys)) {
                    if ($primaryKeys[$i]==$column) {
                        $tipo.=" PRIMARY KEY";
                        unset($primaryKeys[$i]);
                        $primaryKeys= array_values($primaryKeys);
                    }

                }
                $i++;
            }
            $columnasSql .= "" . $column . " " . $tipo . ", ";
        }
        
        //Esta función elimina la ultima coma
        $columnasSql = rtrim($columnasSql, ', ');
        $sql = "CREATE TABLE " . $tabla . " (".$columnasSql.")";
        $BD->exec($sql);
    } else {
        echo "La tabla $tabla ya existe, no es necesario crearla.";
        //throw new Exception(mensajeError("(crearTabla): Todos los parámetros tienen que ser numéricos"));
    }
}

function eliminarTabla($tabla) {

    $BD = conexionPDO();
    $result = extraerTablas("SHOW TABLES LIKE '$tabla'");
    if (count($result) == 1) {
        //$BD= conexionPDO();
        //$BD = conexionPDO();
        $sql = "DROP TABLE " . $tabla . ";";
        $stmt = $BD->exec($sql);
    }
}

//valores es un array asociativo columna => valor
function insertar($tabla, $valores) {

    $result = extraerTablas("SHOW TABLES LIKE '$tabla'");

    if (count($result) == 1) {

        //Parámetros en caso de que no haya
        //$BD= conexionPDO();
        $BD = conexionPDO();

        //para mostrar errores
        $BD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $columnasSql = implode(", ", array_keys($valores));
        $valoresSql = ":" . implode(", :", array_keys($valores));

        $sql = "INSERT INTO " . $tabla . " (" . $columnasSql . ") VALUES (" . $valoresSql . ");";

        //stmt se convierte en un array

        $stmt = $BD->prepare($sql);

        foreach ($valores as $clave => $valor) {
            // Esto sustituye las claves por sus respectivas columnas
            $stmt->bindValue(":" . $clave, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            echo "Registro insertado con éxito.";
        } else {
            throw new Exception(mensajeError("(insertar): Error en la inserción del registro."));
        }
    } else {
        throw new Exception(mensajeError("(insertar): La tabla $tabla no existe, no es posible insertar."));
    }
}
