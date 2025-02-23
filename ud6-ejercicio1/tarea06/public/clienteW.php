<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "tarea06.localhost";
$urlrelativo = "/servidorSoap";
$uri = "http://" . $host . $urlrelativo;
$url = $uri . "/servicio.wsdl"; // URL del WSDL generado

try {
    $cliente = new SoapClient($url);
} catch (SoapFault $f) {
    die("Error en cliente SOAP: " . $f->getMessage());
}

$codP = 13;
$codT = 14;
$codF = 'CONSOL';

// Función getPvp
$pvp = $cliente->__soapCall('getPvp', ['id' => $codP]);
$precio = ($pvp == null) ? "No existe el Producto" : $pvp;
echo "Código de producto $codP: $precio<br>";

// Función getFamilias
echo "Códigos de Familias:";
$familias = $cliente->__soapCall('getFamilias', []);
echo "<ul>";
foreach ($familias as $v) {
    echo "<li>$v</li>";
}
echo "</ul>";

// Función getProductosFamilia (corregido 'cofF' a 'codF')
$productos = $cliente->__soapCall('getProductosFamilia', ['codF' => $codF]);
echo "Productos de la Familia $codF:";
echo "<ul>";
foreach ($productos as $v) {
    echo "<li>$v</li>";
}
echo "</ul>";

// Función getStock
$unidades = $cliente->__soapCall('getStock', ['codP' => $codP, 'codT' => $codT]);
echo "Unidades del producto $codP en la tienda $codT: $unidades";