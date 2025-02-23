<?php
require '../vendor/autoload.php';

use Clases\Clases1\ClasesOperacionesService;

$host = "tarea06.localhost";
$urlrelativo = "/servidorSoap";
$uri = "http://" . $host . $urlrelativo;
$url = $uri . "/servicio.wsdl"; // URL del WSDL

try {
    $cliente = new SoapClient($url);
} catch (SoapFault $f) {
    die("Error en cliente SOAP: " . $f->getMessage());
}

$codP = 13;
$codT = 1;
$codF = 'MP3';

// Instancia de la clase generada
$objeto = new ClasesOperacionesService();

// Función getPvp
$pvp = $objeto->getPvp($codP);
$precio = ($pvp == null) ? "No existe el Producto" : $pvp;
echo "Código de producto $codP: $precio<br>";

// Función getFamilias
echo "Códigos de Familias:";
$familias = $objeto->getFamilias();
echo "<ul>";
foreach ($familias as $v) {
    echo "<li>$v</li>";
}
echo "</ul>";

// Función getProductosFamilia
echo "Productos de la Familia $codF:";
$productos = $objeto->getProductosFamilia($codF);
echo "<ul>";
foreach ($productos as $v) {
    echo "<li>$v</li>";
}
echo "</ul>";

// Función getStock
$unidades = $objeto->getStock($codP, $codT);
echo "Unidades del producto $codP en la tienda $codT: $unidades";