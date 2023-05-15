<?php

class ResponsableV
{

    private $nroEmpleado;
    private $nroLicencia;
    private $nombre;
    private $apellido;
  

    public function __construct($nroEmpleado, $nroLicencia, $nombre, $apellido)
    {
        $this->setNroEmpleado($nroEmpleado);
        $this->setNroLicencia($nroLicencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }


    public function getNroEmpleado()
    {
        return $this->nroEmpleado;
    }

    public function getNroLicencia()
    {
        return $this->nroLicencia;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setNroEmpleado($nroEmpleado)
    {
        $this->nroEmpleado = $nroEmpleado;
    }

    public function setNroLicencia($nroLicencia)
    {
        $this->nroLicencia = $nroLicencia;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function __toString()
    {
        return "Responsable: " . $this->getApellido().", ".$this->getNombre()."\n----------------------------------------\n"."Nro. Empleado: " . $this->getNroEmpleado(). "\nNro. Licencia: " . $this->getNroLicencia(). "\n----------------------------------------\n";
    }


}
