<?php

    // Habilitar uso de sesiones

    session_start();

    require '../vendor/autoload.php';

    use Tarea05\Jugadores;
    use Milon\Barcode\DNS1D;
    use Philo\Blade\Blade;

    // Utilizar Blade para generar la interfaz gráfica de presentación para el usuario

    $views = '../views';
    $cache = '../cache';

    $blade = new Blade($views, $cache);

    // Recuperar todos los jugadores de la base de datos y cargar la vista vjugadores pasándole el título y el encabezado como parámetros
    
    $d = new DNS1D();

    $titulo = 'Jugadores';
    $encabezado = 'Listado de jugadores';

    $jugadores = (new Jugadores())->recuperarJugadores();

    $d->setStorPath($cache);

    if (isset($_SESSION['mensaje'])) {

        $mensaje = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);

        echo $blade
            ->view()
            ->make('vjugadores', compact('titulo', 'encabezado', 'jugadores', 'd', 'mensaje'))
            ->render();

    } else {

        echo $blade
            ->view()
            ->make('vjugadores', compact('titulo', 'encabezado', 'jugadores', 'd'))
            ->render();

    }