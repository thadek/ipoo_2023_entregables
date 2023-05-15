<?php

include_once('Viaje.php');
include_once('Pasajero.php');
include_once('PasajeroVIP.php');
include_once('PasajeroEspecial.php');
include_once('ResponsableV.php');

/**
 * Muestra el menú de opciones
 */
function Menu()
{

    escribirAmarillo("1. Ver información del viaje\n2. Ver pasajeros\n3. Editar destino viaje \n4. Editar cantidad máxima de pasajeros\n5. Agregar pasajero\n6. Editar Pasajero \n7. Ver pasajero por Nro Asiento \n8. Eliminar pasajero\n9. Ver Responsable del viaje\n10. Editar Responsable del viaje\n11. Salir\n");
}

/**
 * lee y valida que el valor ingresado sea el tipo especificado, en caso de ser asi lo retorna, 
 * en caso contrario lanza una excepcion.
 * @param string $mensaje
 * @param string $tipo
 * @return string
 */
function leerYValidarValor($mensaje, $tipo)
{
    echo $mensaje;
    $valor = trim(fgets(STDIN));
    while (!validarValor($valor, $tipo)) {
        echo "\nEl valor ingresado no es del tipo $tipo, ingrese nuevamente: \n";
        $valor = trim(fgets(STDIN));
    }

    return $valor;
}

/**
 * Valida que el valor ingresado sea del tipo especificado, en caso de ser asi retorna true, 
 * en caso contrario retorna false.
 * @param string $valor
 * @param string $tipo
 * @return boolean
 */
function validarValor($valor, $tipo)
{
    switch ($tipo) {
        case 'string':
            return is_string($valor);
        case 'int':
            return is_numeric($valor);
        case 'float':
            return is_float($valor);
        case 'bool':
            return is_bool($valor);
        default:
            return false;
    }
}

/**
 * @param string $val
 * Valida si el string recibido es S o N. En caso de ser S retorna true, en caso contrario retorna false.
 */
function leerBool($val)
{
    $val = strtoupper($val);
    while($val != 'S' && $val != 'N'){
        echo "\nEl valor ingresado no es válido, ingrese nuevamente S/N : \n";
        $val = trim(fgets(STDIN));
        $val = strtoupper($val);
    }
    return $val == 'S' ? true : false;
}


/**
 * Agrega un pasajero al viaje
 * @param Viaje $viaje
 */
function agregarPasajero($viaje)
{
    if (!$viaje->hayPasajesDisponibles()) {
        throw new Exception("No hay pasajes disponibles");
    }
    $nombre = leerYValidarValor("\nIngrese el nombre del pasajero: ", "string");
    $asiento = leerYValidarValor("\nIngrese el número de asiento: ", "int");
    if ($viaje->isAsientoOcupado($asiento)) {
        throw new Exception("El asiento $asiento ya se encuentra ocupado");
    }
    $tipo = leerYValidarValor("\nIngrese el tipo de pasajero (1: Común, 2: VIP, 3: Especial): ", "int");
    $pasajero = null;
    switch ($tipo) {
        case 1:
            $pasajero = new Pasajero($nombre, $asiento);
            break;
        case 2:
            $nroViajeroFrecuente = leerYValidarValor("\nIngrese el número de viajero frecuente: ", "int");
            $cantMillas = leerYValidarValor("\nIngrese la cantidad de millas: ", "int");
            $pasajero = new PasajeroVIP($nombre, $asiento, $nroViajeroFrecuente, $cantMillas);
            break;
        case 3:
            $asistencia = leerBool(leerYValidarValor("\n¿Contrata asistencia de embarque? S/N ", "string"));
            $sillaDeRuedas = leerBool(leerYValidarValor("\n¿Silla de ruedas? S/N ", "string"));
            $comidaEspecial = leerBool(leerYValidarValor("\n¿Comida especial? S/N ", "string"));
            $pasajero = new PasajeroEspecial($nombre, $asiento, $asistencia, $sillaDeRuedas, $comidaEspecial);
            break;
        default:
            throw new Exception("El tipo de pasajero ingresado no es válido");
    }
    $costoAPagar = $viaje->venderPasaje($pasajero);

    echo "Pasajero agregado correctamente.\n";
    escribirVerde("El costo a pagar es: $ $costoAPagar \n");
    echo "DATOS DEL TICKET: \n";
    echo "---------------------------------------------------------\n";
    echo $pasajero;
    echo "---------------------------------------------------------\n";
}



