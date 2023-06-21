<?php

class Empresa {

    private $id;
    private $nombre;
    private $direccion;
    private $viajes;

    public function __construct(){
        $this->id = "";
        $this->nombre = "";
        $this->direccion = "";
        $this->viajes = array();
    }

    public function cargarEmpresa($id, $nombre, $direccion){
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDireccion($direccion);
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function setDireccion($direccion){
        $this->direccion = $direccion;
    }

    public function getDireccion(){
        return $this->direccion;
    }

    public function setViajes($viajes){
        $this->viajes = $viajes;
    }

    public function getViajes(){
        return $this->viajes;
    }


    //Metodos ORM

       /**
     * Retorna un array de objetos Empresa
     */
    public static function listar($cond = ""){
        $base=new BaseDatos();
        $query="SELECT * FROM empresa";
        if ($cond!=""){
            $query.=" WHERE ".$cond;
        }
        $arrayEmpresas = array();
        if($base->Iniciar()){
            if($res=$base->Ejecutar($query)){
                while($row=$base->Registro()){
                    $empresa = new Empresa();
                    $empresa->cargarEmpresa($row['idempresa'],$row['enombre'],$row['edireccion']);
                    array_push($arrayEmpresas,$empresa);
                }
            }  
        }else{
           throw new Exception("Error en la conexion a la base de datos ".$base->getError());
        }
        return $arrayEmpresas;
    }



    /**
     * Cargar el objeto desde la base de datos.
     */
    public function cargar(){
        $base=new BaseDatos();
        $query="SELECT * FROM empresa WHERE idempresa=".$this->getId();

        if($this->getId()==null){
            throw new Exception("No se puede cargar la empresa desde BD porque el objeto no tiene un id seteado.");
        }
        if($base->Iniciar()){
            if($res=$base->Ejecutar($query)){
                $row=$base->Registro();
                $this->setNombre($row['enombre']);
                $this->setDireccion($row['edireccion']);
            }  
        }else{
           throw new Exception("Error en la conexión a la base de datos ".$base->getError());
        }
       
    }


    /**
     * Inserta un nuevo registro en la tabla Empresa
     */
    public function insertar(){
        $conexion = new BaseDatos();
        $query = " INSERT INTO empresa (enombre,edireccion)
        VALUES ('"
        .$this->getNombre().
        "','"
        .$this->getDireccion().
        "')";
        $response = false;
        if($conexion->Iniciar()){
            if($id = $conexion->devuelveIDInsercion($query)){
                $this->setId($id);
                $response = true;
            } else{
                throw new Exception("Ocurrió un error al insertar la empresa. SQL Devolvió: " . $conexion->getError());
            }
        }else{
            throw new Exception("Ocurrió un error al intentar conectarse a la base de datos. Error: " . $conexion->getError());
        }
        return $response;
    }


    /**
     * Modifica un registro de la tabla Empresa
     */
    public function actualizar(){
        $conexion = new BaseDatos();
        $query = "UPDATE empresa SET enombre='".$this->getNombre()."', edireccion='".$this->getDireccion()."' WHERE idempresa=".$this->getId();
        $response = false;
        if($conexion->Iniciar()){
            if($conexion->Ejecutar($query)){
                $response = true;
            } else{
                throw new Exception("Ocurrió un error al modificar la empresa. SQL Devolvió: " . $conexion->getError());
            }
        }else{
            throw new Exception("Ocurrió un error al intentar conectarse a la base de datos. Error: " . $conexion->getError());
        }
        return $response;
    }

    /**
     * Elimina un registro de la tabla Empresa
     */
     public function eliminar(){
        $conexion = new BaseDatos();
        $query = "DELETE FROM empresa WHERE idempresa=".$this->getId();
        $response = false;
        if($conexion->Iniciar()){
            if($conexion->Ejecutar($query)){
                $response = true;
            } else{
                throw new Exception("Ocurrió un error al eliminar la empresa. SQL Devolvió: " . $conexion->getError());
            }
        }else{
            throw new Exception("Ocurrió un error al intentar conectarse a la base de datos. Error: " . $conexion->getError());
        }
        return $response;
     }

 

     /**
      * Retorna un string con los viajes de la empresa
      */
     public function mostrarViajes(){
        $arrViajes = $this->getViajes();
        $strViajes = "";
        foreach($arrViajes as $viaje){
            $strViajes .= 
            "\nID: " . $viaje->getId() . " - DESTINO: " . $viaje->getDestino() . " - OCUPACIÓN: " . count($viaje->getPasajeros()) . "/" . $viaje->getCantMaxPasajeros() . " - IMPORTE: $" . $viaje->getImporte() ." - RESPONSABLE: ".$viaje->getResponsableV() . "\n";
        }
        return $strViajes;
     }



    public function __toString()
    {
        return "\n----------------------------------------\n" 
        ."ID:".$this->getId(). " - ". $this->getNombre() .
        "\n----------------------------------------\n".
        "Direccion: " . $this->getDireccion(). "\nViajes:\n" . $this->mostrarViajes()
        . "\n----------------------------------------\n";
    }



}