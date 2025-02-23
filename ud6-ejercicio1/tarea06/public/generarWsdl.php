<?php
require '../vendor/autoload.php';

use PHP2WSDL\PHPClass2WSDL;

$host = "tarea06.localhost";
$urlrelativo = "/servidorSoap";
$uri = "http://" . $host . $urlrelativo;

// URL del futuro servicio con WSDL (servicioW.php)
$urlServicioWSDL = $uri . "/servicioW.php"; 

// Generar WSDL para la clase Operaciones
$class = "Clases\\Operaciones";
$wsdlGenerator = new PHPClass2WSDL($class, $urlServicioWSDL);
$wsdlGenerator->generateWSDL(true);

// Guardar el WSDL en la carpeta servidorSoap
$fichero = $wsdlGenerator->save('../servidorSoap/servicio.wsdl');

if ($fichero) {
    echo "WSDL generado correctamente en: servidorSoap/servicio.wsdl";
} else {
    echo "Error al generar el WSDL";
}