/**
 * Elimina un pasajero del viaje
 * @param Viaje $viaje
 */
function eliminarPasajero($viaje)
{
    echo "Ingrese el nro de Ticket a eliminar:\n";
    $nroTicket = trim(fgets(STDIN));
    $isDeleted = $viaje->removePasajero($nroTicket);
    if ($isDeleted) {
        echo "\nSe eliminó correctamente el pasajero.\n";
    } else {
        echo "\nNo se encontró el pasajero.\n";
    }
}



/**
 * Edita el destino del viaje
 * @param Viaje $viaje
 */
function editarDestino($viaje)
{
    echo "Ingrese el nuevo destino: ";
    $destino = trim(fgets(STDIN));
    $viaje->setDestino($destino);
    echo "Destino actualizado\n";
}

/**
 * Edita la cantidad máxima de pasajeros del viaje
 * @param Viaje $viaje
 */
function editarCantMaxPasajeros($viaje)
{
    echo "Ingrese la nueva cantidad máxima de pasajeros: ";
    $cantMaxPasajeros = trim(fgets(STDIN));
    $viaje->setCantMaxPasajeros($cantMaxPasajeros);
    echo "Cantidad máxima de pasajeros actualizada\n";
}



/**
 * Escribe en color verde
 * @param string $texto
 */
function escribirVerde($texto)
{
    echo "\033[32m" . $texto . "\033[0m";
}


/**
 * Muestra la información del viaje y los pasajeros llamando a los métodos de la clase Viaje
 */
function verPasajeros($viaje)
{
    echo $viaje->verListaPasajeros();
}

function editarPasajero($viaje)
{
    echo "Ingrese el numero de ticket del pasajero a modificar\n";
    $nroTicket = trim(fgets(STDIN));
    $pasajeroIndex = $viaje->getPasajero($nroTicket);
    if ($pasajeroIndex === null) {
        throw new Exception("No se encontró el pasajero con num de ticket: $nroTicket");
    }
    $pasajero = $viaje->getPasajeros()[$pasajeroIndex];
    $nombre = leerYValidarValor("Ingrese el nombre del pasajero:\n", "string");
    $nroAsiento = leerYValidarValor("Ingrese el numero de asiento a ocupar:\n", "string");
    $pasajero->setNombre($nombre);

    if ($viaje->isAsientoOcupado($nroAsiento,$pasajero->getNroAsiento())) {
        throw new Exception("El asiento $nroAsiento ya se encuentra ocupado");
    }
    $pasajero->setNroAsiento($nroAsiento);
    $vAnterior = $viaje->getCosto()*$pasajero->darPorcentajeIncremento();

    if ($pasajero instanceof PasajeroVIP) {
        editarPasajeroVIP($viaje,$pasajero,$vAnterior);
    } elseif ($pasajero instanceof PasajeroEspecial) {
        editarPasajeroEspecial($viaje,$pasajero,$vAnterior);
    }
     escribirVerde("\nPasajero modificado correctamente\n");
   
}


function editarPasajeroVIP($viaje,$pasajero,$vAnterior){


    $nroViajeroFrecuente = leerYValidarValor("Ingrese el numero de viajero frecuente:\n", "int");
    $cantMillas = leerYValidarValor("Ingrese la cantidad de millas:\n", "int");
    $pasajero->setNroViajeroFrecuente($nroViajeroFrecuente);
    $pasajero->setCantMillas($cantMillas);

    //Elimino el costo anterior
    $viaje->setCostosAbonados($viaje->getCostosAbonados()-$vAnterior);
    //Agrego el nuevo costo creando un nuevo pasaje
    $costoActual = $viaje->calcularCostoPasajero($pasajero);

    $viaje->setCostosAbonados($viaje->getCostosAbonados()+$costoActual);
    escribirVerde("\nCosto anterior: $ $vAnterior El nuevo costo es: $ $costoActual \n");


}


