<?php


class Pasajero {


    private $nombre;
    private $nroAsiento;
    private static $contador = 0;
    private $nroTicket;


    public function __construct($nombre, $nroAsiento)
    {
        $this->nombre = $nombre;
        $this->nroAsiento = $nroAsiento;
        self::$contador++;
        $this->nroTicket = self::$contador;
    }
    
    public function getNroTicket()
    {
        return $this->nroTicket;
    }


    public function getNombre()
    {
        return $this->nombre;
    }

    public function getNroAsiento()
    {
        return $this->nroAsiento;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setNroAsiento($nroAsiento)
    {
        $this->nroAsiento = $nroAsiento;
    }

    public function setNroTicket($nroTicket)
    {
        $this->nroTicket = $nroTicket;
    }


    /**
     * Devuelve el porcentaje de incremento del precio del pasajero común. (10%)
     */
    public function darPorcentajeIncremento(){
        return 1.1;
    }




    public function __toString()
    {
        return "Nombre: " . $this->getNombre() . "\n" .
        "Nro de Asiento: " . $this->getNroAsiento() . "\n" .
        "Nro de Ticket: " . $this->getNroTicket() . "\n";
    }




}