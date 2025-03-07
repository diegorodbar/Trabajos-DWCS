<?php

    // Verificar que se haya enviado el parámetro 'wp' vía POST. Si no se envía, redireccionar a la página "repartos.php" y terminar la ejecución.
    if (!isset($_POST['wp'])) {
        header('Location: repartos.php');
        exit;
    }
    
    $waypoints = $_POST['wp']; // Asignar el array de waypoints enviado por POST a la variable $waypoints.

?>

<!DOCTYPE html>

<html lang="es">

    <head>
        
        <meta charset="utf-8"> <!-- Definir la codificación de caracteres -->
        <title>Ruta de Reparto</title> <!-- Título de la página -->

        <!-- Incluir Bootstrap desde CDN para estilos -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <!-- Script JavaScript para funciones de manejo de parámetros en la URL y de inicialización del mapa -->
        <script type="text/javascript"> 

            // Función para obtener el valor de un parámetro de la URL dado su nombre.
            function getParameterByName(name) {
                
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]"); // Escapar corchetes en el nombre del parámetro.
                
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"), // Crear una expresión regular para buscar el parámetro en la cadena de consulta.
                    results = regex.exec(location.search); // Ejecutar la expresión regular sobre la URL.

                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " ")); // Retornar el valor del parámetro o una cadena vacía si no se encuentra.

            }
            
            var map, directionsManager; // Declaración de variables globales para el mapa y el administrador de direcciones.

            // Función para inicializar el mapa de Bing Maps.
            function initializeMap() {

                // Crear un nuevo mapa en el elemento con id "myMap", utilizando las credenciales proporcionadas.
                map = new Microsoft.Maps.Map('#myMap', {
                    credentials: 'Au1tWYSz_y_rJsqDnJtAojXcFtljvRI5tF5-qBIC5-wmr_Rk9qwPsF_GmvVjgPy1'
                });

                // Cargar el módulo de direcciones de Bing Maps.
                Microsoft.Maps.loadModule('Microsoft.Maps.Directions', function() {
                    
                    directionsManager = new Microsoft.Maps.Directions.DirectionsManager(map); // Crear una instancia del DirectionsManager para gestionar rutas en el mapa.
                    
                    <?php

                        // Recorrer cada uno de los waypoints recibidos y agregarlos al DirectionsManager.
                        for ($i = 0; $i < count($waypoints); $i++) {
                            // Generar código JavaScript para agregar el waypoint actual, utilizando sus coordenadas.
                            echo "directionsManager.addWaypoint(new Microsoft.Maps.Directions.Waypoint({ location: new Microsoft.Maps.Location({$waypoints[$i]}) }));\n";
                        }

                    ?>

                    // Configurar las opciones de solicitud para el cálculo de rutas.
                    directionsManager.setRequestOptions({
                        
                        distanceUnit: Microsoft.Maps.Directions.DistanceUnit.km, // Establecer la unidad de distancia a kilómetros.
                        routeAvoidance: [Microsoft.Maps.Directions.RouteAvoidance.avoidLimitedAccessHighway] // Evitar rutas que utilicen autopistas de acceso limitado.

                    });

                    // Configurar las opciones de renderizado de la ruta en el mapa.
                    directionsManager.setRenderOptions({
                        drivingPolylineOptions: {
                            strokeColor: 'green', // Establecer el color de la línea de la ruta (verde).
                            strokeThickness: 6 // Establecer el grosor de la línea de la ruta.
                        },
                        waypointPushpinOptions: {
                            
                            title: '' // Opciones para los pushpins de los waypoints (título vacío en este caso).
                        }
                    });
                    
                    directionsManager.calculateDirections(); // Calcular y renderizar la ruta con los waypoints añadidos.

                });

            }

        </script>

        <script type="text/javascript" src="https://www.bing.com/api/maps/mapcontrol?callback=initializeMap" async defer></script> <!-- Incluir el script de Bing Maps, que ejecuta initializeMap() al cargar -->

    </head>

    <body style="background:#00bfa5;">
        <div class="container mt-3"> <!-- Contenedor principal para la visualización del mapa y el botón de navegación -->
            <div class="d-flex justify-content-center"> <!-- Contenedor para centrar el mapa -->
                <div id="myMap" style="width:650px;height:420px;"></div> <!-- Elemento donde se renderizará el mapa con dimensiones definidas -->
            </div>
            <div class="d-flex justify-content-center mt-3"> <!-- Contenedor para centrar el botón de "Volver" -->
                <a href="repartos.php" class="btn btn-warning">Volver</a> <!-- Botón que redirecciona a la página "repartos.php" -->
            </div>
        </div>
    </body>

</html>