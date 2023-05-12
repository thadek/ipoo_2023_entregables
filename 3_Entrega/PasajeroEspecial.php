<?php

include_once("Pasajero.php");

class PasajeroEspecial extends Pasajero {

 private $asistenciaEmbarque;
 private $sillaDeRuedas;
 private $comidaEspecial;

 public function __construct($nombre, $nroAsiento, $nroTicket, $asistenciaEmbarque, $sillaDeRuedas, $comidaEspecial)
 {
     // Llamamos al constructor de la clase padre
     parent::__construct($nombre, $nroAsiento, $nroTicket);
     $this->asistenciaEmbarque = $asistenciaEmbarque;
     $this->sillaDeRuedas = $sillaDeRuedas;
     $this->comidaEspecial = $comidaEspecial;
 }

    public function getAsistenciaEmbarque()
    {
        return $this->asistenciaEmbarque;
    }

    public function getSillaDeRuedas()
    {
        return $this->sillaDeRuedas;
    }

    public function getComidaEspecial()
    {
        return $this->comidaEspecial;
    }

    public function setAsistenciaEmbarque($asistenciaEmbarque)
    {
        $this->asistenciaEmbarque = $asistenciaEmbarque;
    }

    public function setSillaDeRuedas($sillaDeRuedas)
    {
        $this->sillaDeRuedas = $sillaDeRuedas;
    }

    public function setComidaEspecial($comidaEspecial)
    {
        $this->comidaEspecial = $comidaEspecial;
    }

    public function __toString()
    {
        return parent::__toString() . 
        "Asistencia de Embarque: " . ($this->getAsistenciaEmbarque() ? "Si" : "No") . "\n" .
        "Silla de Ruedas: " . ($this->getSillaDeRuedas() ? "Si" : "No") . "\n" .
        "Comida Especial: " . ($this->getComidaEspecial() ? "Si" : "No") . "\n";
    }



}