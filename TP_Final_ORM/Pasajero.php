<?php

include_once 'ResponsableV.php';
include_once 'Viaje.php';

class Pasajero {

    private $nombre;
    private $apellido;
    private $dni;
    private $telefono;
    private $viaje;


    public function __construct(){
        $this->nombre = "";
        $this->apellido = "";
        $this->dni = "";
        $this->telefono = "";
        $this->viaje = new Viaje();
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getApellido(){
        return $this->apellido;
    }

    public function getDni(){
        return $this->dni;
    }

    public function getTelefono(){
        return $this->telefono;
    }


    public function getViaje(){
        return $this->viaje;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setApellido($apellido){
        $this->apellido = $apellido;
    }

    public function setDni($dni){
        $this->dni = $dni;
    }

    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }


    public function setViaje($viaje){
        $this->viaje = $viaje;
    }


    /**
     * Setea los atributos del objeto Pasajero
     */
    public function cargarPasajero($nombre, $apellido, $dni, $telefono, $viaje)
    {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setDni($dni);
        $this->setTelefono($telefono);
        $this->setViaje($viaje);
    }


    public function __toString()
    {
        return 
        "\n----------------------------------------\nPasajero: " 
        . $this->getApellido().", ".$this->getNombre().
        "\n----------------------------------------\n".
        "DNI: " . $this->getDni(). 
        "\nTelefono: " . $this->getTelefono()
        . "\n----------------------------------------\n";
    }


    //Metodos ORM


      /**
     * Retorna un array de objetos Pasajero con todos los registros de la tabla Pasajero
     * Acepta un string con una condicion WHERE
     * Si no se pasa condicion, retorna todos los registros de la tabla
     * @param string $cond
     */
    public static function listar($cond = ""){
        $bd = new BaseDatos();
        $pasajeros = array();

        $query = "SELECT * FROM pasajero";
        if($cond != ""){
            $query = $query . " WHERE " . $cond;
        }

        if($bd->Iniciar()){
            if($bd->Ejecutar($query)){
                $pasajeros = array();
                while($row = $bd->Registro()){
                    $pasajero = new Pasajero();
                    $viaje = new Viaje();
                    //Cargo el id de viaje pero no el objeto completo para no crear un bucle de dependencia circular.
                    $viaje->setId($row['idviaje']);
                    $pasajero->cargarPasajero($row['pnombre'], $row['papellido'], $row['pdocumento'], $row['ptelefono'], $viaje);
                    array_push($pasajeros, $pasajero);
                }
            } else{
                throw new Exception("Ocurrió un error al listar los pasajeros. SQL Devolvió: " . $bd->getError());
            }
        }else{
            throw new Exception("Ocurrió un error al intentar conectarse a la base de datos. Error: " . $bd->getError());
        }

        return $pasajeros;

    }


    /**
     * Carga el objeto pasajero desde la db.
     */
    public function cargar(){
        $response = false;
        $bd = new BaseDatos();

        $query = "SELECT * FROM pasajero WHERE pdocumento = " . $this->getDni();

        if($bd->Iniciar()){
            if($bd->Ejecutar($query)){
                if($row = $bd->Registro()){
                    $viaje = new Viaje();
                    $viaje->setId($row['idviaje']);
                    $this->cargarPasajero($row['pnombre'], $row['papellido'], $row['pdocumento'], $row['ptelefono'], $viaje);
                    $response = true;
                } else{
                    throw new Exception("No se encontró el pasajero con DNI: " . $this->getDni());
                }
            } else{
                throw new Exception("Ocurrió un error al cargar el pasajero. SQL Devolvió: " . $bd->getError());
            }
        }else{
            throw new Exception("Ocurrió un error al intentar conectarse a la base de datos. Error: " . $bd->getError());
        }

        return $response;
    }


    /**
     * Inserta un nuevo registro en la tabla Pasajero
     */
    public function insertar(){
        $bd = new BaseDatos();

        if($this->getViaje()->getId() == null){
            throw new Exception("El viaje no está seteado.");
        }


        $query = " INSERT INTO pasajero (pdocumento,pnombre,papellido,ptelefono,idviaje) 
        VALUES ('"
        .$this->getDni().
        "','"
        .$this->getNombre().
        "','"
        .$this->getApellido()
        ."','"
        .$this->getTelefono()
        ."','"
        .$this->getViaje()->getId()
        ."')";

        $response = false;
        if($bd->Iniciar()){
            if($bd->Ejecutar($query)){
                $response = true;
            } else{
                throw new Exception("Ocurrió un error al insertar el pasajero. SQL Devolvió: " . $bd->getError());
            }
        }else{
            throw new Exception("Ocurrió un error al intentar conectarse a la base de datos. Error: " . $bd->getError());
        }
        return $response;
        
    }


    /**
     * Actualiza los datos de un registro de la tabla Pasajero
     */
    public function actualizar(){
        $bd = new BaseDatos();

        $query = "UPDATE pasajero SET pnombre = '" . $this->getNombre() . "', papellido = '" . $this->getApellido() . "', ptelefono = '" . $this->getTelefono() ."', idviaje = '".$this->getViaje()->getId(). "' WHERE pdocumento = '" . $this->getDni() . "'";

        $response = false;
        if($bd->Iniciar()){
            if($bd->Ejecutar($query)){
                $response = true;
            } else{
                throw new Exception("Ocurrió un error al modificar el pasajero. SQL Devolvió: " . $bd->getError());
            }
        }else{
            throw new Exception("Ocurrió un error al intentar conectarse a la base de datos. Error: " . $bd->getError());
        }
        return $response;


    }


    /**
     * Elimina un registro de la tabla Pasajero
     */
    public function eliminar(){
        $bd = new BaseDatos();

        $query = "DELETE FROM pasajero WHERE pdocumento = '" . $this->getDni() . "'";

        $response = false;
        if($bd->Iniciar()){
            if($bd->Ejecutar($query)){
                $response = true;
            } else{
                throw new Exception("Ocurrió un error al eliminar el pasajero. SQL Devolvió: " . $bd->getError());
            }
        }else{
            throw new Exception("Ocurrió un error al intentar conectarse a la base de datos. Error: " . $bd->getError());
        }
        return $response;

    }


  


}