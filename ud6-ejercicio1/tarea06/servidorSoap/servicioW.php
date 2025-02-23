<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Desactiva errores en pantalla (usa logs)
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php_errors.log');

require '../vendor/autoload.php';

$wsdlPath = __DIR__ . '/servicio.wsdl';

try {
    $server = new SoapServer($wsdlPath);
    $server->setClass('Clases\Operaciones');
    $server->handle();
} catch (Throwable $t) {
    error_log("Error en servicioW.php: " . $t->getMessage());
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}