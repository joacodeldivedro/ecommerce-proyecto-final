<?php
// create_user.php - Crear usuario de prueba con contraseña conocida
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // create admin user with password Admin123
    $email = 'admin@tienda.com';
    $name = 'Administrador';
    $password_plain = 'Admin123';
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
    // check if exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->rowCount() > 0) {
        // update password and role
        $update = $db->prepare("UPDATE users SET password = ?, is_admin = 1, name = ? WHERE email = ?");
        $update->execute([$password_hash, $name, $email]);
        echo "Usuario admin actualizado. Email: $email, Password: $password_plain";
    } else {
        // insert
        $insert = $db->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?,?,?,1)");
        $insert->execute([$name, $email, $password_hash]);
        echo "Usuario admin creado. Email: $email, Password: $password_plain";
    }
    echo "<br><a href='index.php?action=login'>Ir al login</a>";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>