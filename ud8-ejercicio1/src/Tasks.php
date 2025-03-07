<?php
    
    session_start(); // Iniciar la sesión para poder almacenar y recuperar variables de sesión

    require_once __DIR__ . '/../vendor/autoload.php'; // Cargar el autoload de Composer para incluir todas las dependencias necesarias
    
    include_once __DIR__ . '/claves.inc.php'; // Incluir el archivo que contiene las claves y credenciales necesarias (por ejemplo, las claves de Google)

    $redirectUri = 'http://localhost/tarea08/public/repartos.php'; // Definir la URL de redirección a la que se enviará al usuario después de la autenticación
 
    $client = new Google_Client(); // Crear una nueva instancia del cliente de Google para la autenticación y el acceso a servicios
    
    $client->setClientId($googleClientIdTarea8); // Establecer el Client ID proporcionado en las claves
    $client->setClientSecret($googleClientSecretTarea8); // Establecer el Client Secret proporcionado en las claves
    $client->setRedirectUri($redirectUri); // Definir la URL a la que Google redirigirá después de la autenticación
    $client->addScope('profile'); // Agregar el alcance "profile" para acceder a la información del perfil del usuario
    $client->addScope('email'); // Agregar el alcance "email" para acceder a la dirección de correo electrónico del usuario
    $client->setApplicationName('PruebaTasks'); // Establecer el nombre de la aplicación
    $client->setScopes(Google_Service_Tasks::TASKS); // Establecer el alcance necesario para acceder al servicio de Google Tasks
    $client->setPrompt('select_account consent'); // Configurar el comportamiento de la ventana de autenticación para que siempre solicite seleccionar una cuenta y otorgar consentimiento

    // Si se ha solicitado cerrar sesión (logout), eliminar el token almacenado en la sesión
    if (isset($_REQUEST['logout'])) {
        unset($_SESSION['token']);
    }

    // Si Google ha devuelto un código de autorización (después de la autenticación)
    if (isset($_GET['code'])) {
        
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']); // Intercambiar el código de autorización por un token de acceso
        $client->setAccessToken($token); // Establecer el token de acceso en el cliente
        $_SESSION['token'] = $token; // Guardar el token en la sesión para usos futuros
        header('Location: ' . filter_var($redirectUri, FILTER_SANITIZE_URL)); // Redirigir al usuario a la URL de redirección definida, sanitizando la URL para mayor seguridad
        exit;

    }

    // Si ya existe un token en la sesión
    if (!empty($_SESSION['token'])) {
        
        $client->setAccessToken($_SESSION['token']); // Establecer el token de acceso en el cliente con el token almacenado en la sesión

        // Verificar si el token ha expirado y, de ser así, eliminarlo de la sesión
        if ($client->isAccessTokenExpired()) {
            unset($_SESSION['token']);
        }

    } else {    
        // Si no hay token en la sesión, crear una URL de autenticación y redirigir al usuario a la URL de autenticación para iniciar el proceso de login
        $authUrl = $client->createAuthUrl();
        header("Location: $authUrl");
        exit;
    }

?>