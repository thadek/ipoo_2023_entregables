<?php

include_once("Pasajero.php");

class PasajeroEspecial extends Pasajero {

 private $asistenciaEmbarque;
 private $sillaDeRuedas;
 private $comidaEspecial;

 public function __construct($nombre, $nroAsiento, $asistenciaEmbarque, $sillaDeRuedas, $comidaEspecial)
 {
     // Llamamos al constructor de la clase padre
     parent::__construct($nombre, $nroAsiento);
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

    /**
     * Devuelve el porcentaje de incremento del precio del pasajero Especial. 
     * Es 30% si requiere los 3 servicios, o 15% si requiere 1 de los 3 servicios.
     *  POR DEFECTO SE ASUME QUE SI ES PASAJERO ESPECIAL REQUIERE COMO MÍNIMO 1 SERVICIO.-
     */
    public function darPorcentajeIncremento(){

        $retorno = null;
        if($this->asistenciaEmbarque && $this->sillaDeRuedas && $this->comidaEspecial){
            $retorno = 1.3;
        }else{
            $retorno = 1.15;
        }
        return $retorno;

    }


    public function __toString()
    {
        return parent::__toString() . 
        "Asistencia de Embarque: " . ($this->getAsistenciaEmbarque() ? "Si" : "No") . "\n" .
        "Silla de Ruedas: " . ($this->getSillaDeRuedas() ? "Si" : "No") . "\n" .
        "Comida Especial: " . ($this->getComidaEspecial() ? "Si" : "No") . "\n";
    }



}