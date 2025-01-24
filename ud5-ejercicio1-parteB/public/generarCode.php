<?php

    session_start();

    require '../vendor/autoload.php';

    use Tarea05\Jugadores;
    use Faker\Factory;

    $faker = Factory::create('es_Es');
    $jugador = new Jugadores();

    // Generar un código y comprobar que no existe

    while (true) {

        $code = $faker->ean13;

        if (!$jugador->existeBarcode($code)) {

            $jugador = null;
            break;

        }

    }

    $_SESSION['codigo'] = $code;
    header('Location: fcrear.php');