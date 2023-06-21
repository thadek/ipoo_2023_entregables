<?php

//Clases
include_once('BaseDatos.php');
include_once('Empresa.php');
include_once('ResponsableV.php');
include_once('Viaje.php');
include_once('Pasajero.php');




//Funciones de escritura de colores en consola

/**
 * Escribe en color verde
 * @param string $texto
 */
function escribirVerde($texto)
{
    echo "\033[32m" . $texto . "\033[0m";
}

/**
 * Escribe en color rojo
 * @param string $texto
 */
function escribirRojo($texto)
{
    echo "\033[31m" . $texto . "\033[0m";
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
 * Escribe en color azul
 * @param string $texto
 */

function escribirAzul($texto)
{
    echo "\033[34m" . $texto . "\033[0m";
}



/**
 * @param string $val
 * Valida si el string recibido es S o N. En caso de ser S retorna true, en caso contrario retorna false.
 */
function leerBool($val)
{
    $val = strtoupper($val);
    while ($val != 'S' && $val != 'N') {
        echo "\nEl valor ingresado no es válido, ingrese nuevamente S/N : \n";
        $val = trim(fgets(STDIN));
        $val = strtoupper($val);
    }
    return $val == 'S' ? true : false;
}




/**
 * -------------------- Funciones de Viajes ----------------------
 */

//Menu ABM Viajes
function menuABMViajes($empresa)
{
    $opcion = 0;
    while ($opcion != 5) {
        escribirAzul(
            "Gestión Viajes de " . $empresa->getNombre() . "\n" .
                "1) Listado de viajes\n" .
                "2) Nuevo viaje \n" .
                "3) Modificación de un viaje \n" .
                "4) Borrar un viaje \n" .
                "5) Volver \n"
        );
        escribirVerde("Ingrese una opción: ");
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                listarViajes($empresa);
                break;
            case 2:
                asistenteAltaViajes($empresa);
                break;
            case 3:
                asistenteModificacionViajes($empresa);
                break;
            case 4:
                asistenteBajaViajes($empresa);
                break;
            case 5:
                escribirAmarillo("<<<<<<< Volviendo al menú principal \n");
                break;
            default:
                escribirRojo("Opción no válida. \n");
                break;
        }
    }
}


function listarViajes($empresa)
{
    $empresa->setViajes(Viaje::listar("idempresa = " . $empresa->getId()));

    if (count($empresa->getViajes()) == 0) {
        escribirRojo("No hay viajes cargados. \n");
    } else {
        escribirAmarillo("Listado de viajes: \n");
        escribirAmarillo($empresa->mostrarViajes());
    }
}


/**
 * Muestra una versión simplificada de los viajes disponibles para que el usuario pueda seleccionar uno por id. Devuelve el id del viaje seleccionado.
 * Si el id ingresado no es válido, lanza una excepción.
 * @param Empresa $empresa
 * @return int idViaje
 */
function seleccionarViaje($empresa)
{
    $empresa->setViajes(Viaje::listar("idempresa = " . $empresa->getId()));
    escribirAmarillo("Viajes disponibles: \n");
    $arrIDViajes = array();
    foreach ($empresa->getViajes() as $viaje) {
        $viaje->setPasajeros(Pasajero::listar("idviaje = " . $viaje->getId()));
        escribirAzul("----------------------------------------");
        escribirAmarillo("\nID: " . $viaje->getId() . " - DESTINO: " . $viaje->getDestino() . " - OCUPACIÓN: " . count($viaje->getPasajeros()) . "/" . $viaje->getCantMaxPasajeros() . " - IMPORTE: $" . $viaje->getImporte() . "\n");
        escribirAzul("----------------------------------------");
        array_push($arrIDViajes, $viaje->getId());
    }
    escribirVerde("\nIngrese el ID del viaje: ");
    $idViaje = trim(fgets(STDIN));
    if (!in_array($idViaje, $arrIDViajes)) {
        throw new Exception("ID de viaje no válido o inexistente. \n");
    }
    return $idViaje;
}

