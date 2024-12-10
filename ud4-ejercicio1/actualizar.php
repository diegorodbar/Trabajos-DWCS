<?php
require_once 'conexion.php';

// Verificar si el ID está presente en la URL
if (!isset($_GET['id'])) {
    header('Location: listado.php');
    exit;
}

$id = $_GET['id'];

// Obtener el producto a actualizar
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

// Obtener las familias para el select
$stmt_familias = $pdo->query("SELECT cod, nombre FROM familias");
$familias = $stmt_familias->fetchAll();

// Si el formulario se envía, actualizar el producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = $_POST['nombre'];
        $nombre_corto = $_POST['nombre_corto'];
        $precio = $_POST['pvp'];
        $familia = $_POST['familia'];
        $descripcion = $_POST['descripcion'];

        // Actualizar el producto en la base de datos con los nuevos datos intorducidos
        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, nombre_corto = ?, pvp = ?, familia = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$nombre, $nombre_corto, $precio, $familia, $descripcion, $id]);

        header('Location: listado.php');
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar el producto: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Actualizar Producto</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="nombre_corto" class="form-label">Nombre Corto</label>
            <input type="text" class="form-control" id="nombre_corto" name="nombre_corto" value="<?php echo $producto['nombre_corto']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="pvp" value="<?php echo $producto['pvp']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="familia" class="form-label">Familia</label>
            <select class="form-control" id="familia" name="familia" required>
                <?php foreach ($familias as $familia): ?>
                    <option value="<?php echo $familia['cod']; ?>" <?php if ($familia['cod'] == $producto['familia']) echo 'selected'; ?>>
                        <?php echo $familia['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo $producto['descripcion']; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
    </form>
</div>
</body>
</html>