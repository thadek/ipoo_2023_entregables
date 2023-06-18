<?php

class Viaje
{

    private $Id;
    private $destino;
    private $cantMaxPasajeros;
    private $empresa;
    private $responsableV;
    private $importe;
    private $pasajeros;



    public function __construct()
    {
        $this->Id = null;
        $this->destino = "";
        $this->cantMaxPasajeros = 0;
        $this->responsableV = new ResponsableV();
        $this->empresa = new Empresa();
        $this->importe = 0;
        $this->pasajeros = array();
    }


    public function cargarViaje($Id, $destino, $cantMaxPasajeros, $empresa, $responsableV, $importe, $pasajeros)
    {
        $this->setId($Id);
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setEmpresa($empresa);
        $this->setResponsableV($responsableV);
        $this->setImporte($importe);
        $this->setPasajeros($pasajeros);
    }

    public function getId()
    {
        return $this->Id;
    }

    public function getDestino()
    {
        return $this->destino;
    }

    public function getCantMaxPasajeros()
    {
        return $this->cantMaxPasajeros;
    }

    public function getEmpresa()
    {
        return $this->empresa;
    }

    public function getResponsableV()
    {
        return $this->responsableV;
    }

    public function getImporte()
    {
        return $this->importe;
    }

    public function getPasajeros()
    {
        return $this->pasajeros;
    }

    public function setId($Id)
    {
        $this->Id = $Id;
    }

    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

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

    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    public function setResponsableV($responsableV)
    {
        $this->responsableV = $responsableV;
    }

    public function setImporte($importe)
    {
        $this->importe = $importe;
    }

    public function setPasajeros($pasajeros)
    {
        $this->pasajeros = $pasajeros;
    }


 


    /**
     * Agrega un objeto pasajero a la coleccion de objetos pasajero del viaje. PUEDE O NO COINCIDIR CON LA DB.
     */
    public function agregarPasajeroAlArray($pasajero)
    {
        if (count($this->getPasajeros()) < $this->cantMaxPasajeros) {
            array_push($this->pasajeros, $pasajero);
        } else {
            throw new Exception("No hay más lugar en el viaje.");
        }
    }


    //METODOS ORM