//Menu alta de viajes
function asistenteAltaViajes($empresa)
{
    escribirAzul("Ingrese el destino del viaje: \n");
    $destino = trim(fgets(STDIN));
    escribirAzul("Ingrese la cantidad máxima de pasajeros: \n");
    $cantMaxPasajeros = trim(fgets(STDIN));

    escribirAzul("Seleccione responsable del viaje: \n");
    $responsableId = seleccionarResponsable();
    $responsableV = new ResponsableV();
    $responsableV->setNroEmpleado($responsableId);
    $responsableV->cargar();

    escribirAzul("Ingrese el importe del viaje");
    $importe = trim(fgets(STDIN));
    $viaje = new Viaje();
    $viaje->setDestino($destino);
    $viaje->setCantMaxPasajeros($cantMaxPasajeros);
    $viaje->setResponsableV($responsableV);
    $viaje->setEmpresa($empresa);
    $viaje->setImporte($importe);
    if ($viaje->insertar()) {
        escribirVerde("Viaje creado con éxito \n");
        escribirVerde("Los datos del viaje son: \n" . $viaje);
    }
}

//Menu modificacion de viajes
function asistenteModificacionViajes($empresa)
{
    escribirAzul("Ingrese el ID del viaje a modificar: \n");
    $idViaje = seleccionarViaje($empresa);
    $viaje = new Viaje();
    $viaje->setId($idViaje);
    $viaje->cargar();

    escribirAzul("Ingrese el nuevo destino del viaje: \n");
    $destino = trim(fgets(STDIN));
    $viaje->setDestino($destino);

    escribirAzul("Ingrese la nueva cantidad máxima de pasajeros: \n");
    $cantMaxPasajeros = trim(fgets(STDIN));
    $viaje->setCantMaxPasajeros($cantMaxPasajeros);

    escribirAzul("Seleccione nuevo responsable del viaje: \n");
    $responsableVID = seleccionarResponsable();
    $responsableV = new ResponsableV();
    $responsableV->setNroEmpleado($responsableVID);
    $viaje->setResponsableV($responsableV);

    escribirAzul("Ingrese el nuevo importe del viaje: ");
    $importe = trim(fgets(STDIN));
    $viaje->setImporte($importe);

    //$viaje->setEmpresa($empresa);

    if ($viaje->actualizar()) {
        escribirVerde("\n\n\n-------------------------- Viaje modificado con éxito ------------------------------- \n\n\n");
        $viaje->cargar();
        escribirVerde("Los datos del viaje son: \n" . $viaje);
    }
}

//Menu baja de viajes
function asistenteBajaViajes($empresa)
{
    escribirAzul("Ingrese el ID del viaje a borrar: \n");
    $idViaje = seleccionarViaje($empresa);
    $viaje = new Viaje();
    $viaje->setId($idViaje);
    if ($viaje->eliminar()) {
        escribirVerde("Viaje borrado con éxito \n");
    }
}



/**
 * -------------------- Funciones de Responsable ----------------------
 */

/**
 * Muestra los responsables disponibles 
 */
function listarResponsables()
{
    $responsables = ResponsableV::listar();
    foreach ($responsables as $responsable) {
        //Cargo a demanda los viajes del responsable.-
        $viajes = Viaje::listar("rnumeroempleado=".$responsable->getNroEmpleado());
        $responsable->setViajes($viajes);
        escribirVerde($responsable);
    }
}

/**
 * Selecciona un responsable de la lista de responsables disponibles y retorna su id
 * @return int $idResponsable
 */
function seleccionarResponsable()
{
    $responsables = ResponsableV::listar();
    $responsables_id = array();
    foreach ($responsables as $responsable) {
        escribirVerde($responsable);
        array_push($responsables_id, $responsable->getNroEmpleado());
    }
    escribirAmarillo("\n Ingrese el numero de empleado del responsable a seleccionar: ");
    $idResponsable = trim(fgets(STDIN));
    if (!in_array($idResponsable, $responsables_id)) {
        throw new Exception("El responsable no existe");
    }
    return $idResponsable;
}


//Menu alta de responsables
function asistenteAltaResponsable()
{
    escribirAzul("Ingrese el nombre del responsable: \n");
    $nombre = trim(fgets(STDIN));
    escribirAzul("Ingrese el apellido del responsable: \n");
    $apellido = trim(fgets(STDIN));
    escribirAzul("Ingrese numero de licencia del responsable: \n");
    $licencia = trim(fgets(STDIN));
    $responsable = new ResponsableV();
    $responsable->setNombre($nombre);
    $responsable->setApellido($apellido);
    $responsable->setNroLicencia($licencia);
    if ($responsable->insertar()) {
        escribirVerde("Responsable creado con éxito \n");
        escribirVerde("Los datos del responsable son: \n" . $responsable);
    }
}

