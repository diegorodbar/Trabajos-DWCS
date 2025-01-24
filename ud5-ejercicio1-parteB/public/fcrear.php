<?php

    // Habilitar uso de sesiones

    session_start();

    require '../vendor/autoload.php';

    // Utilizar Blade para generar la interfaz gráfica de presentación para el usuario

    use Philo\Blade\Blade;

    $views = '../views';
    $cache = '../cache';

    $blade = new Blade($views, $cache);

    $titulo = 'Nuevo';
    $encabezado = 'Crear jugador';

    if (isset($_SESSION['error'])) {

        $error = $_SESSION['error'];

        echo $blade
            ->view()
            ->make('vcrear', compact('titulo', 'encabezado', 'error'));
            render();

        unset($_SESSION['error']);

    } else if (isset($_SESSION['codigo'])){

        $code = $_SESSION['codigo'];
        unset($_SESSION['codigo']);

        echo $blade
            ->view()
            ->make('vcrear', compact('titulo', 'encabezado', 'code'))
            ->render();

    } else {

        echo $blade
        ->view()
        ->make('vcrear', compact('titulo', 'encabezado'))
        ->render();

    }