<?php

    session_start(); // Inicia la sesión para gestionar el login del usuario

    require __DIR__ . '/vendor/autoload.php';
    require 'conexion.php';

    // Mostrar errores en el navegador
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Importar Jaxon
    use Jaxon\Jaxon;
    use function Jaxon\jaxon;

    $jaxon = jaxon();

    // Registrar función para validar login
    $jaxon->register(Jaxon::CALLABLE_FUNCTION, 'validarLogin');

    function validarLogin($email, $password) {
        global $conexion;
        $response = jaxon()->newResponse();

        // Validar credenciales
        $sql = "SELECT * FROM usuarios WHERE email = ? AND password = MD5(?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            $_SESSION['usuario_id'] = $usuario['id']; // Guardar ID del usuario en sesión
            $_SESSION['usuario_email'] = $usuario['email'];
            $response->assign('mensaje', 'innerHTML', 'Login exitoso');
            $response->script('window.location.href = "productos.php";');
        } else {
            $response->assign('mensaje', 'innerHTML', 'Email o contraseña incorrectos');
        }
        return $response;
    }

    if ($jaxon->canProcessRequest()) {
        $jaxon->processRequest();
        exit;
    }

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <?php echo $jaxon->getCss(); ?>
        <?php echo $jaxon->getJs(); ?>
        <?php echo $jaxon->getScript(); ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <h1>Login</h1>
        <form id="loginForm">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="button" onclick="jaxon_validarLogin($('#email').val(), $('#password').val())">Login</button>
        </form>
        <div id="mensaje"></div>
    </body>
</html>