function editarPasajeroEspecial($viaje,$pasajero,$vAnterior){
    $asistencia = leerBool(leerYValidarValor("¿Contrata asistencia de embarque? S/N ", "string"));
    $sillaDeRuedas = leerBool(leerYValidarValor("¿Silla de ruedas? S/N ", "string"));
    $comidaEspecial = leerBool(leerYValidarValor("¿Comida especial? S/N ", "string"));
    $pasajero->setAsistenciaEmbarque($asistencia);
    $pasajero->setSillaDeRuedas($sillaDeRuedas);
    $pasajero->setComidaEspecial($comidaEspecial);
     //Elimino el costo anterior
     $viaje->setCostosAbonados($viaje->getCostosAbonados()-$vAnterior);
     //Agrego el nuevo costo creando un nuevo pasaje
     $costoActual = $viaje->calcularCostoPasajero($pasajero);
     $viaje->setCostosAbonados($viaje->getCostosAbonados()+$costoActual);
     escribirVerde("\nCosto anterior: $ $vAnterior El nuevo costo es: $ $costoActual \n");
}





function verPasajero($viaje)
{
    $nroAsiento = leerYValidarValor("Ingrese el nro de asiento del pasajero:\n", "string");
    $pasajeroIndex = $viaje->getPasajero($nroAsiento);
    if ($pasajeroIndex || $pasajeroIndex === 0) {
        echo $viaje->getPasajeros()[$pasajeroIndex];
    } else {
        throw new Exception("No se encontró el pasajero con nro de Asiento: $nroAsiento");
    }
}


/**
 * Escribe en color amarillo
 * @param string $texto
 */
function escribirAmarillo($texto)
{
    echo "\033[33m" . $texto . "\033[0m";
}




/**
 * Ver responsable del viaje
 */
function verResponsable($viaje)
{
    echo $viaje->getResponsableV();
    echo "\n";
}

/**
 * Editar responsable del viaje
 */
function editarResponsable($viaje)
{
    $responsable = $viaje->getResponsableV();
    $responsable->setNombre(leerYValidarValor("Ingrese el nombre del responsable:\n", "string"));
    $responsable->setApellido(leerYValidarValor("Ingrese el apellido del responsable:\n", "string"));
    $responsable->setNroLicencia(leerYValidarValor("Ingrese el nro de licencia del responsable:\n", "int"));
    $responsable->setNroEmpleado(leerYValidarValor("Ingrese el nro de empleado del responsable:\n", "int"));
    // $viaje->setResponsable($responsable);
}

/**
 * Test Viaje: Prueba la clase Viaje creando una instancia y probando sus métodos
 */
function testViaje()
{

    $responsable = new ResponsableV(12345, 94974, "Matias", "Levpold");

    $viaje = new Viaje("Buenos Aires", 5, [], $responsable, 1500, 0);
    $opcion = 0;

    escribirVerde("\n\nVIAJE FELIZ S.A.\n\n");
    while ($opcion != 11) {
        try {
            Menu();
            echo "Ingrese una opción: ";
            $opcion = trim(fgets(STDIN));
            switch ($opcion) {
                case 1:
                    escribirVerde("\n" . $viaje . "\n");
                    break;
                case 2:
                    verPasajeros($viaje);
                    break;

                case 3:
                    editarDestino($viaje);
                    break;

                case 4:
                    editarCantMaxPasajeros($viaje);
                    break;
                case 5:
                    agregarPasajero($viaje);
                    break;

                case 6:
                    editarPasajero($viaje);
                    break;

                case 7:
                    verPasajero($viaje);
                    break;
                case 8:
                    eliminarPasajero($viaje);
                    break;
                case 9:
                    verResponsable($viaje);
                    break;
                case 10:
                    editarResponsable($viaje);
                    break;
                case 11:
                    break;
                default:
                    echo "Opción inválida\n";
                    break;
            }
        } catch (Exception $e) {
            echo "\n\n\033[31m" . $e->getMessage() . "\033[0m\n\n";
        }
    }
}


testViaje();
