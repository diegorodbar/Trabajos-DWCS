<?php

    session_start();

    require '../vendor/autoload.php';

    use Tarea05\Jugadores;
    use Faker\Factory;

    // Borrar los datos de todos los jugadores de la base de datos

    $jugador = (new Jugadores())->borrarTodo();
    
    $jugador = null;

    // Generar 12 jugadores con datos aleatorios utilizando Faker (datos en español)

    $faker = Factory::create('es_Es');

    $cantidad = 12;

    for ($i = 0; $i < $cantidad; $i++) {

        $jugador = new Jugadores();

        $jugador->setNombre($faker->firstName('male' | 'female'));
        $jugador->setApellidos($faker->lastName . " " . $faker->lastName);
        $jugador->setDorsal($faker->unique()->numberBetween(1,60));
        $jugador->setPosicion($faker->numberBetween(1,6));
        $jugador->setBarcode($faker->unique()->ean13);

        $jugador->create();

        $jugador = null;

    }

    $_SESSION['mensaje'] = 'Datos de ejemplo creados correctamente';

    header('Location: jugadores.php');