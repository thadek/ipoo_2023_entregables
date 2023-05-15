<?php

include_once("Pasajero.php");

class PasajeroVIP extends Pasajero {

    private $nroViajeroFrecuente;
    private $cantMillas;

    public function __construct($nombre, $nroAsiento, $nroViajeroFrecuente, $cantMillas)
    {
        // Llamamos al constructor de la clase padre
        parent::__construct($nombre, $nroAsiento);
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

    /**
     * Devuelve el porcentaje de incremento del precio del pasajero VIP.
     * Es 35% si tiene entre 0 y 300 millas y 30% si tiene más de 300 millas.
     */
    public function darPorcentajeIncremento(){
        //35% si tiene menos de 300 millas
        $retorno = 1.35;
        //30% si tiene 300 o más millas
        if($this->cantMillas > 300){
            $retorno = 1.3;
        }
        return $retorno;
    }





    public function __toString()
    {
        return parent::__toString() . 
        "Nro de Viajero Frecuente: " . $this->getNroViajeroFrecuente() . "\n" .
        "Cantidad de Millas: " . $this->getCantMillas() . "\n";
    }
           

}