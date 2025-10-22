<?php
class CartController {
    private $productModel;
    private $cartModel;
    public function __construct($db) {
        $this->productModel = new Product($db);
        $this->cartModel = new Cart($db);
    }
public function index() {
    
    if(!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit;
    }
    
    $cart_id = $this->cartModel->getCart($_SESSION['user_id']);
    $cart_items = $this->cartModel->getCartItems($cart_id);
    $cart_total = $this->cartModel->getCartTotal($cart_id);
    
    include 'views/cart/view.php';
}
    public function add() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        if(isset($_GET['id'])) {
            $product_id = $_GET['id'];
            $product = $this->productModel->readById($product_id);
            
            if($product) {
                if($this->cartModel->addToCart($_SESSION['user_id'], $product_id)) {
                    $_SESSION['success_message'] = "Producto agregado al carrito";
                } else {
                    $_SESSION['error_message'] = "Error al agregar producto al carrito";
                }
            }
        }
        header("Location: index.php?action=products");
        exit;
    }
public function remove() {
    if(!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit;
    }
    if(isset($_GET['id'])) {
        $item_id = $_GET['id'];  // Cambia el nombre
        if($this->cartModel->removeItem($item_id)) {  // Usa removeItem en lugar de removeFromCart
            $_SESSION['success_message'] = "Producto eliminado del carrito";
        } else {
            $_SESSION['error_message'] = "Error al eliminar producto del carrito";
        }
    }
    header("Location: index.php?action=cart");
    exit;
}
public function updateQuantity() {
    if(!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit;
    }
    
    // CAMBIA product_id por item_id
    if(isset($_POST['item_id']) && isset($_POST['quantity'])) {
        $item_id = $_POST['item_id'];
        $quantity = intval($_POST['quantity']);
        
        if($this->cartModel->updateItemQuantity($item_id, $quantity)) {
            $_SESSION['success_message'] = "Cantidad actualizada";
        } else {
            $_SESSION['error_message'] = "Error al actualizar cantidad";
        }
    }
    header("Location: index.php?action=cart");
    exit;
}
    public function checkout() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        if($this->cartModel->clearCart($_SESSION['user_id'])) {
            $message = "¡Compra realizada con éxito! Gracias por tu compra.";
        } else {
            $message = "Error al procesar la compra";
        }
        
        $cart_items = $this->cartModel->getCart($_SESSION['user_id']);
        $cart_total = $this->cartModel->getCartTotal($_SESSION['user_id']);
        include 'views/cart/view.php';
    }
    public function getCartCount() {
        if(!isset($_SESSION['user_id'])) {
            return 0;
        }
        return $this->cartModel->getCartItemsCount($_SESSION['user_id']);
    }
}
?>