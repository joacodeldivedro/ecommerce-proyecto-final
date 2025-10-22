<?php
class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login() {
        $error = "";
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            require_once 'models/user.php';
            $user = new User($this->db);
            $user->email = $email;
            $user->password = $password;
            
            if($user->login()) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['is_admin'] = (!empty($user->is_admin) && ($user->is_admin==1 || $user->is_admin==='1' || $user->is_admin===true)) ? true : false;
                
                if($_SESSION['is_admin']) {
                    header('Location: index.php?action=admin_categories');
                } else {
                    header('Location: index.php?action=products');
                }
                exit;
            } else {
                $error = 'Credenciales incorrectas';
            }
        }
        
        require 'views/auth/login.php';
    }

    public function profile() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        
        require_once 'models/user.php';
        $userModel = new User($this->db);
        $user_data = $userModel->getUserById($_SESSION['user_id']);
        
        if($_POST && isset($_POST['update_shipping'])) {
            $direccion = $_POST['direccion'] ?? '';
            $ciudad = $_POST['ciudad'] ?? '';
            $codigo_postal = $_POST['codigo_postal'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            
            if($userModel->updateShippingInfo($_SESSION['user_id'], $direccion, $ciudad, $codigo_postal, $telefono)) {
                $_SESSION['success_message'] = "Información de envío actualizada correctamente";
                $user_data = $userModel->getUserById($_SESSION['user_id']);
            } else {
                $_SESSION['error_message'] = "Error al actualizar la información";
            }
        }
        
        if($_POST && isset($_POST['change_password'])) {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if(!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
                if($new_password === $confirm_password) {
                    if($userModel->changePassword($_SESSION['user_id'], $current_password, $new_password)) {
                        $_SESSION['success_message'] = "Contraseña cambiada correctamente";
                    } else {
                        $_SESSION['error_message'] = "Contraseña actual incorrecta";
                    }
                } else {
                    $_SESSION['error_message'] = "Las contraseñas no coinciden";
                }
            } else {
                $_SESSION['error_message'] = "Todos los campos son obligatorios";
            }
        }
        
        include 'views/user/profile.php';
    }

    public function register() {
        require 'views/auth/register.php';
    }

    public function recover() {
        require 'views/auth/recover.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
?>