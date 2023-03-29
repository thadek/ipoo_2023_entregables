<?php

include_once('Viaje.php');


/**
 * Muestra el menú de opciones
 */
function Menu()
{

    escribirAmarillo("1. Ver información del viaje\n2. Ver pasajeros\n3. Editar destino viaje \n4. Editar cantidad máxima de pasajeros\n5. Agregar pasajero\n6. Eliminar pasajero\n7. Salir\n");
}

/**
 * Agrega un pasajero al viaje
 * @param Viaje $viaje
 */
function agregarPasajero($viaje)
{
    echo "Ingrese el nombre del pasajero: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese el apellido del pasajero: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingrese el DNI del pasajero: ";
    $dni = trim(fgets(STDIN));
    $pasajero = ["nombre" => $nombre, "apellido" => $apellido, "dni" => $dni];
    $viaje->addPasajero($pasajero);
}



/**
 * Elimina un pasajero del viaje
 * @param Viaje $viaje
 */
function eliminarPasajero($viaje)
{
    echo "Ingrese el DNI del pasajero a eliminar:";
    $dni = trim(fgets(STDIN));
    $viaje->removePasajero($dni);
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
 * Escribe en color amarillo
 * @param string $texto
 */
function escribirAmarillo($texto)
{
    echo "\033[33m" . $texto . "\033[0m";
}


/**
 * Test Viaje: Prueba la clase Viaje creando una instancia y probando sus métodos
 */
function testViaje()
{
    $pasajeros_ejemplo = [
        ["nombre" => "Juan", "apellido" => "Perez", "dni" => "98765432"],
        ["nombre" => "Maria", "apellido" => "Gomez", "dni" => "49787987"]
    ];
    $viaje = new Viaje("Buenos Aires", 5, $pasajeros_ejemplo);
    $opcion = 0;

    escribirVerde("\n\nVIAJE FELIZ S.A.\n\n");
    while ($opcion != 7) {
        try {
            Menu();
            echo "Ingrese una opción: ";
            $opcion = trim(fgets(STDIN));
            switch ($opcion) {
                case 1:
                    escribirVerde("\n" . $viaje . "\n");
                    break;
                case 2:
                    $viaje->verListaPasajeros();
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
                    eliminarPasajero($viaje);
                    break;   
                case 7:
                   break;
                default:
                    echo "Opción inválida";
                    break;
            }
        } catch (Exception $e) {
            echo "\n\n\033[31m" . $e->getMessage() . "\033[0m\n\n";
            
        }
    }
}


testViaje();
