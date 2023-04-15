<?php

include_once('Viaje.php');
include_once('ResponsableV.php');
include_once('Pasajero.php');


/**
 * Muestra el menú de opciones
 */
function Menu()
{

    escribirAmarillo("1. Ver información del viaje\n2. Ver pasajeros\n3. Editar destino viaje \n4. Editar cantidad máxima de pasajeros\n5. Agregar pasajero\n6. Editar Pasajero \n7. Ver pasajero por DNI \n8. Eliminar pasajero\n9. Ver Responsable del viaje\n10. Editar Responsable del viaje\n11. Salir\n");
}

/**
 * lee y valida que el valor ingresado sea el tipo especificado, en caso de ser asi lo retorna, 
 * en caso contrario lanza una excepcion.
 * @param string $mensaje
 * @param string $tipo
 * @return string
 */
function leerYValidarValor($mensaje,$tipo)
{
    echo $mensaje;
    $valor = trim(fgets(STDIN));
    while(!validarValor($valor,$tipo)){
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
function validarValor($valor,$tipo)
{
    switch($tipo){
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
 * Agrega un pasajero al viaje
 * @param Viaje $viaje
 */
function agregarPasajero($viaje)
{
    $nombre = leerYValidarValor("\nIngrese el nombre del pasajero: ","string");
    $apellido = leerYValidarValor("\nIngrese el apellido del pasajero: ","string");
    $dni = leerYValidarValor("\nIngrese el DNI del pasajero: ","string");
    $telefono = leerYValidarValor("\nIngrese el telefono del pasajero: ","string");
    $pasajero = new Pasajero($nombre, $apellido, $dni, $telefono);
    $viaje->addPasajero($pasajero);
}



/**
 * Elimina un pasajero del viaje
 * @param Viaje $viaje
 */
function eliminarPasajero($viaje)
{
    echo "Ingrese el DNI del pasajero a eliminar:\n";
    $dni = trim(fgets(STDIN));
    $isDeleted = $viaje->removePasajero($dni);
    if($isDeleted){
        echo "\nSe eliminó correctamente el pasajero.\n";
    }else{
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
function verPasajeros($viaje){
    echo $viaje->verListaPasajeros();
}

function editarPasajero($viaje){
    echo "Ingrese el DNI del pasajero a modificar\n";
    $dni = trim(fgets(STDIN));
    $pasajeroIndex = $viaje->getPasajero($dni);
    if($pasajeroIndex === null){
        throw new Exception("No se encontró el pasajero con DNI: $dni");
    }
    $nombre = leerYValidarValor("Ingrese el nombre del pasajero:\n","string");
    $apellido = leerYValidarValor("Ingrese el apellido del pasajero:\n","string");
    $telefono = leerYValidarValor("Ingrese el telefono del pasajero:\n","string");
    $viaje->updatePasajero($pasajeroIndex,$nombre,$apellido,$telefono);
    echo "\nPasajero actualizado\n"; 
}

function verPasajero($viaje){
    $dni = leerYValidarValor("Ingrese el DNI del pasajero:\n","string");
    $pasajeroIndex = $viaje->getPasajero($dni);
    if($pasajeroIndex || $pasajeroIndex === 0){
        echo $viaje->getPasajeros()[$pasajeroIndex];        
    }else{
        throw new Exception("No se encontró el pasajero con DNI: $dni");
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
function verResponsable($viaje){
    echo $viaje->getResponsableV();
    echo "\n";
}

/**
 * Editar responsable del viaje
 */
function editarResponsable($viaje){
    $responsable = $viaje->getResponsableV();
    $responsable->setNombre(leerYValidarValor("Ingrese el nombre del responsable:\n","string"));
    $responsable->setApellido(leerYValidarValor("Ingrese el apellido del responsable:\n","string"));
    $responsable->setNroLicencia(leerYValidarValor("Ingrese el nro de licencia del responsable:\n","int"));
    $responsable->setNroEmpleado(leerYValidarValor("Ingrese el nro de empleado del responsable:\n","int"));
   // $viaje->setResponsable($responsable);
}

/**
 * Test Viaje: Prueba la clase Viaje creando una instancia y probando sus métodos
 */
function testViaje()
{
    $pasajerosEjemplo = [
        new Pasajero("Juan","Perez","123","+541184564436"),
        new Pasajero("Maria","Gomez","87654321","+541182334436"),
        new Pasajero("Pedro","Rodriguez","1234","+54112564436"),
    ];

    $responsable = new ResponsableV(12345,94974,"Matias","Levpold");

    $viaje = new Viaje("Buenos Aires", 5,$pasajerosEjemplo, $responsable);
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
