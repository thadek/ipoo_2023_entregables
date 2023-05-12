<?php

class Pasajero {


    private $nombre;
    private $nroAsiento;
    private $nroTicket;


    public function __construct($nombre, $nroAsiento, $nroTicket)
    {
        $this->nombre = $nombre;
        $this->nroAsiento = $nroAsiento;
        $this->nroTicket = $nroTicket;
    }
    

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getNroAsiento()
    {
        return $this->nroAsiento;
    }

    public function getNroTicket()
    {
        return $this->nroTicket;
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

    public function __toString()
    {
        return "Nombre: " . $this->nombre . "\n" .
        "Nro de Asiento: " . $this->nroAsiento . "\n" .
        "Nro de Ticket: " . $this->nroTicket . "\n";
    }

    


}