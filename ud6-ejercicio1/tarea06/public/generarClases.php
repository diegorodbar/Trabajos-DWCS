<?php
ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);


//Fichero para generar las clases
require '../vendor/autoload.php';

use Wsdl2PhpGenerator\Generator;
use Wsdl2PhpGenerator\Config;

$host = "tarea06.localhost";
$urlrelativo = "/servidorSoap";
$uri = "http://" . $host . $urlrelativo;
$url = $uri . "/servicio.wsdl";


$generator = new Generator();
$generator->generate(
    new Config([
        'inputFile' => $url,
        'outputDir' => '../src/Clases1',
        'namespaceName' => 'Clases\Clases1'
    ])
);