//Menu modificacion de responsables
function asistenteModificacionResponsable()
{
    escribirAzul("Ingrese el Numero de empleado del responsable a modificar: \n");
    escribirAmarillo("Responsables disponibles: \n");
    $idResponsable = seleccionarResponsable();
    $responsable = new ResponsableV();
    $responsable->setNroEmpleado($idResponsable);
    $responsable->cargar();
    escribirAzul("Ingrese el nuevo nombre del responsable: \n");
    $nombre = trim(fgets(STDIN));
    escribirAzul("Ingrese el nuevo apellido del responsable: \n");
    $apellido = trim(fgets(STDIN));
    escribirAzul("Ingrese el nuevo numero de licencia del responsable: \n");
    $licencia = trim(fgets(STDIN));
    $responsable->setNombre($nombre);
    $responsable->setApellido($apellido);
    $responsable->setNroLicencia($licencia);
    if ($responsable->actualizar()) {
        escribirVerde("Responsable modificado con éxito \n");
        escribirVerde("Los datos del responsable son: \n" . $responsable);
    }
}


//Menu baja de responsables
function asistenteBajaResponsable()
{
    escribirRojo("############### Borrar un responsable ###############\n");
    escribirAzul("Lista de responsables disponibles:");
    $idResponsable = seleccionarResponsable();
    $responsable = new ResponsableV();
    $responsable->setNroEmpleado($idResponsable);
    //Verificar que no tenga viajes asignados
    $viajes = Viaje::listar("rnumeroempleado=".$idResponsable);
    if (count($viajes) > 0) {
        throw new Exception("El responsable tiene viajes asignados, por lo que no se lo puede eliminar.");
    }
    if ($responsable->eliminar()) {
        escribirVerde("Responsable borrado con éxito \n");
    }
}




function menuABMResponsable()
{
    $opcion = 0;
    while ($opcion != 5) {
        escribirAzul(
            "Gestión Responsables Viajes \n" .
                "1) Listado de responsables\n" .
                "2) Nuevo responsable \n" .
                "3) Modificación de un responsable \n" .
                "4) Borrar un responsable \n" .
                "5) Volver \n"
        );
        escribirVerde("Ingrese una opción: ");
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                listarResponsables();
                break;
            case 2:
                asistenteAltaResponsable();
                break;
            case 3:
                asistenteModificacionResponsable();
                break;
            case 4:
                asistenteBajaResponsable();
                break;
            case 5:
                escribirAmarillo("<<<<<<< Volviendo al menú principal \n");
                break;
            default:
                escribirRojo("Opción no válida. \n");
                break;
        }
    }
}




/**
 * -------------------- Funciones de Pasajero ----------------------
 */


function menuABMPasajero($empresa)
{
    $opcion = 0;
    while ($opcion != 5) {
        escribirVerde("\nGestión Pasajeros de " . $empresa->getNombre() . "\n");
        escribirAzul(
            "1) Listado de pasajeros por viaje\n" .
                "2) Agregar nuevo pasajero a un viaje \n" .
                "3) Modificación de un pasajero existente\n" .
                "4) Borrar un pasajero \n" .
                "5) Volver \n"
        );
        escribirVerde("Ingrese una opción: ");
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                listarPasajerosPorViaje($empresa);
                break;
            case 2:
                asistenteAltaPasajero($empresa);
                break;
            case 3:
                asistenteModificacionPasajero($empresa);
                break;
            case 4:
                asistenteBajaPasajero();
                break;
            case 5:
                escribirAmarillo("<<<<<<< Volviendo al menú principal \n");
                break;
            default:
                escribirRojo("Opción no válida. \n");
                break;
        }
    }
}



function listarPasajerosPorViaje($empresa)
{
    $idViaje = seleccionarViaje($empresa);
    $pasajeros = Pasajero::listar("idviaje = $idViaje");
    if (count($pasajeros) == 0) {
        escribirRojo("\n\nNo hay pasajeros para este viaje \n\n");
    } else {
        escribirAzul("\n\nPasajeros del viaje: \n\n");
        foreach ($pasajeros as $pasajero) {
            escribirVerde($pasajero);
        }
    }
}


/**
 * Funcion que verifica si un pasajero posee un viaje asignado
 */
function verificarSiPoseeViajeAsignado($dni)
{
    $retorno = false;
    $p = Pasajero::listar("pdocumento = $dni");
    if (count($p) > 0) {
        $retorno = true;
    }
    return $retorno;
}

