<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Carrito - Ecommerce</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Carrito de Compras</h1>
        <nav>
            <span>Hola, <?php echo $_SESSION['user_name']; ?></span>
            <a href="index.php?action=products">Seguir Comprando</a>
            <?php if($_SESSION['is_admin']): ?>
                <a href="index.php?action=admin_categories">Admin</a>
            <?php endif; ?>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </nav>
    </header>
    <div class="container">
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        <?php if(isset($message)): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if(empty($cart_items) || !is_array($cart_items) || count($cart_items) == 0): ?>
            <div class="empty-cart">
                <p>El carrito está vacío</p>
                <a href="index.php?action=products" class="btn">Ir a Comprar</a>
            </div>
        <?php else: ?>
            <table>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
                <?php 
                $total = 0;
                foreach($cart_items as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td>
                        <?php if(!empty($item['image'])): ?>
                            <img src="uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-image">
                        <?php else: ?>
                            <div class="no-image-small">Sin imagen</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo $item['name']; ?></strong>
                        <?php if(!empty($item['description'])): ?>
                            <br><small><?php echo substr($item['description'], 0, 50) . '...'; ?></small>
                        <?php endif; ?>
                    </td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form method="POST" action="index.php?action=update_quantity" style="display: inline;">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="10" style="width: 60px;">
                            <button type="submit" class="btn btn-small">Actualizar</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <a href="index.php?action=remove_from_cart&id=<?php echo $item['id']; ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="4"><strong>Total General</strong></td>
                    <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </table>
            <div class="cart-actions">
                <a href="index.php?action=checkout" class="btn btn-success">Finalizar Compra</a>
                <a href="index.php?action=products" class="btn">Seguir Comprando</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>