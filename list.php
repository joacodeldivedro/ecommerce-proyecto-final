<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Tienda - Ecommerce</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Tienda Online</h1>
        <nav>
            <span>Hola, <?php echo isset($_SESSION['user_name'])?htmlspecialchars($_SESSION['user_name']):'Visitante'; ?></span>
            <a href="index.php?action=products">Productos</a>
            <a href="index.php?action=cart">
                Carrito 
                <?php 
                $database = new Database();
                $db = $database->getConnection();
                $cartController = new CartController($db);
                $cart_count = $cartController->getCartCount();
                if($cart_count > 0) echo "($cart_count)";
                ?>
            </a>
            <?php if($_SESSION['is_admin']): ?>
                <a href="index.php?action=admin_categories">Admin</a>
            <?php endif; ?>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </nav>
    </header>
    <div class="container">
        <h2>Productos Disponibles</h2>
        
        <div class="visitor-counter">
            <strong>Total de visitas a productos:</strong> 
            <?php 
            $database = new Database();
            $db = $database->getConnection();
            $counter = new PageCounter($db);
            echo $counter->getPageViews('products'); 
            ?>
        </div>
        
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        
        <div class="products-grid">
            <?php while($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="product-card">
                <?php if(!empty($product['image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                <?php else: ?>
                    <div class="no-image">Sin imagen</div>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="category">Categoría: <?php echo htmlspecialchars($product['category_name']); ?></p>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <a href="index.php?action=add_to_cart&id=<?php echo $product['id']; ?>" class="btn">Agregar al Carrito</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>