//Menu alta de pasajeros
function asistenteAltaPasajero($empresa)
{
    escribirAzul("Ingrese el dni del pasajero: \n");
    $dni = trim(fgets(STDIN));
    if (verificarSiPoseeViajeAsignado($dni)) {
        throw new Exception("El pasajero ya posee un viaje asignado, utilice la opción de modificar pasajero");
    }
    $idViaje = seleccionarViaje($empresa);
    escribirAzul("Ingrese el nombre del pasajero: \n");
    $nombre = trim(fgets(STDIN));
    escribirAzul("Ingrese el apellido del pasajero: \n");
    $apellido = trim(fgets(STDIN));
    escribirAzul("Ingrese el telefono del pasajero: \n");
    $telefono = trim(fgets(STDIN));


    $viaje = new Viaje();
    $viaje->setId($idViaje);
    $viaje->cargar();


    $pasajero = new Pasajero();
    $pasajero->cargarPasajero($nombre, $apellido, $dni, $telefono, $viaje);
    //Verificar si el viaje no va lleno y agregarlo al arreglo.
    $viaje->agregarPasajeroAlArray($pasajero);

    $pasajero->setViaje($viaje);
    if ($pasajero->insertar()) {
        escribirVerde("Pasajero creado con éxito \n");
        escribirVerde("Los datos del pasajero son: \n" . $pasajero);
    }
}


//Menu modificacion de pasajeros
function asistenteModificacionPasajero($empresa)
{
    escribirAzul("Ingrese el dni del pasajero: \n");
    $dni = trim(fgets(STDIN));
    $pasajero = new Pasajero();
    $pasajero->setDni($dni);
    $pasajero->cargar();

    escribirAzul("Ingrese el nombre del pasajero: \n");
    $nombre = trim(fgets(STDIN));
    escribirAzul("Ingrese el apellido del pasajero: \n");
    $apellido = trim(fgets(STDIN));
    escribirAzul("Ingrese el telefono del pasajero: \n");
    $telefono = trim(fgets(STDIN));
    $pasajero->setNombre($nombre);
    $pasajero->setApellido($apellido);
    $pasajero->setTelefono($telefono);

    //Editar numero de viaje
    escribirAmarillo("Seleccione el nuevo viaje a asignar a este pasajero: \n");
    $idViaje = seleccionarViaje($empresa);
    $viaje = new Viaje();
    $viaje->setId($idViaje);
    $viaje->cargar();
    if(count($viaje->getPasajeros()) == $viaje->getCantMaxPasajeros()){
        throw new Exception("El viaje seleccionado ya se encuentra lleno, seleccione otro viaje");
    }
    $pasajero->setViaje($viaje);

    if($pasajero->actualizar()){
        escribirVerde("Pasajero modificado con éxito \n");
        escribirVerde("Los datos del pasajero son: \n" . $pasajero);
    }
}


//Menu baja de pasajeros
function asistenteBajaPasajero()
{
    escribirAzul("Ingrese el dni del pasajero a eliminar: \n");
    $dni = trim(fgets(STDIN));
    $pasajero = new Pasajero();
    $pasajero->setDni($dni);
    $pasajero->cargar();
    if ($pasajero->eliminar()) {
        escribirVerde("Pasajero borrado con éxito \n");
    }
}



  



/**
 * -------------------- Funciones de Empresa ----------------------
 */

//Menu ABM Empresa
function menuABMEmpresa($empresa)
{
    $opcion = 0;
    while ($opcion != 6) {
        escribirVerde("\nGestión Empresas \n");
        escribirAzul(
            "1) Cambiar empresa seleccionada para operar sistema \n" .
                "2) Listado \n" .
                "3) Alta \n" .
                "4) Modificación \n" .
                "5) Baja \n" .
                "6) Volver \n"
        );
        escribirVerde("Ingrese una opción: ");
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case 1:
                $empresa = seleccionarEmpresa($empresa);
                break;
            case 2:
                listarEmpresas();
                break;
            case 3:
                asistenteAltaEmpresa();
                break;
            case 4:
                $modificada = asistenteModificacionEmpresa();
                if ($modificada->getId() == $empresa->getId()) {
                    $empresa = $modificada;
                }
                break;
            case 5:
                asistenteBajaEmpresa();
                break;
            case 6:
                escribirAmarillo("<<<<<<< Volviendo al menú principal \n");
                break;
            default:
                escribirRojo("Opción no válida. \n");
                break;
        }
        return $empresa;
    }
}


//ListarEmpresas
function listarEmpresas()
{
    escribirAzul("\n Lista de empresas disponibles:\n");
    $empresas = Empresa::listar();
    foreach ($empresas as $empresa) {
        $empresa->setViajes(Viaje::listar("idempresa = " . $empresa->getId()));
        escribirAmarillo($empresa);
    }
    return $empresas;
}


