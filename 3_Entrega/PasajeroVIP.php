<?php

include_once("Pasajero.php");

class PasajeroVIP extends Pasajero {

    private $nroViajeroFrecuente;
    private $cantMillas;

    public function __construct($nombre, $nroAsiento, $nroTicket, $nroViajeroFrecuente, $cantMillas)
    {
        // Llamamos al constructor de la clase padre
        parent::__construct($nombre, $nroAsiento, $nroTicket);
        $this->nroViajeroFrecuente = $nroViajeroFrecuente;
        $this->cantMillas = $cantMillas;
    }

    public function getNroViajeroFrecuente()
    {
        return $this->nroViajeroFrecuente;
    }

    public function getCantMillas()
    {
        return $this->cantMillas;
    }

    public function setNroViajeroFrecuente($nroViajeroFrecuente)
    {
        $this->nroViajeroFrecuente = $nroViajeroFrecuente;
    }

    public function setCantMillas($cantMillas)
    {
        $this->cantMillas = $cantMillas;
    }

    public function __toString()
    {
        return parent::__toString() . 
        "Nro de Viajero Frecuente: " . $this->nroViajeroFrecuente . "\n" .
        "Cantidad de Millas: " . $this->cantMillas . "\n";
    }
           

}