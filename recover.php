<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Contraseña - Ecommerce</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Recuperar Contraseña</h2>
            
            <?php if(isset($message)): ?>
                <div class="success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(!isset($showResetForm) || !$showResetForm): ?>
                <form method="POST">
                    <input type="email" name="email" placeholder="Ingresa tu email" required>
                    <button type="submit">Enviar enlace de recuperación</button>
                </form>
            <?php else: ?>
                <form method="POST">
                    <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>">
                    <input type="password" name="new_password" placeholder="Nueva contraseña" required>
                    <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
                    <small>Mínimo 8 caracteres, una mayúscula, una minúscula y un número</small>
                    <button type="submit">Actualizar Contraseña</button>
                </form>
            <?php endif; ?>
            
            <div class="links">
                <a href="index.php?action=login">Volver al login</a>
            </div>
        </div>
    </div>
</body>
</html>