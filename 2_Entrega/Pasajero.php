<?php


class Pasajero {

    private $nombre;
    private $apellido;
    private $dni;
    private $telefono;

    public function __construct($nombre,$apellido,$dni,$telefono)
    {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setDni($dni);
        $this->setTelefono($telefono);
        
    }

    //Metodos de acceso.
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


    public function __toString(){
        return "\n----------------------------------------\nPasajero: ".$this->getApellido(). ", ".$this->getNombre(). "\nDNI: ". $this->getDni(). "\nTelefono: ".$this->getTelefono() . "\n----------------------------------------\n";
    }


}