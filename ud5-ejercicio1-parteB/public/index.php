<?php

    require '../vendor/autoload.php';

    use Tarea05\Jugadores;

    // Nueva conexión a la base de datos

    $jugador = new Jugadores();

    // Si hay jugadores redirige a la lista de jugadores y si no a la página de instalación

    if ($jugador->tieneDatos()) {

        $jugador = null;
        header('Location: jugadores.php');

    } else {

        $jugador = null;
        header('Location: instalacion.php');
        
    }