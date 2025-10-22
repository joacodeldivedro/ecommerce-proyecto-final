<!DOCTYPE html>
<html>
<head>
    <title>Registro - Ecommerce</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Crear Cuenta</h2>
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="name" placeholder="Nombre completo (solo letras)" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
                <small>Solo se permiten letras y espacios</small>
                
                <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                
                <input type="password" name="password" placeholder="Contraseña (mínimo 8 caracteres, mayúscula, minúscula y número)" required>
                <small>Mínimo 8 caracteres, una mayúscula, una minúscula y un número</small>
                
                <button type="submit">Registrarse</button>
            </form>
            <div class="links">
                <a href="index.php?action=login">Volver al login</a>
            </div>
        </div>
    </div>
</body>
</html>