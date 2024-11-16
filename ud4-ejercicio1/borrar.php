<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        try {
            // Eliminar el producto
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);

            // Redirigir al listado con un mensaje de éxito
            header('Location: listado.php?msg=Producto eliminado correctamente');
            exit;
        } catch (PDOException $e) {
            die("Error al borrar el producto: " . $e->getMessage());
        }
    }
} else {
    // Si no es una petición POST, redirigir al listado
    header('Location: listado.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Producto Eliminado</h2>
    <p>El producto ha sido eliminado correctamente.</p>
    <a href="listado.php" class="btn btn-secondary">Volver al listado</a>
</div>
</body>
</html>