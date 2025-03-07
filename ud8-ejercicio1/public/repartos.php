<?php

    require_once __DIR__ . '/../src/Tasks.php'; // Incluir archivo de definición de tareas
    require_once __DIR__ . '/../vendor/autoload.php'; // Incluir el autoload de Composer para cargar librerías

    // Importar la clase Jaxon y la función jaxon del namespace Jaxon
    use Jaxon\Jaxon;
    use function Jaxon\jaxon;

    $coordenadasAlmacen = "36.86071,-2.440779"; // Definir las coordenadas de almacenamiento (ubicación fija)

    $service = new Google_Service_Tasks($client); // Crear una instancia del servicio de Google Tasks ($client está definido previamente)

    // Función para obtener las listas de tareas, con un límite de 100 resultados
    function getListasTareas() {
        
        global $service; // Usar la variable global $service
        
        $optParams = ['maxResults' => 100]; // Parámetros para limitar el número de resultados
        
        return $service->tasklists->listTasklists($optParams); // Retornar la lista de tareas obtenida del servicio de Google Tasks

    }

    // Función para obtener las tareas de una lista específica
    function getTareas($idLista) {
        
        global $service; // Usar la variable global $service
        
        return $service->tasks->listTasks($idLista); // Retornar las tareas de la lista indicada

    }

    // Función para obtener las notas de una tarea específica, buscando por su título
    function getNotes($idLista, $titulo) {
        
        $tareas = getTareas($idLista); // Obtener todas las tareas de la lista indicada

        // Recorrer cada tarea obtenida
        foreach ($tareas->getItems() as $tarea) {
            // Si el título de la tarea coincide con el título buscado, retornar sus notas
            if ($tarea->getTitle() === $titulo) {
                return $tarea->getNotes();
            }
        }
        
        return ""; // Si no se encontró ninguna tarea con ese título, retornar una cadena vacía
    }

    require_once __DIR__ . '/../src/Tools.php'; // Incluir archivo con herramientas adicionales

    $jaxon = jaxon(); // Inicializar Jaxon para gestionar llamadas AJAX
    
    $jaxon->register(Jaxon::CALLABLE_FUNCTION, 'ordenarEnvios'); // Registrar la función 'ordenarEnvios' para que sea invocable vía AJAX

    // Si Jaxon puede procesar la solicitud (por ejemplo, si se ha hecho una llamada AJAX), procesarla
    if ($jaxon->canProcessRequest()) {
        $jaxon->processRequest();
    }

    // Procesar formularios y acciones según se envíen datos

    // Si se envía el formulario con la latitud (indica que se está creando una tarea)
    if (isset($_POST['lat'])) {
        
        $nota = $_POST['lat'] . "," . $_POST['lon']; // Combinar latitud y longitud en una cadena separada por coma para las notas
        
        $titulo = ucwords($_POST['pro']) . ". " . ucwords($_POST['dir']); // Crear el título de la tarea uniendo la propiedad y la dirección, con mayúsculas iniciales
        
        $idListaTarea = $_POST['idLTarea']; // Obtener el ID de la lista de tareas donde se agregará la nueva tarea
        
        unset($_SESSION[$idListaTarea]); // Eliminar cualquier dato en sesión relacionado con esa lista para evitar datos obsoletos
        
        $opciones = ['title' => $titulo, 'notes' => $nota]; // Crear un array con las opciones para la nueva tarea (título y notas)
        
        $nuevaTarea = new Google_Service_Tasks_Task($opciones); // Crear una nueva instancia de tarea con las opciones definidas

        // Intentar insertar la nueva tarea en la lista de tareas
        try {
            $service->tasks->insert($idListaTarea, $nuevaTarea);
        } catch (Google_Exception $ex) { exit("Error al guardar la tarea: " . $ex->getMessage()); } // Si ocurre un error, detener la ejecución y mostrar el mensaje de error
        
        unset($_POST['lat']); // Eliminar la variable POST 'lat' para evitar reenvíos accidentales

    }

    // Verificar si se ha enviado alguna acción a través de GET
    if (isset($_GET['action'])) {
        
        $accion = $_GET['action']; // Almacenar la acción en una variable

        // Evaluar la acción mediante un switch
        switch ($accion) {

            // Caso: borrar lista de tareas ('blt')
            case 'blt':

                try {
                    $service->tasklists->delete($_GET['idlt']); // Intentar borrar la lista de tareas utilizando el ID pasado en GET
                } catch (Google_Exception $ex) {
                    exit("Error al borrar la lista de tareas: " . $ex->getMessage()); // En caso de error, detener la ejecución y mostrar el mensaje de error
                }
                
                unset($_SESSION[$_GET['idlt']]); // Eliminar la sesión asociada a la lista borrada
                break;

            // Caso: borrar tarea individual ('bt')
            case 'bt':

                try {
                    $service->tasks->delete($_GET['idlt'], $_GET['idt']); // Intentar borrar la tarea utilizando el ID de la lista y el ID de la tarea
                } catch (Google_Exception $ex) {
                    exit("Error al borrar la tarea: " . $ex->getMessage());  // En caso de error, detener la ejecución y mostrar el mensaje de error
                }
                
                unset($_SESSION[$_GET['idlt']]); // Eliminar la sesión asociada a la lista de la tarea borrada
                break;

            // Caso: crear una nueva lista de tareas ('nlt')
            case 'nlt':
                
                $fecha = new DateTime($_GET['title']); // Crear un objeto DateTime a partir del parámetro 'title' (fecha) recibido
                
                $fechaFormateada = $fecha->format("d/m/Y"); // Formatear la fecha en el formato "día/mes/año"
                
                $tituloLista = "Repartos " . $fechaFormateada; // Construir el título de la lista concatenando "Repartos " y la fecha formateada
                
                // Verificar si ya existe una lista con ese nombre
                if (!existeLista($tituloLista)) {
                    
                    $opciones = ["title" => $tituloLista]; // Crear un array de opciones para la nueva lista, con el título definido
                    
                    $nuevaLista = new Google_Service_Tasks_TaskList($opciones); // Crear una nueva instancia de lista de tareas
                    
                    try {
                        $service->tasklists->insert($nuevaLista); // Intentar insertar la nueva lista de tareas
                    } catch (Google_Exception $ex) {
                        exit("Error al crear una lista de tareas: " . $ex->getMessage()); // Si ocurre un error, detener la ejecución y mostrar el mensaje de error
                    }

                } else {

                    echo "<script>alert('Ya existe un reparto para ese día !!!');</script>"; // Si la lista ya existe, mostrar una alerta en el navegador

                }

                break;

            // Caso: ordenar envíos ('oEnvios')
            case 'oEnvios':

            
                if (!isset($_GET['pos'])) { break; } // Verificar que se hayan enviado las posiciones (pos)
                
                $posiciones = $_GET['pos']; // Obtener las posiciones recibidas por GET
                
                $idLista = $_GET['idLt']; // Obtener el ID de la lista de tareas desde GET
                
                unset($_SESSION['idLt']); // Eliminar cualquier dato en sesión asociado a 'idLt'
                
                $tareas = getTareas($idLista); // Obtener las tareas de la lista indicada
                
                $ordenEnvios = []; // Inicializar un array para guardar el orden de envíos

                // Recorrer cada posición recibida
                foreach ($posiciones as $indice => $valor) {
                    $pos = $valor - 1; // Ajustar el índice (el envío 1 corresponde al índice 0)
                    $ordenEnvios[$indice] = $tareas->getItems()[$pos]->getTitle(); // Guardar el título de la tarea correspondiente a la posición ajustada en el array
                }
                
                $_SESSION[$idLista] = $ordenEnvios; // Almacenar el orden de envíos en la sesión utilizando el ID de la lista como clave
                break;

            // Caso: ocultar el orden de envíos ('oce')
            case 'oce':
                
                $listaId = $_GET['idlt']; // Obtener el ID de la lista desde GET
                unset($_SESSION[$listaId]); // Eliminar la sesión asociada a esa lista para ocultar el orden
                break;

        }

    }

    // Función para verificar si ya existe una lista con el nombre especificado
    function existeLista($nombreLista) {
        
        $listas = getListasTareas(); // Obtener todas las listas de tareas

        // Recorrer cada lista
        foreach ($listas->getItems() as $lista) {
            
            if ($lista->getTitle() === $nombreLista) {
                return true; // Si el título de la lista coincide con el nombre buscado, retornar true
            }

        }
        
        return false; // Si no se encontró coincidencia, retornar false
    }
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configuración para dispositivos móviles -->

        <!-- Incluir Bootstrap desde CDN para estilos -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <!-- Incluir Font Awesome para íconos -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <!-- Incluir jQuery desde CDN -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <title>Repartos</title> <!-- Título de la página -->
        
        <script src="js/funciones.js"></script> <!-- Incluir archivo JavaScript con funciones adicionales -->

    </head>

    <body style="background:#00bfa5;">
        <h4 class="text-center mt-3">Gestión de Pedidos</h4> <!-- Encabezado principal de la página -->
        <div class="container mt-4" style="width:80rem;"> <!-- Contenedor principal con ancho definido -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" onsubmit="return validarFecha();"> <!-- Formulario para crear una nueva lista de reparto -->
                <div class="row">
                    <div class="col-md-3 mb-2"> <!-- Botón para enviar el formulario y crear la lista -->
                        <button type="submit" class="btn btn-info"> <i class="fas fa-plus mr-1"></i>Nueva Lista de Reparto </button>
                    </div>
                    <input type="hidden" name="action" value="nlt"> <!-- Campo oculto para indicar la acción 'nlt' -->
                    <div class="col-md-4"> <!-- Campo para seleccionar la fecha, requerido -->
                        <input type="date" class="form-control" id="title" name="title" required>
                    </div>
                </div>
            </form>

            <?php
            
                $listas = getListasTareas(); // Obtener todas las listas de tareas
                
                // Recorrer cada lista obtenida
                foreach ($listas->getItems() as $lista) {

                    // Omitir la lista predeterminada "My Tasks" o "Mis tareas"
                    if ($lista->getTitle() === "My Tasks" || $lista->getTitle() === "Mis tareas") {
                        continue;
                    }

                    // Iniciar la tabla para mostrar la lista de tareas
                    echo "<table class='table mt-2' id='t_{$lista->getId()}'>\n";
                    echo "<thead class='bg-secondary'>\n";
                    echo "<tr>\n";              
                    echo "<th scope='col' style='width:42rem;'>{$lista->getTitle()}</th>\n"; // Mostrar el título de la lista en una columna ancha     
                    echo "<th scope='col' class='text-right'>\n"; // Columna para los botones de acción, alineada a la derecha                
                    echo "<a href='envio.php?id={$lista->getId()}' class='btn btn-info mr-2 btn-sm'><i class='fas fa-plus mr-1'></i>Nuevo</a>\n"; // Botón para añadir un nuevo envío (tarea) a la lista                
                    echo "<button class='btn btn-success mr-2 btn-sm' onclick=\"ordenarEnvios('{$lista->getId()}')\"><i class='fas fa-sort mr-1'></i>ordenar</button>\n"; // Botón para ordenar los envíos usando la función AJAX 'ordenarEnvios'                
                    echo "<a href='repartos.php?action=oce&idlt={$lista->getId()}' class='btn btn-primary mr-2 btn-sm'><i class='fas fa-eye-slash mr-1'></i>Ocultar orden</a>\n"; // Botón para ocultar el orden de envíos                
                    echo "<a href='repartos.php?action=blt&idlt={$lista->getId()}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Borrar Lista?')\"><i class='fas fa-trash mr-1'></i>Borrar</a>\n"; // Botón para borrar la lista de tareas, con confirmación
                    echo "</th></tr>\n";
                    echo "</thead>\n";
                    echo "<tbody style='font-size:0.8rem'>\n";

                    $tareas = getTareas($lista->getId()); // Obtener las tareas de la lista actual

                    // Recorrer cada tarea en la lista
                    foreach ($tareas->getItems() as $tarea) {
                        
                        $coordenadas = explode(",", $tarea->getNotes()); // Separar las notas (coordenadas) usando la coma como delimitador
                        
                        $lat = $coordenadas[0]; // Asignar la latitud (primer valor)
                        $lon = isset($coordenadas[1]) ? $coordenadas[1] : ""; // Asignar la longitud (segundo valor) si existe

                        echo "<tr>\n";
                        echo "<th scope='row'>{$tarea->getTitle()} ({$tarea->getNotes()})"; // Mostrar el título de la tarea y sus notas (coordenadas)
                        echo "<input type='hidden' value='{$tarea->getNotes()}'>"; // Campo oculto para almacenar las notas de la tarea
                        echo "</th>\n";
                        echo "<th scope='row' class='text-right'>\n"; // Columna para botones de acción sobre la tarea, alineada a la derecha
                        echo "<a href='repartos.php?action=bt&idlt={$lista->getId()}&idt={$tarea->getId()}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Borrar Tarea?')\">"; // Botón para borrar la tarea, con confirmación
                        echo "<i class='fas fa-trash mr-1'></i>Borrar</a>\n";
                        echo "<a href='mapas.php?lat={$lat}&lon={$lon}' class='btn btn-info ml-2 btn-sm'><i class='fas fa-map mr-1'></i>Mapa</a>\n"; // Botón para ver la ubicación de la tarea en un mapa, pasando latitud y longitud por GET
                        echo "</th>\n";
                        echo "</tr>\n";

                    }

                    echo "</tbody>\n";
                    echo "</table>\n";

                    // Si existe un orden de envíos almacenado en la sesión para esta lista, mostrarlo
                    if (isset($_SESSION[$lista->getId()])) {

                        // Formulario para enviar el orden de envíos y visualizar la ruta en el mapa
                        echo "<form name='ruta' action='rutas.php' method='POST'>";
                        // Incluir las coordenadas de almacenamiento al inicio del array de puntos
                        echo "<input type='hidden' name='wp[]' value='{$coordenadasAlmacen}'>";
                        echo "<div class='container mt-2 mb-2' style='font-size:0.8rem'>";
                        echo "<ul class='list-group'>";

                        // Recorrer cada envío en el orden almacenado
                        foreach ($_SESSION[$lista->getId()] as $indice => $tituloEnvio) {
                            
                            echo "<li class='list-group-item list-group-item-info'>" . ($indice + 1) . ".- " . $tituloEnvio . "</li>\n";// Mostrar cada envío con su posición en la lista
                            echo "<input type='hidden' name='wp[]' value='" . getNotes($lista->getId(), $tituloEnvio) . "'>\n"; // Incluir un campo oculto con las notas (coordenadas) correspondientes a cada envío

                        }

                        // Incluir nuevamente las coordenadas de almacenamiento al final del array
                        echo "<input type='hidden' name='wp[]' value='{$coordenadasAlmacen}'>";
                        echo "<p class='text-center mt-2'>\n";
                        // Botón para enviar el formulario y ver la ruta en el mapa
                        echo "<button type='submit' class='btn btn-info btn-sm'><i class='fas fa-route mr-1'></i>Ver Ruta en Mapa</button>\n";
                        echo "</p>";
                        echo "</div>";
                        echo "</form>";

                    }

                }

            ?>

        </div>

        <?php
            
            echo $jaxon->getCss(); // Incluir los estilos CSS generados por Jaxon
            echo $jaxon->getJs(); // Incluir los scripts JavaScript generados por Jaxon
            echo $jaxon->getScript(); // Incluir el código de inicialización de Jaxon
        ?>

    </body>

</html>