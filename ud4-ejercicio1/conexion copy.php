<?php
$host = 'localhost'; // host de la base de datos
$dbname = 'proyecto'; // nombre de la base de datos
$username = 'proyecto'; // usuario de la base de datos
$password = 'abc123.'; // contraseña del usuario de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configuración de PDO para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>