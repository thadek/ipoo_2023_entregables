<?php

class ResponsableV
{

    private $nroEmpleado;
    private $nroLicencia;
    private $nombre;
    private $apellido;
    private $viajes;
  


    public function __construct()
    {
        $this->nroEmpleado = "";
        $this->nroLicencia = "";
        $this->nombre = "";
        $this->apellido = ""; 
        $this->viajes = [];
    }


    public function cargarResponsable($nroEmpleado, $nroLicencia, $nombre, $apellido, $viajes = [])
    {
        $this->setNroEmpleado($nroEmpleado);
        $this->setNroLicencia($nroLicencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setViajes($viajes);
       
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

    public function getViajes()
    {
        return $this->viajes;
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

    public function setViajes($viajes)
    {
        $this->viajes = $viajes;
    }


    //Metodos ORM



    public static function listar($condicion=""){
        $arreglo = array();
        $base=new BaseDatos();
        $query="SELECT * FROM responsable ";

        if($condicion!=""){
            $query=$query.' WHERE '.$condicion;
        }

        if($base->Iniciar()){
            if($base->Ejecutar($query)){
                $arreglo = array();
                while($response=$base->Registro()){
                    $obj=new ResponsableV();
                    $obj->cargarResponsable($response['rnumeroempleado'], $response['rnumerolicencia'], $response['rnombre'], $response['rapellido']);
                    array_push($arreglo, $obj);
                }
            }else{
                throw new Exception("Error al listar los responsables. SQL Devolvió:" . $base->getError());
            }
        }else{
            throw new Exception("Error al conectar a la base de datos.");
        }
        return $arreglo;
    }

    /**
     * Cargar el objeto desde la base de datos.
     */

    public function cargar(){
        $base=new BaseDatos();
        $query="SELECT * FROM responsable WHERE rnumeroempleado=".$this->getNroEmpleado();

        if($this->getNroEmpleado()==null){
            throw new Exception("No se puede cargar el responsable desde BD porque el objeto no tiene un id seteado.");
        }
        if($base->Iniciar()){
            if($base->Ejecutar($query)){
              if($response=$base->Registro()){
                $this->cargarResponsable($response['rnumeroempleado'], $response['rnumerolicencia'], $response['rnombre'], $response['rapellido']);
              }
            }else{
                throw new Exception("Error al ejecutar la consulta: ".$query);
            }
        }else{
            throw new Exception("Error al conectar a la base de datos.");
        }
    }

    public function insertar(){
        $base=new BaseDatos();
        $query="INSERT INTO responsable(rnumerolicencia,rnombre,rapellido)  
        VALUES('".$this->getNroLicencia()."','".$this->getNombre()."','".$this->getApellido()."')";

        if(!$base->Iniciar()){
            throw new Exception("Error al conectar a la base de datos.");
        }
        if($id = $base->devuelveIDInsercion($query)){
            $this->setNroEmpleado($id);
            return true;
        }else{
            throw new Exception("Error al realizar la inserción del responsable. SQL Devolvió:" . $base->getError());
        }
    }


    public function actualizar(){
        $base=new BaseDatos();
        $query="UPDATE responsable SET rnumerolicencia='".$this->getNroLicencia()."',rnombre='".$this->getNombre()."',rapellido='".$this->getApellido()."' 
        WHERE rnumeroempleado=".$this->getNroEmpleado();

        if(!$base->Iniciar()){
            throw new Exception("Error al conectar a la base de datos.");
        }
        if($base->Ejecutar($query)){
            return true;
        }else{
            throw new Exception("Error al realizar la modificación del responsable. SQL Devolvió:" . $base->getError());
        }
    }


    public function eliminar(){
        $base=new BaseDatos();
        $query="DELETE FROM responsable WHERE rnumeroempleado=".$this->getNroEmpleado();

        if(!$base->Iniciar()){
            throw new Exception("Error al conectar a la base de datos.");
        }
        if($base->Ejecutar($query)){
            return true;
        }else{
            throw new Exception("Error al realizar la eliminación del responsable. SQL Devolvió:" . $base->getError());
        }
    }


   /**
      * Retorna un string con los viajes del responsable
      */
      public function mostrarViajes(){
        $arrViajes = $this->getViajes();
        $strViajes = "\nViajes del responsable:\n
        \n----------------------------------------\n";
        foreach($arrViajes as $viaje){
            $strViajes .= 
            "\nID: " . $viaje->getId() . " - DESTINO: " . $viaje->getDestino() . " - OCUPACIÓN: " . count($viaje->getPasajeros()) . "/" . $viaje->getCantMaxPasajeros() . " - IMPORTE: $" . $viaje->getImporte() ."\n";
        }
        return $strViajes;
     }

    public function __toString()
    {
        return "\n----------------------------------------\nResponsable: " 
        . $this->getApellido().", ".$this->getNombre().
        "\n----------------------------------------\n".
        "Nro. Empleado: " . $this->getNroEmpleado(). "\nNro. Licencia: " . $this->getNroLicencia()
        . (count($this->getViajes())>0 ? $this->mostrarViajes() : "")
        . "\n----------------------------------------\n";
    }


}
