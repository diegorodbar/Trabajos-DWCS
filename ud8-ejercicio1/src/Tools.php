<?php
    
    require_once 'Coordenadas.php'; // Incluir la clase Coordenadas para trabajar con la geolocalización y APIs asociadas.
    require_once __DIR__ . '/../vendor/autoload.php'; // Incluir el autoload de Composer para cargar todas las dependencias (incluyendo Jaxon).

    use Jaxon\Jaxon;
    use function Jaxon\jaxon;

    $jaxon = jaxon(); // Crear una instancia de Jaxon para gestionar llamadas AJAX.

    // Función para obtener las coordenadas (latitud, longitud y altitud) de una dirección dada.
    function getCoordenadas($direccion) {
        
        $respuesta = jaxon()->newResponse(); // Crear una nueva respuesta Jaxon para enviar comandos al cliente.
        
        $direccion = trim($direccion); // Eliminar espacios en blanco al inicio y final de la dirección.

        // Verificar que la dirección tenga al menos 4 caracteres. Si la dirección es muy corta, enviar una alerta de error.
        if (strlen($direccion) < 4) {
            
            $respuesta->alert("Coordenadas no válidas");
            return $respuesta;

        }

        
        $coordenadasObj = new Coordenadas($direccion); // Crear un objeto Coordenadas pasando la dirección proporcionada.
        
        $coords = $coordenadasObj->getCoordenadas(); // Obtener las coordenadas (array con latitud, longitud y altitud) del objeto.

        // Asignar la latitud, longitud y altitud a variables separadas.
        $lat = $coords[0];
        $lon = $coords[1];
        
        $alt = $coords[2] . " mts."; // Concatenar " mts." a la altitud para indicar la unidad de medida.
        
        $respuesta->assign('lat', 'value', $lat); // Asignar el valor de latitud al elemento HTML con id 'lat'.
        $respuesta->assign('lon', 'value', $lon); // Asignar el valor de longitud al elemento HTML con id 'lon'.
        $respuesta->assign('alt', 'value', $alt); // Asignar el valor de altitud al elemento HTML con id 'alt'.
        
        return $respuesta; // Retornar la respuesta Jaxon con las asignaciones realizadas.

    }

    // Función para ordenar los envíos (waypoints) de forma óptima según la distancia.
    function ordenarEnvios($puntos, $id) {
        
        $respuesta = jaxon()->newResponse(); // Crear una nueva respuesta Jaxon para enviar comandos al cliente.

        // Verificar que la cadena de puntos no esté vacía (después de eliminar espacios en blanco). Si la cadena es vacía, enviar una alerta al usuario.
        if (strlen(trim($puntos)) === 0) {

            $respuesta->alert("Puntos no válidos");
            return $respuesta;

        }

        $coordObj = new Coordenadas(); // Crear un objeto Coordenadas sin parámetros (se usará para ordenar los waypoints).
        
        $datos = $coordObj->ordenarEnvios($puntos); // Llamar al método ordenarEnvios del objeto, pasando la cadena de puntos.

        $datosRespuesta = ['respuesta' => $datos, 'id' => $id]; // Preparar un array con la respuesta que incluirá el resultado y el id recibido.
        
        $respuesta->call('obtuvimosDatos', $datosRespuesta); // Invocar la función JavaScript 'obtuvimosDatos' en el cliente y pasarle los datos.
        
        return $respuesta; // Retornar la respuesta Jaxon.

    }

    // Registrar las funciones getCoordenadas y ordenarEnvios para que sean accesibles vía AJAX mediante Jaxon.
    $jaxon->register(Jaxon::CALLABLE_FUNCTION, 'getCoordenadas');
    $jaxon->register(Jaxon::CALLABLE_FUNCTION, 'ordenarEnvios');

    // Si la solicitud puede ser procesada por Jaxon (por ejemplo, es una llamada AJAX), procesarla.
    if ($jaxon->canProcessRequest()) {

        $jaxon->processRequest();

    }

?>