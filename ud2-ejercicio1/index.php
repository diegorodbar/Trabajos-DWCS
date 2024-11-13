<?php
// Definir agenda como un array, que se cargará con datos del formulario si existen
$agenda = [];
if (!empty($_POST['hidden'])) {
    $agenda = json_decode($_POST['hidden'], true);
}

// Variable para advertencia de nombre vacío
$advertencia = "";

// Proceso de datos del formulario
if (!empty($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    
    // Comprobar si el campo de nombre tiene contenido
    if ($nombre === "") {
        $advertencia = "Por favor, ingresa un nombre.";
    } else {
        // Gestionar contacto en función de si ya existe en la agenda
        if (!empty($telefono)) {
            $agenda[$nombre] = $telefono;  // Añadir o actualizar contacto
        } elseif (isset($agenda[$nombre])) {
            unset($agenda[$nombre]);       // Eliminar contacto si teléfono está vacío
        }
    }
}

// Verificar si se ha solicitado limpiar la agenda
if (isset($_GET['limpiar']) && $_GET['limpiar'] == '1') {
    $agenda = [];
    // Solución propuesta: 
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit; 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Contactos</title>
    <style>
        /* Estilos básicos */
        body { font-family: Arial, sans-serif; width: 50%; margin: auto; }
        h2 { color: #333; }
        form { margin: 20px 0; }
        label { display: block; margin-top: 10px; }
        input[type="text"] { width: 100%; padding: 8px; margin-top: 5px; }
        button { padding: 10px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        .warning { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>Agenda de Contactos</h2>

<!-- Mostrar advertencia si no se ingresó nombre -->
<?php if ($advertencia): ?>
    <p class="warning"><?php echo htmlspecialchars($advertencia); ?></p>
<?php endif; ?>

<!-- Mostrar contactos en la agenda (si existen) -->
<?php if (!empty($agenda)): ?>
    <ul>
        <?php foreach ($agenda as $nombre => $telefono): ?>
            <li><?php echo htmlspecialchars($nombre) . ": " . htmlspecialchars($telefono); ?></li>
        <?php endforeach; ?>
    </ul>
    <!-- Botón para limpiar la agenda -->
    <form method="get" action="">
        <button type="submit" name="limpiar" value="1">Vaciar Agenda</button>
    </form>
<?php else: ?>
    <p>La agenda está vacía.</p>
<?php endif; ?>

<!-- Formulario para agregar o actualizar contactos -->
<h3>Añadir o Editar Contacto</h3>
<form method="post" action="">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono">

    <!-- Campo hidden para almacenar los datos de la agenda -->
    <input type="hidden" name="hidden" value='<?php echo json_encode($agenda); ?>'>

    <button type="submit">Guardar</button>
</form>

</body>
</html>
