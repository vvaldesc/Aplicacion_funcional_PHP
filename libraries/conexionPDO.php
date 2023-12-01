<?php
include $_SERVER["DOCUMENT_ROOT"]."/Aplicacion_funcional_PHP/libraries/conexion.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/Aplicacion_funcional_PHP/libraries/funciones.php";



//Creo variable global con los parámetros necesarios para la conexión PDO
//$BD = conexionPDO();
function extraerTablas($sql) {
     try {
        $BD = conexionPDO();
        $cursorSql = $BD->prepare($sql);
        if ($cursorSql->execute()) {
            $tabla = $cursorSql->fetchAll();
            return $tabla;
        } else {
            echo "Error en la consulta: " . $BD->error;
        }
    } catch (Exception $exc) {
        mensajeError("Error general ");
    }
}
function comprobarBD(){
    $creada=false;
    try {
        $BD = conexionPDO();
        $sentencia='SHOW DATABASES';
        $sentencia=extraerTablas($sentencia);
        $creada=false;  $i=0;
        while (!$creada && $sentencia!=null) {
            if($sentencia[$i][0]=='concesionario'){
                $creada=true;
            }
            $i++;
        }
            
            //RETORNA UN BOTON EL CUAL CREA LA BD, ESTE BOTÓN ESTÁ DENTRO DEL FORM
            //Y PODRIAMOS USAR UNA VARIABLE POST PARA AVISAR DE QUE SE TIENE QUE CREAR LA BD
            
            
            
            //crearBD($BD);
    } catch (Exception $exc) {
        if($exc->getCode() == 1045){
            mensajeError('Conexión a la base de datos incorrecta, acceso denegado al usuario');
        }
        //Excepción Cuando no está creada la base de datos
        if($exc->getCode() == 1049){
            //mensajeError('No existe la base de datos en el sistema');
            return false;
        }
        else{
            mensajeError('Error comprobando BD');
        }
    }
    
    $BD=null;
    return true;
}
//Creación de tablas inciciales
function crearBD() {
    
        //FALTA AÑADIR ALGUNOS PREPARES, CREO QUE SOLO HAY UNO
        //NO ESTARÍA MAL HACER UNA FUNCION DE ACTUALIZAR COLUMNAS DE UN REGISTRO (USAR EXTRAERTABLAS() DENTRO DE ESTA FUNCIÓN SERÍA LO SUYO)
        //Y SUS RESPECTIVAS EXCEPCIONES
    
        $BD = new PDO("mysql:host=localhost", 'root', '');
        try {
            //En insertar la letra ñ da error (puede ser la función bindValues)
            $BD->exec('CREATE DATABASE concesionario');
            

            crearTabla("vendedores", array("DNI" => "varchar(20)", "Nombre" => "varchar(20)", "Apellidos" => "varchar(20)", "FechaAlta" => "DATE", "FechaNac" => "DATE",
            "Rol" => "varchar(20)", "contrasena" => "varchar(100)", 'Email' => 'varchar(100)'), array("DNI"));

            insertar("vendedores", array("DNI" => "06293360P", "Nombre" => "Javier", "Apellidos" => "Diaz", "FechaAlta" => "2023-11-13", "FechaNac" => "2004-10-01", "Rol" => "junior", "contrasena" => 'javier1234', 'Email' => 'javierdiazmolina@yopmail.com'));
            insertar("vendedores", array("DNI" => "06293362X", "Nombre" => "Victor", "Apellidos" => "Valdes", "FechaAlta" => "2023-11-11", "FechaNac" => "2001-03-13", "Rol" => "admin", "contrasena" => 'victor1234', 'Email' => 'victorvaldescobos@yopmail.com'));
            insertar("vendedores", array("DNI" => "75143313J", "Nombre" => "VictorNoAdmin", "Apellidos" => "Valdes", "FechaAlta" => "2023-11-11", "FechaNac" => "2001-03-13", "Rol" => "", "contrasena" => 'victor1234', 'Email' => 'victorvaldescobos@yopmail.com'));

            crearTabla("coches", array("VIN" => "varchar(20)", "Matricula" => "varchar(20)", "Marca" => "varchar(20)", "Modelo" => "varchar(20)", "Ano" => "varchar(20)", "Precio" => "integer", "Km" => 'integer'), array("VIN"));
            insertar("coches", array("VIN" => "JH4DC4400SS012345", "Matricula" => "3467-LKF", "Marca" => "Ford", "Modelo" => "Fiesta", "Ano" => 2007, "Precio" => 2500, "Km" => 100000));
            insertar("coches", array("VIN" => "KLATF08Y1VB363633", "Matricula" => "0493-HGS", "Marca" => "Ferrari", "Modelo" => "Roma", "Ano" => 2017, "Precio" => 200500, "Km" => 80000));

            crearTabla("clientes", array("DNI" => "varchar(20)", "Nombre" => "varchar(20)", "Apellidos" => "varchar(20)", "Domicilio" => "varchar(20)", "FechaNac" => "DATE"), array("DNI"));
            insertar("clientes", array("DNI" => "07328669H", "Nombre" => "Rodrigo", "Apellidos" => "Pérez", "Domicilio" => "Calle Fernandez De los Rios, 9", "FechaNac" => "2000-04-11"));
            insertar("clientes", array("DNI" => "03069726P", "Nombre" => "Alejandro", "Apellidos" => "Sánchez", "Domicilio" => "Calle Sol, 8", "FechaNac" => "2002-08-19"));

            crearTabla('ventas', array('COD_VENTAS' => 'varchar(20)'), array('COD_VENTAS'));
            anadirForanea('ventas', 'DNI', 'vendedores');
            anadirForanea('ventas', 'VIN', 'coches');
            anadirForanea('ventas', 'DNI', 'clientes');
            insertar('ventas', array('COD_VENTAS' => '1', "DNI_vendedores" => "06293360P", "VIN_coches" => "KLATF08Y1VB363633", "DNI_clientes" => "07328669H"));
            insertar('ventas', array('COD_VENTAS' => '2', "DNI_vendedores" => "06293362X", "VIN_coches" => "JH4DC4400SS012345", "DNI_clientes" => "03069726P"));

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
}

//valores es un array asociativo columna => valor
function insertar($tabla, $valores) {

    $result = extraerTablas("SHOW TABLES LIKE '$tabla'");

    if (count($result) == 1) {

 
        if (isset($valores['DNI'])) {
            if (!validarDNI($valores['DNI'])) {
                throw new Exception(mensajeError("(insertar): DNI no válido."));
            }
            if (isset($valores['contrasena'])) {
                if (validarContraseña($valores['contrasena'])) {
                    $valores['contrasena']=hash('sha256', $valores['contrasena']);
                }else{
                    throw new Exception(mensajeError("(insertar): Error la contraseña no es válida."));
                }
            }            
        }else{
            if (isset($valores['VIN'])) {
                if (!validarVIN($valores['VIN'])) {
                    throw new Exception(mensajeError("(insertar): VIN no válido."));
                }
                if (isset($valores['Matricula'])) {
                    if (!validarMatricula($valores['Matricula'])) {
                    throw new Exception(mensajeError("(insertar): Matrícula no válida."));
                    }
                }
            }            
        }
            //Parámetros en caso de que no haya
    try {
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

            if (!$stmt->execute()) {
                throw new Exception(mensajeError("(insertar): Error en la inserción del registro."));
            } 
        } catch (Exception $exc) {
            echo mensajeError('No se puede insertar el registro, porque la clave principal está repetida.');
        }
        } else {
            throw new Exception(mensajeError("(insertar): La tabla $tabla no existe, no es posible insertar."));
        }
}

function modificarTabla($tabla, $dato, $valor) {
    $BD = conexionPDO();

    try {
        
        for ($i=0;$i<count($dato);$i++){
            $sql ="UPDATE $tabla SET ". $dato[$i][0]." = '".$_POST[$dato[$i][0]]."' WHERE ".$dato[0][0]." = '".$_POST[$dato[0][0]]."'";
            $stmt = $BD->prepare($sql);
            $stmt->execute();
        }

        

    } catch (Exception $e) {
        throw new Exception('¡Algo salió mal!');
    }
}
function modificacionCheck(){
    echo "<nav class='navbar bg-body-tertiary bg-success rounded m-2'>
            <div class='container-fluid'>
                <p>
                    'Se ha modificado la tabla correctamente '
                </p>
            </div>
        </nav>'";
}

function eliminarDatos($tabla,$dato,$valor){
    $BD = conexionPDO();
    try {
        
        $sql ="DELETE FROM $tabla WHERE $dato = '".$valor."'";
        $stmt = $BD->prepare($sql);

        $stmt->execute();

        echo "<nav class='navbar bg-body-tertiary bg-success rounded m-2'>
            <div class='container-fluid'>
                <p>
                    'Se ha eliminado la columna correctamente '
                </p>
            </div>
        </nav>'";
    } catch (Exception ) {
        echo "<nav class='navbar bg-body-tertiary bg-danger rounded m-2'>
            <div class='container-fluid'>
                <p>
                    'No se puede eliminar este valor, porque es foranea de otra.'
                </p>
            </div>
        </nav>'";
    }
}
