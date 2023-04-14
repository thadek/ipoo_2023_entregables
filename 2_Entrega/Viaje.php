<?php

include_once("Pasajero.php");
include_once("ResponsableV.php");


class Viaje
{

    //Inicializo el codigo en 0 y lo hago estático para que no se reinicie cada vez que se crea un objeto
    private static $codigo = 0;
    private $destino;
    private $cantMaxPasajeros = 100;
    private $pasajeros;
    private $responsableV;


    public function __construct($destino, $cantMaxPasajeros, $pasajeros, $responsableV)
    {
        $this->setDestino($destino);
        $this->setPasajeros($pasajeros);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setResponsableV($responsableV);
        //Aumento el código en 1 cada vez que se crea un viaje. Genero el codigo al final para que pasen las validaciones.
        self::generarCodigo();
    }

    /**
     * Genera un código de viaje nuevo por cada instancia que se cree
     */
    private static function generarCodigo()
    {
        self::$codigo++;
    }

    public function getCodigo()
    {
        return self::$codigo;
    }

    public function getDestino()
    {
        return $this->destino;
    }

    public function getCantMaxPasajeros()
    {
        return $this->cantMaxPasajeros;
    }

    public function getResponsableV()
    {
        return $this->responsableV;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Setea el destino del viaje
     * @param string $destino
     * @throws Exception
     */
    public function setDestino($destino)
    {
        if ($destino != "") {
            $this->destino = $destino;
        } else {
            throw new Exception("El destino no puede estar vacío.");
        }
    }


    /**
     * Setea el responsable del viaje
     * @param ResponsableV $responsableV
     * @throws Exception
     */
    public function setResponsableV($responsableV)
    {
        if ($responsableV != "") {
            $this->responsableV = $responsableV;
        } else {
            throw new Exception("El responsable no puede estar vacío.");
        }
    }




    /**
     * Setea la cantidad maxima de pasajeros
     * @param int $cantMaxPasajeros
     * @throws Exception
     */
    public function setCantMaxPasajeros($cantMaxPasajeros)
    {
        if (is_numeric($cantMaxPasajeros) && $cantMaxPasajeros > 0) {
            if ($cantMaxPasajeros < count($this->getPasajeros())) {
                throw new Exception("La cantidad máxima de pasajeros no puede ser menor a la cantidad de pasajeros actual.");
            } else {
                $this->cantMaxPasajeros = $cantMaxPasajeros;
            }
        } else {
            throw new Exception("La cantidad máxima de pasajeros debe ser un número mayor a 0.");
        }
    }

    /**
     * CRUD Pasajeros
     */

    /**
     * Agrega un pasajero al viaje
     * @param Pasajero $pasajero
     * @return bool 
     */
    public function addPasajero($pasajero)
    {
        $arrPasajeros = $this->getPasajeros();

        if (count($arrPasajeros) < $this->getCantMaxPasajeros()) {
            if($this->getPasajero($pasajero->getDni())) {
                throw new Exception("El pasajero con DNI " .$pasajero->getDni(). " ya existe");
            }else{
               array_push($arrPasajeros, $pasajero);
               $this->setPasajeros($arrPasajeros);
            } 
            return true;
        } else {
            throw new Exception("No se puede agregar el pasajero, el viaje está completo");
        }
    }

    /**
     * Retorna el array con los pasajeros del viaje
     * @return array $pasajeros
     */
    public function getPasajeros()
    {
        return $this->pasajeros;
    }

    /**
     * Retorna el objeto pasajero buscado por dni, caso contrario retorna false
     * @param string $dni
     */
    public function getPasajero($dni)
    {

        //Implementación actual, utilizando recorrido parcial con while.
        $i = 0;
        $corte = false;
        $pasajero = null;
        $arrPasajeros = $this->getPasajeros();
        while ($i < count($arrPasajeros) && !$corte) {
            if ($arrPasajeros[$i]->getDni() == $dni) {
                $pasajero = $arrPasajeros[$i];
                $corte = true;
            }
            $i++;
        }

        if ($pasajero) {
            return $pasajero;
        } else {
            return false;
        }

    }

    /**
     * Actualiza un pasajero del viaje
     * @param string $dni
     * @param Pasajero $pasajero
     * @return string $response
     */
    public function updatePasajero($dni, $pasajero)
    {

        if($pasajero instanceof Pasajero){
            $pSearch = $this->getPasajero($dni);
            $pSearch->setNombre($pasajero->getNombre());
            $pSearch->setApellido($pasajero->getApellido());
            $pSearch->setDni($pasajero->getDni());
            $pSearch->setTelefono($pasajero->getTelefono());
        }else{
            throw new Exception("El pasajero no es una instancia de la clase Pasajero");
        }
       

      
    }

    /**
     * Elimina un pasajero del viaje
     * @param string $dni
     * @return boolean $isDeleted
     */
    public function removePasajero($dni)
    {
        //Busco en el arreglo de pasajeros el dni y lo elimino
        $arrPasajeros = $this->getPasajeros();

        $pasajeroIndex = null;
        $corte = false;
        $i = 0;
        while($i < count($arrPasajeros) && !$corte){
         if($arrPasajeros[$i]->getDni() == $dni){
                $pasajeroIndex = $i;
                $corte = true;
            }
            $i++;
        }

        if ($pasajeroIndex || $pasajeroIndex === 0) {
            unset($arrPasajeros[$pasajeroIndex]);
            //Reindexo el array para que no queden huecos <-< 
            $arrPasajeros = array_values($arrPasajeros);
            $this->setPasajeros($arrPasajeros);
            return true;
        } else {
            throw new Exception("No existe ningún pasajero con el DNI $dni");
        }
    }

    /**
     * Setea el array de pasajeros
     * @param array $pasajeros
     * 
     */
    public function setPasajeros($pasajeros)
    {
        if (count($pasajeros) <= $this->getCantMaxPasajeros()) {
            is_array($pasajeros) ? $this->pasajeros = $pasajeros : throw new Exception("El parámetro pasajeros debe ser un array");
        } else {
            throw new Exception("La cantidad de pasajeros supera la cantidad máxima de pasajeros");
        }
    }


    /**
     * Muestra la lista de pasajeros
     * @return string $response
     */
    public function verListaPasajeros()
    {

        if(count($this->getPasajeros()) == 0){
            throw new Exception("No hay pasajeros cargados en el viaje.");
        }
        $response= "Lista de Pasajeros:";
        $response.= "\n----------------------------------------\n";
        foreach ($this->getPasajeros() as $pasajero) {
          $response.= $pasajero . "\n";
        }
        return $response;
    }

    public function __toString()
    {
        return "\n----------------------------------------\n                 Viaje\n----------------------------------------\nViaje N°: " . $this->getCodigo() .
            "\nDestino: " . $this->getDestino() .
            "\nCantidad de pasajeros: " . count($this->getPasajeros()) .
            "\nCantidad máxima de pasajeros: " . $this->getCantMaxPasajeros() .
            "\n----------------------------------------\n" . $this->getResponsableV() .
            $this->verListaPasajeros()
            . "\n----------------------------------------\n";
    }
}
