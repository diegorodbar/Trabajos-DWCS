<?php

    $servidor = "localhost";
    $usuario = "gestor";
    $password = "abc123.";
    $basedatos = "tarea07";

    $conexion = new mysqli($servidor, $usuario, $password, $basedatos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $conexion->set_charset("utf8");

?>