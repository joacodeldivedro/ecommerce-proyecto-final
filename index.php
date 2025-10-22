<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
echo "<!-- DEBUG: Action = $action -->";
echo "<!-- DEBUG: POST recibido = " . (isset($_POST) ? 'SÍ' : 'NO') . " -->";
require_once 'config/database.php';
require_once 'models/user.php';
require_once 'models/product.php';
require_once 'models/category.php';
require_once 'models/cart.php';
require_once 'controllers/authController.php';
require_once 'controllers/productController.php';
require_once 'controllers/cartController.php';
require_once 'controllers/adminController.php';
$database = new Database();
$db = $database->getConnection();

$protected_actions = [
    'products', 'cart', 'add_to_cart', 'remove_from_cart', 'update_quantity', 'checkout',
    'admin_categories', 'create_category', 'edit_category', 'delete_category',
    'admin_products', 'create_product', 'edit_product', 'delete_product', 'profile'
];

if(in_array($action, $protected_actions) && !isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit;
}

switch($action) {
    case 'login':
        $controller = new AuthController($db);
        $controller->login();
        break;
    
    case 'register':
        $controller = new AuthController($db);
        $controller->register();
        break;
    
    case 'recover':
        $controller = new AuthController($db);
        $controller->recover();
        break;
    
    case 'products':
        $controller = new ProductController($db);
        $controller->index();
        break;
    
    case 'admin_categories':
        $controller = new AdminController($db);
        $controller->categories();
        break;

    case 'create_category':
        $controller = new AdminController($db);
        $controller->createCategory();
        break;

    case 'edit_category':
        $controller = new AdminController($db);
        $controller->editCategory();
        break;

    case 'delete_category':
        $controller = new AdminController($db);
        $controller->deleteCategory();
        break;

    case 'admin_products':
        $controller = new AdminController($db);
        $controller->products();
        break;

    case 'create_product':
        $controller = new AdminController($db);
        $controller->createProduct();
        break;

    case 'edit_product':
        $controller = new AdminController($db);
        $controller->editProduct();
        break;

    case 'delete_product':
        $controller = new AdminController($db);
        $controller->deleteProduct();
        break;
    
    case 'cart':
        $controller = new CartController($db);
        $controller->index();
        break;

    case 'add_to_cart':
        $controller = new CartController($db);
        $controller->add();
        break;

    case 'remove_from_cart':
        $controller = new CartController($db);
        $controller->remove();
        break;

    case 'update_quantity':
        $controller = new CartController($db);
        $controller->updateQuantity();
        break;

    case 'checkout':
        $controller = new CartController($db);
        $controller->checkout();
        break;
    
    case 'profile':
        $controller = new AuthController($db);
        $controller->profile();
        break;
    
    case 'logout':
        $controller = new AuthController($db);
        $controller->logout();
        break;
    
    default:
        header("Location: index.php?action=login");
        break;
}
?>