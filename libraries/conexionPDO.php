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
function comprobarBD(){
    try {
        $BD = conexionPDO();
        $result = extraerTablas("SHOW TABLES");
        if (count($result) < 3 ) {
            crearBD();
        }   
    } catch (Exception $exc) {
        if($exc->getCode() == 1045){
            echo 'Conexión a la base de datos incorrecta, acceso denegado al usuario';
        }
        if($exc->getCode() == 1049){
            echo 'No existe la base de datos en el sistema';
        }
        else{
            echo $exc->getMessage();
        }
    }
    $BD=null;
}
//Creación de tablas inciciales
function crearBD() {
    
        //FALTA AÑADIR ALGUNOS PREPARES, CREO QUE SOLO HAY UNO
        //NO ESTARÍA MAL HACER UNA FUNCION DE ACTUALIZAR COLUMNAS DE UN REGISTRO (USAR EXTRAERTABLAS() DENTRO DE ESTA FUNCIÓN SERÍA LO SUYO)
        //Y SUS RESPECTIVAS EXCEPCIONES
    
    
        try {
            //En insertar la letra ñ da error (puede ser la función bindValues)
            eliminarTabla('clientes','VIN_coches');
            eliminarTabla('coches','DNI_vendedores');
            eliminarTabla('vendedores');
            
            crearTabla("vendedores", array("DNI" => "varchar(20)", "Nombre" => "varchar(20)","Apellidos" => "varchar(20)","FechaAlta" => "DATE","FechaNac" => "DATE",
                "Rol" => "varchar(20)","contrasena" => "varchar(20)"), array("DNI"));
            insertar("vendedores", array("DNI" => "06293364H", "Nombre" => "Javier","Apellidos" => "Diaz","FechaAlta"=>"2023-11-13","FechaNac"=>"2004-10-01", "Rol" => "junior","contrasena"=>"52f87a36d63aaaeb8e413bd8498b3d8d7918af494b20ded56c16cc03e8eb27e7"));
            insertar("vendedores", array("DNI" => "03245754K", "Nombre" => "Victor","Apellidos" => "Valdes","FechaAlta"=>"2023-11-11","FechaNac"=>"2001-03-13", "Rol" => "admin","contrasena"=>"29bb72f3aa2d13f4c0da08cda282f6dce2edf9ef58e800123effc5666059351b"));
            
            
            crearTabla("coches", array("VIN" => "varchar(20)", "Matricula" => "varchar(20)", "Marca" => "varchar(20)", "Modelo" => "varchar(20)", "Ano" => "varchar(20)", "Precio" => "integer", "Km" => 'integer'), array("VIN"));
            anadirForanea('coches', 'DNI', 'vendedores');
            insertar("coches", array("VIN" => "23456GFDB", "Matricula" => "3467LKF","Marca" => "Ford", "Modelo" => "Fiesta", "Ano" => 2007, "Precio" => 2500, "Km" => 100000,"DNI_vendedores"=> "06293364H"));
            insertar("coches", array("VIN" => "23456YHUS", "Matricula" => "0493HGS","Marca" => "Ferrari", "Modelo" => "Roma", "Ano" => 2017, "Precio" => 200500, "Km" => 80000,"DNI_vendedores"=> "03245754K"));
            
            
            crearTabla("clientes", array("DNI" => "varchar(20)", "Nombre" => "varchar(20)","Apellidos" => "varchar(20)","Domicilio" => "varchar(20)","FechaNac" => "DATE"), array("DNI"));
            anadirForanea('clientes', 'VIN', 'coches');
            insertar("clientes", array("DNI" => "05245677L", "Nombre" => "Rodrigo","Apellidos" => "Pérez","Domicilio" => "Calle Fernandez De los Rios, 9","FechaNac"=>"2000-04-11","VIN_coches" => "23456GFDB"));
            insertar("clientes", array("DNI" => "12304964Y", "Nombre" => "Alejandro","Apellidos" => "Sánchez","Domicilio" => "Calle Sol, 8","FechaNac"=>"2002-08-19","VIN_coches" => "23456YHUS"));        
            
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
}
//Añadir Foranea
function anadirForanea($tabla,$foranea,$tablaForanea){
    $BD = conexionPDO();
    $sql = "ALTER TABLE $tabla
            ADD COLUMN ".$foranea."_".$tablaForanea." varchar(20) NOT NULL,
            ADD CONSTRAINT fk_".$foranea."_".$tablaForanea." FOREIGN KEY (".$foranea."_".$tablaForanea.")
            REFERENCES $tablaForanea ($foranea)";
    
    $stmt = $BD->prepare($sql);
    $stmt->execute();
    
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

function eliminarTabla($tabla,$fk = null) {

    $BD = conexionPDO();
    $result = extraerTablas("SHOW TABLES LIKE '".$tabla."'");
    if (count($result) == 1) {
        //$BD= conexionPDO();
        //$BD = conexionPDO();
        if($fk!=null){
            $sql="ALTER TABLE ".$tabla." DROP FOREIGN KEY fk_$fk";
            $stmt = $BD->exec($sql);
        }
        $sql = "DROP TABLE " . $tabla . ";";
        $stmt = $BD->exec($sql);
    }
    $BD=null;
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
