<?php

class Viaje
{

    //Inicializo el codigo en 0 y lo hago estático para que no se reinicie cada vez que se crea un objeto
    private static $codigo = 0;
    private $destino;
    private $cantMaxPasajeros = 100;
    private $pasajeros;
    private $responsableV;
    private $costo;
    private $costosAbonados;


    public function __construct($destino, $cantMaxPasajeros, $pasajeros, $responsableV, $costo, $costosAbonados)
    {
        $this->setDestino($destino);
        $this->setPasajeros($pasajeros);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setResponsableV($responsableV);
        $this->setCosto($costo);
        $this->setCostosAbonados($costosAbonados);
        //Aumento el código en 1 cada vez que se crea un viaje. Genero el codigo al final para que pasen las validaciones.
        self::generarCodigo();
    }

    /**
     * Genera un código de viaje nuevo por cada instancia que se cree
     * Aumenta en 1 la variable clase $codigo cada vez que se crea un objeto
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

    public function getCosto()
    {
        return $this->costo;
    }

    public function setCosto($costo)
    {
        $this->costo = $costo;
    }

    public function getCostosAbonados()
    {
        return $this->costosAbonados;
    }

    public function setCostosAbonados($costosAbonados)
    {
        $this->costosAbonados = $costosAbonados;
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
     * @throws Exception
     * @return bool 
     */
    public function addPasajero($pasajero)
    {
        $arrPasajeros = $this->getPasajeros();
        //Valido que el numero de asiento no este ocupado previamente
        if($this->isAsientoOcupado($pasajero->getNroAsiento())){
            throw new Exception("El asiento " . $pasajero->getNroAsiento() . " ya está ocupado");
        }

        if (!$this->hayPasajesDisponibles()) {
            throw new Exception("No se puede agregar el pasajero, el viaje está completo");
        }

        array_push($arrPasajeros, $pasajero);
        $this->setPasajeros($arrPasajeros);
        return true;
        
    }


    /**
     * Devuelve verdadero si hay pasajes disponibles, falso caso contrario
     */
    public function hayPasajesDisponibles(){
        $arrPasajeros = $this->getPasajeros();
        return (count($arrPasajeros) < $this->getCantMaxPasajeros());
    }



    /**
     * Verifico que el asiento no este ocupado
     * @param int $nroAsiento
     */
    public function isAsientoOcupado($nroAsiento,$numAsientoOcupado = null){
        $arrPasajeros = $this->getPasajeros();
        $i = 0;
        $corte = false;
        $asientoOcupado = false;
        if(!($nroAsiento == $numAsientoOcupado)){
            while ($i < count($arrPasajeros) && !$corte) {
                if ($arrPasajeros[$i]->getNroAsiento() == $nroAsiento) {
                    $asientoOcupado = true;
                    $corte = true;
                }
                $i++;
            }  
        }
        return $asientoOcupado;
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
     * Retorna la posición del pasajero en el array de pasajeros
     * @param int $nroTicket
     */
    public function getPasajero($nroTicket)
    {
        //Implementación actual, utilizando recorrido parcial con while.
        $i = 0;
        $corte = false;
        $pasajero = null;
        $arrPasajeros = $this->getPasajeros();
        while ($i < count($arrPasajeros) && !$corte) {
            if ($arrPasajeros[$i]->getNroTicket() == $nroTicket) {
                $pasajero = $i;        
                $corte = true;
            }
            $i++;
        }

        return $pasajero;
    }

    /**
     * Elimina un pasajero del viaje, returna true si 
     * se eliminó correctamente, false si no se encontró el pasajero
     * @param int $nroTicket
     * @return boolean $isDeleted
     */
    public function removePasajero($nroTicket)
    {
        //Busco en el arreglo de pasajeros el dni y lo elimino
        $arrPasajeros = $this->getPasajeros();
        $pasajeroIndex = $this->getPasajero($nroTicket);
        $isDeleted = false;
        if ($pasajeroIndex || $pasajeroIndex == 0) {
            unset($arrPasajeros[$pasajeroIndex]);
            //Reindexo el array para que no queden huecos <-< 
            $arrPasajeros = array_values($arrPasajeros);
            $this->setPasajeros($arrPasajeros);
            $isDeleted = true;
        } 
        return $isDeleted;
    }




    /**
     * Setea el array de pasajeros
     * @param array $pasajeros
     * 
     */
    public function setPasajeros($pasajeros)
    {
        if (count($pasajeros) <= $this->getCantMaxPasajeros()) {
            if (is_array($pasajeros)) {
                $this->pasajeros = $pasajeros;
            } else {
                throw new Exception("El parámetro pasajeros debe ser un array");
            }
        } else {
            throw new Exception("La cantidad de pasajeros supera la cantidad máxima de pasajeros");
        }
    }


    public function calcularCostoPasajero($pasajero){
        return $this->getCosto() * $pasajero->darPorcentajeIncremento();
    }


    public function venderPasaje($pasajero){
        //Intento agregar el pasajero al viaje
        $this->addPasajero($pasajero);
        //Calculo el costo del pasaje
        $costoAbonar = $this->calcularCostoPasajero($pasajero);
        //Sumo a los costos abonados del viaje el costo del pasaje
        $this->setCostosAbonados($this->getCostosAbonados() + $costoAbonar);
        //Retorno el costo a abonar con el incremento correspondiente aplicado.
        return $costoAbonar;
    }


    /**
     * Muestra la lista de pasajeros
     * @return string $response
     */
    public function verListaPasajeros()
    {

        if (count($this->getPasajeros()) == 0) {
            $response = "No hay pasajeros en el viaje\n";
        } else {
            $response = "Lista de Pasajeros:";
            $response .= "\n----------------------------------------\n";
            foreach ($this->getPasajeros() as $pasajero) {
                $response .= $pasajero . "\n";
            }
        }
        return $response;
    }

    public function __toString()
    {
        return "\n----------------------------------------\n                 Viaje\n----------------------------------------\nViaje N°: " . $this->getCodigo() .
            "\nDestino: " . $this->getDestino() .
            "\nCantidad de pasajeros: " . count($this->getPasajeros()) .
            "\nCosto: $" . $this->getCosto() .
            "\nCostos abonados: $" . $this->getCostosAbonados() .
            "\nCantidad máxima de pasajeros: " . $this->getCantMaxPasajeros() .
            "\n----------------------------------------\n" . $this->getResponsableV() .
            $this->verListaPasajeros()
            . "\n----------------------------------------\n";
    }
}
