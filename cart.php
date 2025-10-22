<?php
class cart {
    private $conn;
    private $table_cart = "carts";
    private $table_cart_items = "cart_items";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getCart($user_id) {
        // Primero verificar si el usuario ya tiene un carrito
        $query = "SELECT id FROM " . $this->table_cart . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si no existe, crear uno
        if (!$cart) {
            $query = "INSERT INTO " . $this->table_cart . " (user_id) VALUES (?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$user_id]);
            return $this->conn->lastInsertId();
        }
        
        return $cart['id'];
    }
    
public function getCartItems($cart_id) {
    $query = "SELECT ci.*, p.name, p.price, p.image, p.description 
             FROM " . $this->table_cart_items . " ci 
             LEFT JOIN products p ON ci.product_id = p.id 
             WHERE ci.cart_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$cart_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Cambia esto
}
    
    public function getCartItemsCount($user_id) {
        $cart_id = $this->getCart($user_id);
        
        $query = "SELECT SUM(quantity) as total_count 
                 FROM " . $this->table_cart_items . " 
                 WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cart_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total_count'] ? $result['total_count'] : 0;
    }
    
    public function addToCart($cart_id, $product_id, $quantity = 1) {
        // Verificar si el producto ya está en el carrito
        $query = "SELECT id, quantity FROM " . $this->table_cart_items . " 
                 WHERE cart_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cart_id, $product_id]);
        $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_item) {
            // Actualizar cantidad
            $new_quantity = $existing_item['quantity'] + $quantity;
            $query = "UPDATE " . $this->table_cart_items . " 
                     SET quantity = ? 
                     WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$new_quantity, $existing_item['id']]);
        } else {
            // Agregar nuevo item
            $query = "INSERT INTO " . $this->table_cart_items . " (cart_id, product_id, quantity) 
                     VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$cart_id, $product_id, $quantity]);
        }
    }
    
    public function updateQuantity($item_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($item_id);
        }
        
        $query = "UPDATE " . $this->table_cart_items . " 
                 SET quantity = ? 
                 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$quantity, $item_id]);
    }
    
    public function removeItem($item_id) {
        $query = "DELETE FROM " . $this->table_cart_items . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$item_id]);
    }
    
    public function clearCart($cart_id) {
        $query = "DELETE FROM " . $this->table_cart_items . " WHERE cart_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$cart_id]);
    }
    
    // AGREGAR ESTE MÉTODO NUEVO
    public function getCartTotal($cart_id) {
        $query = "SELECT SUM(ci.quantity * p.price) as total 
                 FROM " . $this->table_cart_items . " ci 
                 LEFT JOIN products p ON ci.product_id = p.id 
                 WHERE ci.cart_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cart_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ? $result['total'] : 0;
    }
public function removeFromCart($user_id, $product_id) {
    $cart_id = $this->getCart($user_id);
    
    // DEBUG
    error_log("DEBUG removeFromCart - User ID: $user_id, Cart ID: $cart_id, Product ID: $product_id");
    
    $query = "DELETE FROM " . $this->table_cart_items . " 
             WHERE cart_id = ? AND product_id = ?";
    $stmt = $this->conn->prepare($query);
    $result = $stmt->execute([$cart_id, $product_id]);
    
    // DEBUG
    error_log("DEBUG removeFromCart - Rows affected: " . $stmt->rowCount());
    error_log("DEBUG removeFromCart - Result: " . ($result ? 'true' : 'false'));
    
    return $result;
}
public function updateItemQuantity($item_id, $quantity) {
    if ($quantity <= 0) {
        return $this->removeItem($item_id);
    }
    
    $query = "UPDATE " . $this->table_cart_items . " 
             SET quantity = ? 
             WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$quantity, $item_id]);
}
}
?>