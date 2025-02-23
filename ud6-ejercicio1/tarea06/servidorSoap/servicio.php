<?php
require '../vendor/autoload.php';

// Configuración correcta de la URI (sin referencia a WSDL)
$host = "tarea06.localhost";
$urlrelativo = "/servidorSoap";
$uri = "http://" . $host . $urlrelativo;

// Parámetros para el servidor SOAP sin WSDL
$parametros = ['uri' => $uri];

try {
    $server = new SoapServer(NULL, $parametros);
    $server->setClass('Clases\Operaciones');
    $server->handle();
} catch (SoapFault $f) {
    die("Error en el servidor: " . $f->getMessage());
}