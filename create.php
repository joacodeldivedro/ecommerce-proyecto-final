<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="/ecommerce/assets/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Crear Nuevo Producto</h1>
            <nav>
                <a href="index.php?action=admin_products">Volver a Productos</a>
                <a href="index.php?action=admin_categories">Categorías</a>
                <a href="index.php?action=logout">Cerrar Sesión</a>
            </nav>
        </header>

        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=create_product" enctype="multipart/form-data" class="admin-form">
            <div class="form-group">
                <label for="name">Nombre del Producto:</label>
                <input type="text" id="name" name="name" required class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" class="form-control" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required class="form-control">
            </div>

            <div class="form-group">
                <label for="category_id">Categoría:</label>
                <select id="category_id" name="category_id" required class="form-control">
                    <option value="">Seleccionar Categoría</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Imagen:</label>
                <input type="file" id="image" name="image" accept="image/*" class="form-control">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Crear Producto</button>
                <a href="index.php?action=admin_products" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>