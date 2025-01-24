<?php

    session_start();

    require '../vendor/autoload.php';

    use Tarea05\Jugadores;

    function error($text) {

        $_SESSION['error'] = $text;
        header('Location: fcrear.php');
        die();

    }

    $nom = trim($_POST['nombre']);
    $ape = trim($_POST['apellidos']);
    $pos = $_POST['posicion'];
    $dorsal = (int)$_POST['dorsal'];
    $cod = trim($_POST['barcode']);

    if (strlen($nom) == 0 || strlen($ape) == 0) {

        error("Nombre y apellidos deben contener algún carácter válido");

    }

    $jugador = new Jugadores();

    if ($jugador->existeDorsal($dorsal)) {

        $jugador = null;
        error("Ese dorsal ya está elegido");

    }

    // Si no hay errores inserta los datos

    $jugador->setNombre(ucwords($nom));
    $jugador->setApellidos(ucwords($ape));
    $jugador->setPosicion(ucwords($pos));
    if ($dorsal != 0) $jugador->setDorsal($dorsal);
    $jugador->setBarcode($cod);

    $jugador->create();
    $jugador = null;

    $_SESSION['mensaje'] = "Jugador creado con éxito";
    header('Location: jugadores.php');