//Seleccionar objeto empresa por id y retornarlo
function seleccionarEmpresa()
{
    $empresas = listarEmpresas();
    escribirAzul("Ingrese el id de la empresa: ");
    $idEmpresa = trim(fgets(STDIN));
    $retorno = null;
    $i = 0;
    while ($retorno == null && $i < count($empresas)) {
        $empresa = $empresas[$i];
        if ($empresa->getId() == $idEmpresa) {
            $retorno = $empresa;
        }
        $i++;
    }
    if ($retorno == null) {
        throw new Exception("No se encontró la empresa con id $idEmpresa \n");
    }
    return $retorno;
}


//Menu alta de empresas
function asistenteAltaEmpresa()
{
    escribirRojo("############### Alta de una empresa ###############\n");
    escribirAzul("Ingrese el nombre de la empresa: ");
    $nombre = trim(fgets(STDIN));
    escribirAzul("Ingrese la dirección de la empresa: ");
    $direccion = trim(fgets(STDIN));
    $empresa = new Empresa();
    $empresa->setNombre($nombre);
    $empresa->setDireccion($direccion);
    if ($empresa->insertar()) {
        escribirVerde("Empresa guardada con éxito \n");
        escribirAmarillo("DATOS: \n" . $empresa . "\n");
    }
    return $empresa;
}



//Menu modificacion de empresas
function asistenteModificacionEmpresa()
{
    escribirRojo("############### Modificar una empresa ###############\n");
    $empresa = seleccionarEmpresa();
    escribirRojo("############### SELECCIONASTE LA EMPRESA: " . $empresa->getNombre() . " ###############\n");
    escribirAzul("Ingrese el nuevo nombre de la empresa: ");
    $nombre = trim(fgets(STDIN));
    escribirAzul("Ingrese la nueva dirección de la empresa: ");
    $direccion = trim(fgets(STDIN));
    $empresa->setNombre($nombre);
    $empresa->setDireccion($direccion);
    if ($empresa->actualizar()) {
        escribirVerde("Empresa modificada con éxito \n");
        escribirAmarillo("DATOS: \n" . $empresa . "\n");
    }
    return $empresa;
}



//Menu baja de empresas
function asistenteBajaEmpresa()
{
    escribirRojo("############### Borrar una empresa ###############\n");
    $empresa = seleccionarEmpresa();
    escribirRojo("############### SELECCIONASTE LA EMPRESA: " . $empresa->getNombre() . " ###############\n");
    escribirAmarillo("\nTomá en cuenta que si la empresa tiene viajes asignados, no se podrá borrar. \n");
    escribirAmarillo("¿Estás seguro que querés borrar esta empresa? (s/n) \n");
    $opcion = trim(fgets(STDIN));
    $opcionElegida = leerBool($opcion);
    if ($opcionElegida) {
        try {
            if ($empresa->eliminar()) {
                escribirVerde("Empresa borrada con éxito \n");
            }
        } catch (Exception $e) {
            escribirRojo("\n La empresa posee viajes asignados, por lo que es imposible borrarla. \n");
        }
    } else {
        escribirRojo("No se borró la empresa. \n");
    }
}









/**
 * -------------------- FUNCION PRINCIPAL ----------------------
 */

//Menu principal
function menuPrincipal($empresa)
{
    escribirVerde("Gestor " . $empresa->getNombre() . "\n");
    escribirAzul(
        "1) Gestión Empresa \n" .
            "2) Gestión Viajes \n" .
            "3) Gestión Pasajeros \n" .
            "4) Gestión Responsables \n" .
            "5) Salir \n"
    );
}


function testViaje()
{
    $empresa = new Empresa();
    $empresa->setId(1);
    $empresa->cargar();
    $opcion = 0;

    while ($opcion != 5) {
        try {

            menuPrincipal($empresa);
            escribirVerde("Ingrese una opción: ");
            $opcion = trim(fgets(STDIN));
            switch ($opcion) {
                case 1:
                    $empresa = menuABMEmpresa($empresa);
                    break;
                case 2:
                    menuABMViajes($empresa);
                    break;
                case 3:
                    menuABMPasajero($empresa);
                    break;
                case 4:
                    menuABMResponsable();
                    break;
                case 5:
                    break;
                default:
                    escribirRojo("Opción no válida. \n");
                    break;
            }
        } catch (Exception $e) {
            escribirRojo("\n\n" . $e->getMessage() . "\n\n");
        }
    }
}


testViaje();
