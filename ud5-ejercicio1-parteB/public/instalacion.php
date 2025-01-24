<?php

    // Habilitar uso de sesiones

    session_start();

    require '../vendor/autoload.php';

    // Utilizar Blade para generar la interfaz gráfica de presentación para el usuario

    use Philo\Blade\Blade;

    $views = '../views';
    $cache = '../cache';

    $blade = new Blade($views, $cache);

    // Cargar la vista vinstalación pasándole el título y el encabezado como parámetros

    $titulo = 'Install';
    $encabezado = 'Instalación';

    echo $blade 
        ->view()
        ->make('vinstalacion', compact('titulo', 'encabezado'))
        ->render();