  /**
     * Retorna un array de objetos viaje.
     */
    public static function listar($cond = ""){
        $conexion = new BaseDatos();
        $query = "SELECT * FROM viaje";
        if($cond != ""){
            $query.=" WHERE " . $cond;
        }
        $arrViajes = [];
        if($conexion->Iniciar()){
            if($conexion->Ejecutar($query)){
                
                while($resp = $conexion->Registro()){
                    //Creo una instancia de viaje
                    $obj = new Viaje();
                    // Creo una instancia de empresa y responsableV para setearlos en el objeto viaje
                    $empresa = new Empresa();
                    $empresa->setId($resp["idempresa"]);
                    //Cargo sus datos desde la base de datos
                    $empresa->cargar();

                    $responsableV = new ResponsableV();
                    $responsableV->setNroEmpleado($resp["rnumeroempleado"]);
                    //Cargo sus datos desde la base de datos
                    $responsableV->cargar();
                    $obj->setId($resp["idviaje"]);
                    //Obtengo los pasajeros del viaje desde la db y los seteo en el objeto viaje
                    $obj->setPasajeros(Pasajero::listar("idviaje = " . $resp["idviaje"]));

                    // Seteo los atributos del objeto viaje que vienen de la consulta sql.
                    $obj->cargarViaje($resp["idviaje"], $resp["vdestino"], $resp["vcantmaxpasajeros"], $empresa, $responsableV, $resp["vimporte"], $obj->getPasajeros());


                    array_push($arrViajes, $obj);
                }


            }else{
                throw new Exception("Error al listar los viajes en la base de datos. SQL Devolvió: " . $conexion->getError());
            }
        }else{
            throw new Exception("Error de conexion con la base de datos en método listarViajes. SQL Devolvió: " . $conexion->getError());
        }

        return $arrViajes;
    }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM viaje WHERE idviaje = ".$this->getId();
        if ($base->Iniciar()) {
            if($base->Ejecutar($sql)){
                if($row2=$base->Registro()){
                    $this->setId($row2['idviaje']);
                    $this->setDestino($row2['vdestino']);
                    $this->setCantMaxPasajeros($row2['vcantmaxpasajeros']);
                    $this->setImporte($row2['vimporte']);

                    $empresa = new Empresa();
                    $empresa->setId($row2['idempresa']);
                    $empresa->cargar();
                    $this->setEmpresa($empresa);
    
                    $responsable = new ResponsableV();
                    $responsable->setNroEmpleado($row2['rnumeroempleado']);
                    $responsable->cargar();
                    $this->setResponsableV($responsable);
                    $resp = true;

                    $pasajeros = Pasajero::listar("idviaje = " . $this->getId());
                    $this->setPasajeros($pasajeros);
                }
            } else {
                throw new Exception("Error al ejecutar la consulta sql en la base de datos.");
            }
        } else {
            throw new Exception("Error al conectar a la base de datos.");
        }
        return $resp;
    }

    /**
     * Inserta la instancia viaje en la base de datos.
     */
    public function insertar()
    {

        $conexion = new BaseDatos();
        $query = "INSERT INTO viaje (vdestino, vcantmaxpasajeros,idempresa, rnumeroempleado, vimporte) 
        VALUES ('" . $this->getDestino() . "','" . $this->getCantMaxPasajeros() . "','" . $this->getEmpresa()->getId() . "','" . $this->getResponsableV()->getNroEmpleado() . "','" . $this->getImporte() . "')";
        if ($conexion->Iniciar()) {
            if ($id = $conexion->devuelveIDInsercion($query)) {
                $this->setId($id);
                return true;
            }else{
                throw new Exception("Error al insertar un viaje en la base de datos. Traza: " . $conexion->getError());
            }
        } else {
            throw new Exception("Error de conexion con la base de datos en método insertarViaje. Traza: " . $conexion->getError());
        }

        
    }

    /**
     * Actualiza la instancia viaje en la base de datos.
     */
    public function actualizar(){

        $conexion = new BaseDatos();
        $query = "UPDATE viaje SET vdestino = '" . $this->getDestino() . "', vcantmaxpasajeros = '" . $this->getCantMaxPasajeros() . "', idempresa = '" . $this->getEmpresa()->getId() . "', rnumeroempleado = '" . $this->getResponsableV()->getNroEmpleado() . "', vimporte = '" . $this->getImporte() . "' WHERE idviaje = '" . $this->getId() . "'";
        $response = false;

        if($conexion->Iniciar()){
            if($conexion->Ejecutar($query)){
                $response = true;
            }else{
                throw new Exception("Error al actualizar un viaje en la base de datos. Traza: " . $conexion->getError());
            }
        }else{
            throw new Exception("Error de conexion con la base de datos en método actualizarViaje. Traza: " . $conexion->getError());
        }

        return $response;

    }

    /**
     * Elimina la instancia viaje de la base de datos.
     */
    public function eliminar(){
            
            $conexion = new BaseDatos();
            if($this->getId() == null){
                throw new Exception("Error al eliminar un viaje en la base de datos. El id del viaje es nulo.");
            }
            $query = "DELETE FROM viaje WHERE idviaje = '" . $this->getId() . "'";
            $response = false;
            if($conexion->Iniciar()){
                if($conexion->Ejecutar($query)){
                    $response = true;
                }else{
                    throw new Exception("Error al eliminar un viaje en la base de datos. Traza: " . $conexion->getError());
                }
            }else{
                throw new Exception("Error de conexion con la base de datos en método eliminarViaje. Traza: " . $conexion->getError());
            }

            return $response;
    }


    /**
     * Retorna un string con la coleccion de pasajeros del viaje.
     */
    public function mostrarPasajeros(){
        $pasajeros = $this->getPasajeros();
        $response = "";
        if(count($pasajeros) == 0){
            $response= "No hay pasajeros cargados en este viaje.";
        }else{
            foreach($pasajeros as $pasajero){
                $response .= $pasajero->__toString() . "\n";
            }
        }
        return $response;
    }


    //tostring
    public function __toString()
    {
        return "ID: " . $this->getId() . "\n" .
        "Destino: " . $this->getDestino() . "\n" .
        "Cantidad Maxima de Pasajeros: " . $this->getCantMaxPasajeros() . "\n" .
        "Empresa: " . $this->getEmpresa() . "\n" .
        "Responsable: " . $this->getResponsableV() . "\n" .
        "Importe: " . $this->getImporte() . "\n" .
        "Pasajeros: " . $this->mostrarPasajeros() . "\n";
    }



}
