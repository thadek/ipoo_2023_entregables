<?php

class Viaje
{

    //Inicializo el codigo en 0 y lo hago estático para que no se reinicie cada vez que se crea un objeto
    private static $codigo = 0;
    private $destino;
    private $cantMaxPasajeros=100;
    private $pasajeros;


    public function __construct($destino, $cantMaxPasajeros, $pasajeros)
    {
        $this->setDestino($destino);
        $this->setPasajeros($pasajeros);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        //Aumento el código en 1 cada vez que se crea un viaje. Genero el codigo al final para que pasen las validaciones.
        self::generarCodigo();
    }

    /**
     * Genera un código de viaje nuevo por cada instancia que se cree
     */
    private static function generarCodigo(){
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



    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setDestino($destino)
    {
        if($destino != ""){
            $this->destino = $destino;
        }else{
            throw new Exception("El destino no puede estar vacío.");
        }
    }

    public function setCantMaxPasajeros($cantMaxPasajeros)
    {
        if(is_numeric($cantMaxPasajeros) && $cantMaxPasajeros > 0){
            if($cantMaxPasajeros < count($this->pasajeros)){
                throw new Exception("La cantidad máxima de pasajeros no puede ser menor a la cantidad de pasajeros actual.");
            }else{
                $this->cantMaxPasajeros = $cantMaxPasajeros;
            }
        }else{
            throw new Exception("La cantidad máxima de pasajeros debe ser un número mayor a 0.");
        }
        
    }

    /**
     * CRUD Pasajeros
     */

    /**
     * Agrega un pasajero al viaje
     * @param array $pasajero
     */
    public function addPasajero($pasajero)
    {
        if (count($this->pasajeros) < $this->cantMaxPasajeros) {
            array_push($this->pasajeros, $pasajero);
            echo "\nPasajero agregado\n";
        } else {
            echo "\n\033[31mNo se puede agregar el pasajero, el viaje está completo\n\033[0m";
        }
        
    }

    /**
     * Retorna un array con los pasajeros del viaje
     */
    public function getPasajeros()
    {
        return $this->pasajeros;
    }

    /**
     * Retorna un array con el pasajero que coincida con el dni
     * @param string $dni
     */
    public function getPasajero($dni)
    {
        foreach ($this->pasajeros as $pasajero) {
            if ($pasajero["dni"] == $dni) {
                return $pasajero;
            }
        }
    }

    /**
     * Actualiza un pasajero del viaje
     * @param string $dni
     * @param array $pasajero
     */
    public function updatePasajero($dni, $pasajero)
    {
        foreach ($this->pasajeros as $pasajero) {
            if ($pasajero["dni"] == $dni) {
                $pasajero = $pasajero;
            }
        }
    }

    /**
     * Elimina un pasajero del viaje
     * @param string $dni
     */
    public function removePasajero($dni)
    {
        //Busco en el arreglo de pasajeros el dni y lo elimino
        $pasajero = array_search($dni, array_column($this->pasajeros, 'dni')); 
        if($pasajero || $pasajero === 0){
            unset($this->pasajeros[$pasajero]);
            echo "\n\nPasajero eliminado\n\n";
        }else{
            echo "\n\nNo se encontró el pasajero\n\n";
        }
         
    }

    public function setPasajeros($pasajeros)
    {
        if (count($pasajeros) <= $this->cantMaxPasajeros) {
            is_array($pasajeros) ? $this->pasajeros = $pasajeros : throw new Exception("El parámetro pasajeros debe ser un array");
        } else {
            throw new Exception("La cantidad de pasajeros supera la cantidad máxima de pasajeros");
        }
    }

  
    public function verListaPasajeros()
    {
        echo "Pasajeros: \n";
        foreach ($this->getPasajeros() as $pasajero) {
            echo "\nNombre: " . $pasajero["nombre"] . "\n";
            echo "Apellido: " . $pasajero["apellido"] . "\n";
            echo "DNI: " . $pasajero["dni"] . "\n\n";
        }
    }

    public function __toString()
    {
        return "Viaje N°: " . $this->getCodigo() . 
        " - Destino: " . $this->getDestino() . 
        " - Cantidad de pasajeros: " . count($this->getPasajeros()) . 
        " - Cantidad máxima de pasajeros: " . $this->getCantMaxPasajeros() . 
        "\n";
    }
}
