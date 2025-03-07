<?php

    // Verifica si se ha recibido la latitud en la URL, de lo contrario, redirige a la página de repartos
    if (!isset($_GET['lat'])) {
        header('Location: repartos.php');
        exit;
    }

    require_once __DIR__ . "/../src/claves.inc.php"; // Incluye el archivo de claves para obtener la clave de la API de Bing Maps
    
    $urlBingMaps = 'https://www.bing.com/api/maps/mapcontrol?key=' . $keyBing; // Construye la URL para cargar la API de Bing Maps con la clave proporcionada

?>

<!DOCTYPE html>
<html lang="es">

    <head>

        <meta charset="utf-8"> <!-- Definir juego de caracteres -->
        <title>Mapa</title> <!-- Título de la página -->

        <!-- Carga de la hoja de estilos de Bootstrap desde un CDN -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <script type="text/javascript" src="<?php echo $urlBingMaps; ?>"></script> <!-- Carga de la API de Bing Maps con la clave obtenida -->

        <script type="text/javascript">

            // Función para obtener parámetros de la URL por nombre
            function getParameterByName(name) {

                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));

            }

            var map; // Variable para almacenar el mapa
            var lat = getParameterByName('lat'); // Obtiene la latitud desde la URL
            var lon = getParameterByName('lon'); // Obtiene la longitud desde la URL

            // Función que carga el mapa con los parámetros de latitud y longitud obtenidos
            function loadMapScenario() {

                map = new Microsoft.Maps.Map(document.getElementById('myMap'), {
                    center: new Microsoft.Maps.Location(lat, lon), // Centra el mapa en las coordenadas especificadas
                    mapTypeId: Microsoft.Maps.MapTypeId.canvasLight, // Establece el tipo de mapa
                    zoom: 17 // Nivel de zoom del mapa
                });

            }

        </script>

    </head>

    <body onload="loadMapScenario();" style="background:#00bfa5;">
        <div class="container mt-3">
            <div class="d-flex justify-content-center">
                <div id="myMap" style="width:650px; height:420px;"></div> <!-- Contenedor donde se renderizará el mapa -->
            </div>
            <div class="d-flex justify-content-center mt-3">
                <a href="repartos.php" class="btn btn-warning">Volver</a> <!-- Botón para volver a la página de repartos -->
            </div>
        </div>
    </body>
    
</html>