<?php
require_once 'conexion.php';

try {
    // Obtener todos los productos
    $stmt = $pdo->query("SELECT p.id, p.nombre, f.nombre AS familia FROM productos p JOIN familias f ON p.familia_id = f.id");
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Listado de Productos</h2>
    <a href="crear.php" class="btn btn-primary mb-3">Crear Nuevo Producto</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Familia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?php echo $producto['id']; ?></td>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['familia']; ?></td>
                <td>
                    <a href="detalle.php?id=<?php echo $producto['id']; ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                    <a href="actualizar.php?id=<?php echo $producto['id']; ?>" class="btn btn-warning btn-sm">Actualizar</a>
                    <form action="borrar.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>