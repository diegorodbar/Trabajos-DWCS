<?php

    // Clase Coordenadas encargada de interactuar con las APIs de Bing para obtener información de localización y altitud, así como para ordenar puntos de envío (waypoints) optimizando la distancia.
    class Coordenadas {
        
        private static $key = ""; // Clave de la API de Bing. Inicialmente vacía; se asignará al incluir el archivo de claves.
        private static $baseUrl = "http://dev.virtualearth.net/REST/v1/Locations/ES/Cangas/"; // URL base para obtener localizaciones en una zona concreta. Cambiar 'Cangas' por la localidad deseada. 
        private $endUrl; // Propiedad para almacenar la parte final de la URL (parámetros de consulta).
        private $url; // Propiedad para almacenar la URL completa que se utilizará para la solicitud.

        // Constructor de la clase.
        public function __construct() {
            
            include(__DIR__ . '/claves.inc.php'); // Incluir el archivo que contiene las claves de acceso, se espera que defina la variable $keyBing.
            
            self::$key = $keyBing; // Asignar la clave de Bing a la variable estática $key.
            
            $numArgs = func_num_args(); // Obtener el número de argumentos pasados al constructor.

            // Si se ha pasado un argumento, se asume que es la dirección que se quiere buscar.
            if ($numArgs === 1) {
                
                $direccion = str_replace(" ", "%20", func_get_arg(0)); // Reemplazar espacios en la dirección por "%20" para que la URL sea válida.
                $this->endUrl = "?include=ciso2&maxResults=1&c=es&key=" . self::$key; // Construir la parte final de la URL con parámetros: incluir ciso2, limitar a 1 resultado, especificar país (es) y la clave.    
                $this->url = self::$baseUrl . $direccion . $this->endUrl; // Construir la URL completa concatenando la URL base, la dirección formateada y la parte final.

            } else { $this->endUrl = "?include=ciso2&maxResults=1&c=es&key=" . self::$key; } // Si no se pasa ningún argumento, solo se establece la parte final de la URL.

        }

        // Método para obtener las coordenadas (latitud, longitud y altitud) de la dirección especificada.
        public function getCoordenadas() {
            
            $contenido = file_get_contents($this->url); // Obtener el contenido de la URL construida, que retorna datos en formato JSON.
            $jsonData = json_decode($contenido, true); // Decodificar el JSON a un array asociativo.          
            $coords = $jsonData["resourceSets"][0]["resources"][0]["point"]["coordinates"]; // Extraer las coordenadas (latitud y longitud) del primer recurso obtenido.       
            $coords[2] = $this->calculaAltitud($coords); // Calcular la altitud usando el método calculaAltitud y asignarla como tercer valor.
            
            return $coords; // Retornar el array de coordenadas que ahora incluye latitud, longitud y altitud.

        }

        // Método para calcular la altitud basado en las coordenadas (latitud y longitud).
        public function calculaAltitud($coords) {

            // Extraer la latitud y la longitud del array de coordenadas.
            $lat = $coords[0];
            $lon = $coords[1];
            
            $urlElevacion = "http://dev.virtualearth.net/REST/v1/Elevation/List?points={$lat},{$lon}&key=" . self::$key; // Construir la URL para obtener la elevación utilizando Bing.         
            $contenido = file_get_contents($urlElevacion); // Obtener el contenido de la URL de elevación (respuesta en JSON).         
            $jsonElevacion = json_decode($contenido, true); // Decodificar el JSON a un array asociativo.
            
            return $jsonElevacion["resourceSets"][0]["resources"][0]["elevations"][0]; // Retornar la primera elevación obtenida del recurso.

        }

        // Método para ordenar envíos (waypoints) optimizando la distancia utilizando la API de rutas de Bing. El parámetro $dato es una cadena con puntos separados por "|".
        public function ordenarEnvios($dato) {
            
            $base = "http://dev.virtualearth.net/REST/v1/Routes/Driving?c=es&wp.0=36.86071,-2.440779&"; // URL base para obtener una ruta de conducción, iniciando desde unas coordenadas fijas.
            
            $puntos = explode("|", $dato); // Dividir la cadena $dato en un array de puntos, usando "|" como separador.
            
            $num = 1; // Inicializar un contador para numerar los waypoints (comienza en 1).
            $trozo = ""; // Inicializar una variable para construir la parte de la URL con los waypoints.

            // Recorrer cada punto del array.
            foreach ($puntos as $punto) {
                $trozo .= "wp." . $num++ . "=" . $punto . "&"; // Añadir a la variable $trozo el waypoint en el formato "wp.[número]=[punto]&" y aumentar el contador.
            }

            $trozo .= "wp." . $num . "=36.86071,-2.440779&optwp=true&optimize=distance&ra=routePath&key=" . self::$key; // Añadir el último waypoint fijo, opciones de optimización y la clave de acceso a la URL.
            $urlCompleta = $base . $trozo; // Concatenar la URL base con la parte construida para obtener la URL completa.

            $respuesta = $this->getRemoteFile($urlCompleta); // Obtener la respuesta remota desde la URL completa utilizando el método getRemoteFile.
            $jsonRespuesta = json_decode($respuesta, true); // Decodificar la respuesta JSON a un array asociativo.

            // Verificar si la respuesta contiene errores y si el código de estado es 404.
            if (isset($jsonRespuesta["errors"]) && $jsonRespuesta['statusCode'] == 404) {
                return "404"; // Retornar "404" en caso de error.
            }
            
            $waypointsOrder = $jsonRespuesta["resourceSets"][0]["resources"][0]['waypointsOrder']; // Obtener el orden de los waypoints desde la respuesta.        
            
            array_shift($waypointsOrder); // Eliminar el primer elemento del array (el punto de inicio).
            array_pop($waypointsOrder); // Eliminar el último elemento del array (el punto de finalización).
            
            $resp = []; // Inicializar un array para almacenar el orden final de los envíos.

            // Recorrer cada waypoint en el orden obtenido.
            foreach ($waypointsOrder as $wp) {      
                $resp[] = substr(strstr($wp, '.'), 1); // Extraer y almacenar la parte numérica o identificador del waypoint, eliminando la parte anterior al punto.
            }
            
            return $resp; // Retornar el array con el orden de envíos optimizado.

        }

        // Método para obtener el contenido remoto de una URL utilizando cURL.
        public function getRemoteFile($url, $timeout = 10) {
            
            $ch = curl_init(); // Inicializar una sesión cURL.
            
            curl_setopt($ch, CURLOPT_URL, $url); // Establecer la URL de destino.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Configurar cURL para que retorne el contenido como cadena en lugar de imprimirlo directamente.
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // Establecer el tiempo de espera para la conexión.
            
            $contenido = curl_exec($ch); // Ejecutar la solicitud cURL y almacenar el contenido obtenido.
            
            curl_close($ch); // Cerrar la sesión cURL para liberar recursos.
            
            return ($contenido) ? $contenido : false; // Retornar el contenido obtenido o false en caso de fallo.
            
        }

    }

?>