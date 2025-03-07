<?php

    // Verifica la presencia del parámetro 'id' en la URL. Redirecciona a 'repartos.php' si no existe.
    if (!isset($_GET['id'])) {
        header('Location: repartos.php');
        exit;
    }

    // Carga de componentes principales
    require_once __DIR__ . '/../src/Tools.php';        // Utilidades y lógica de geolocalización
    require_once __DIR__ . '/../src/Producto.php';     // Modelo de productos (DB)
    require_once __DIR__ . '/../vendor/autoload.php';  // Autoload de Composer

    use Jaxon\Jaxon;
    use function Jaxon\jaxon;

    // Obtener listado de productos desde la base de datos
    $producto = new Producto();
    $stmt = $producto->listadoProductos(); // Método que ejecuta: SELECT * FROM productos

    $idLista = $_GET['id']; // Almacenar el ID de la lista de tareas desde la URL

    // Configuración de Jaxon (AJAX)
    // Biblioteca para comunicación asíncrona cliente-servidor
    $jaxon = jaxon();
    $jaxon->setOption('js.app.minify', false);      // JS legible en desarrollo
    $jaxon->setOption('core.decode_utf8', true);    // Soporte para caracteres UTF-8
    $jaxon->setOption('core.debug.on', false);      // Desactiva modo depuración
    $jaxon->setOption('core.debug.verbose', false); // Mensajes detallados desactivados

    // Procesa posibles solicitudes AJAX (implementadas en Tools.php)
    if ($jaxon->canProcessRequest()) {
        $jaxon->processRequest();
    }

?>

<!DOCTYPE html>
<html lang="es">
        
    <head>

        <!-- CONFIGURACIÓN HEAD -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- DEPENDENCIAS EXTERNAS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
            integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" 
            integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        
        <!-- RECURSOS LOCALES -->
        <title>Apartado 4-3</title>
        <script type="text/javascript" src="js/funciones.js"></script> <!-- Incluir archivo JavaScript con funciones adicionales -->

    </head>

    <body style="background:#00bfa5;">

        <!-- ESTRUCTURA PRINCIPAL -->
        <div class="container mt-3">
            <div class="d-flex justify-content-center h-100">
                <div class="card" style="width:34rem;">
                    <!-- CABECERA DEL FORMULARIO -->
                    <div class="card-header">
                        <h3><i class="fas fa-cart-plus mr-2"></i>Crear Envio</h3>
                    </div>
                    
                    <!-- CUERPO DEL FORMULARIO -->
                    <div class="card-body">
                        <form name="f1" method="POST" action="repartos.php" onsubmit="return semaforo();">
                            <!-- SECCIÓN DIRECCIÓN -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:2.3rem;">
                                        <i class="fas fa-city"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Dirección" id="dir" name="dir" required>
                            </div>
                            
                            <!-- BOTÓN DE GEOLOCALIZACIÓN (Llama a getCoordenadas() en funciones.js) -->
                            <div class="form-group mt-1">
                                <button type="button" class="btn btn-info mr-2" id="vDireccion" onclick="getCoordenadas();">
                                    Ver Coordenadas
                                </button>
                            </div>
                            
                            <!-- CAMPOS DE COORDENADAS (Autocompletados vía Jaxon/Tools.php) -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:2.3rem;">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Latitud" id="lat" name="lat" required readonly>
                            </div>
                            
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:2.3rem;">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Longitud" id="lon" name="lon" required readonly>
                            </div>
                            
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:2.3rem;">
                                        <i class="fa fa-info"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Altitud" id="alt" name="alt" readonly>
                            </div>
                            
                            <!-- SELECTOR DE PRODUCTOS (Datos desde BD) -->
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:2.3rem;">
                                        <i class="fas fa-box-open"></i>
                                    </span>
                                </div>
                                <select id="pro" name="pro" class="form-control">
                                    <?php
                                    echo "<option value='-1'>Elige un producto</option>";
                                    // Genera opciones desde la consulta SQL ejecutada en Producto.php
                                    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                                        echo "<option>" . htmlspecialchars($row->nombre) . "</option>"; // Sanitización contra XSS con htmlspecialchars()
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <!-- CONTROLES FINALES -->
                            <div class="form-group">
                                <input type="hidden" name="idLTarea" value="<?php echo $idLista; ?>">
                                <button type="submit" class="btn btn-info mr-2" id="enviar" onclick="return semaforo();">
                                    Crear Envio
                                </button>
                                <a href="repartos.php" class="btn btn-success">Volver</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- INYECCIÓN DE RECURSOS JAXON -->
        <?php
            // Genera los recursos necesarios para el funcionamiento de Jaxon:
            echo $jaxon->getCss();  // Estilos CSS (si los tuviera)
            echo $jaxon->getJs();   // Biblioteca principal
            echo $jaxon->getScript(); // Scripts generados para las funciones registradas
        ?>
        
    </body>

</html>