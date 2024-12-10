<?php
require_once 'conexion.php';

// Obtener las familias para el select
$stmt = $pdo->query("SELECT cod, nombre FROM familias");
$familias = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $nombre_corto = $_POST['nombre_corto'];
        $precio = $_POST['precio'];
        $familia = $_POST['familia']; // familia_id de la tabla productos
        $descripcion = $_POST['descripcion'];

        // Insertar el nuevo producto en la base de datos
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, nombre_corto, pvp, familia, descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $nombre_corto, $precio, $familia, $descripcion]);

        // Redirigir al listado de productos después de guardar
        header('Location: listado.php');
        exit;
    } catch (PDOException $e) {
        die("Error al crear el producto: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Crear Nuevo Producto</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="nombre_corto" class="form-label">Nombre Corto</label>
            <input type="text" class="form-control" id="nombre_corto" name="nombre_corto" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
        </div>
        <div class="mb-3">
            <label for="familia" class="form-label">Familia</label>
            <select class="form-control" id="familia" name="familia" required>
                <?php foreach ($familias as $familia): ?>
                    <option value="<?php echo $familia['cod']; ?>"><?php echo $familia['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Producto</button>
    </form>
</div>
</body>
</html>