<?php
require_once 'conexion.php';

// Verificar si el ID está presente en la URL
if (!isset($_GET['id'])) {
    header('Location: listado.php');
    exit;
}

$id = $_GET['id'];

try {
    // Obtener los detalles del producto en la base de datos
    $stmt = $pdo->prepare("SELECT p.*, f.nombre AS familia FROM productos p JOIN familias f ON p.familia = f.cod WHERE p.id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    // Si no se encuentra el producto, redirigir al listado
    if (!$producto) {
        header('Location: listado.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error al obtener detalles: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Detalles del Producto</h2>
    <table class="table table-bordered">
        <tr>
            <th>Nombre</th>
            <td><?php echo $producto['nombre']; ?></td>
        </tr>
        <tr>
            <th>Nombre Corto</th>
            <td><?php echo $producto['nombre_corto']; ?></td>
        </tr>
        <tr>
            <th>Precio</th>
            <td><?php echo $producto['pvp']; ?></td>
        </tr>
        <tr>
            <th>Familia</th>
            <td><?php echo $producto['familia']; ?></td> <!-- Mostrar el nombre de la familia -->
        </tr>
        <tr>
            <th>Descripción</th>
            <td><?php echo $producto['descripcion']; ?></td>
        </tr>
    </table>
    <a href="listado.php" class="btn btn-secondary">Volver al listado</a>
</div>
</body>
</html>