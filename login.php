<!DOCTYPE html>
<html>
<head>
    <title>Login - Ecommerce</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?action=login">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
            </form>
            <div class="links">
                <a href="index.php?action=register">Crear cuenta nueva</a>
                <a href="index.php?action=recover">Recuperar contraseña</a>
            </div>
        </div>
    </div>
</body>
</html>