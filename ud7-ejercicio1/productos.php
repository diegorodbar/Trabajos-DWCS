<?php

session_start(); // Inicia la sesión para verificar si el usuario está autenticado

require __DIR__ . '/vendor/autoload.php';
require 'conexion.php';

// Si el usuario no está autenticado, redirigir al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

use Jaxon\Jaxon;
use function Jaxon\jaxon;

$jaxon = jaxon();

// Registrar funciones PHP que serán llamadas desde JavaScript
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'miVoto');
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'pintarEstrellas'); // Puedes eliminarla si no se usa

function miVoto($idUsuario, $idProducto, $voto) {
    global $conexion;
    $response = jaxon()->newResponse();

    // Verificar si el usuario ya ha votado por este producto
    $sql = "SELECT * FROM votos WHERE idUs = ? AND idPr = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $idUsuario, $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response->assign('mensaje', 'innerHTML', 'Ya has votado por este producto');
        $response->script('alert("Ya has votado por este producto");');
        return $response;
    }

    // Insertar el voto
    $sql = "INSERT INTO votos (idUs, idPr, cantidad) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iii", $idUsuario, $idProducto, $voto);
    $stmt->execute();

    // Calcular la nueva valoración media y contar el número de votos
    $sql = "SELECT AVG(cantidad) as media, COUNT(*) as votos FROM votos WHERE idPr = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $media = $row['media'];
    $votos = $row['votos'];

    // Construir el HTML con el número de valoraciones y los íconos de estrellas
    $estrellas = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $media) {
            $estrellas .= '<i class="fas fa-star"></i>';
        } elseif ($i - 0.5 <= $media) {
            $estrellas .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $estrellas .= '<i class="far fa-star"></i>';
        }
    }

    $valoracion_text = $votos . " valoraciones: " . $estrellas;
    $response->assign('valoracion_' . $idProducto, 'innerHTML', $valoracion_text);

    return $response;
}

function pintarEstrellas($idProducto, $media) {
    // Función auxiliar
    $response = jaxon()->newResponse();
    $estrellas = '';

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $media) {
            $estrellas .= '<i class="fas fa-star"></i>';
        } elseif ($i - 0.5 <= $media) {
            $estrellas .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $estrellas .= '<i class="far fa-star"></i>';
        }
    }

    $response->assign('valoracion_' . $idProducto, 'innerHTML', $estrellas);
    return $response;
}

// Procesar la solicitud Xajax solo si es una petición AJAX
if ($jaxon->canProcessRequest()) {
    $jaxon->processRequest();
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <?php echo $jaxon->getCss(); ?> <!-- Incluye los estilos de Xajax (si los hay) -->
    <?php echo $jaxon->getJs(); ?>  <!-- Incluye los scripts de Xajax -->
    <?php echo $jaxon->getScript(); ?>  <!-- Incluye el script de inicialización de Xajax -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Productos</h1>
    <form action="logout.php" method="POST">
        <button type="submit">Cerrar sesión</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Valoración</th>
                <th>Valorar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtener la lista de productos
            $sql = "SELECT * FROM productos";
            $result = $conexion->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['nombre'] . '</td>';
                echo '<td>' . $row['descripcion'] . '</td>';
                echo '<td>$' . $row['precio'] . '</td>';
                // Inicialmente, sin votos se muestra "0 valoraciones"
                echo '<td id="valoracion_' . $row['id'] . '">0 valoraciones</td>';
                echo '<td>';
                echo '<select id="voto_' . $row['id'] . '">';
                for ($i = 1; $i <= 5; $i++) {
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
                echo '</select> ';
                echo '<button onclick="jaxon_miVoto(1, ' . $row['id'] . ', document.getElementById(\'voto_' . $row['id'] . '\').value)">Votar</button>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <div id="mensaje"></div>
</body>
</html>