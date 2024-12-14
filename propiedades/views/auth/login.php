<?php
require_once '../../controllers/AuthController.php';

session_start();
$auth = new AuthController();

// Si ya está logueado, redirigir al dashboard
if ($auth->isLoggedIn()) {
    header('Location: /propiedades/index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $auth->login($email, $password);
    
    if (isset($result['success'])) {
        header('Location: /propiedades/index.php');
        exit();
    } else {
        $error = $result['error'] ?? 'Error al iniciar sesión';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Propiedades</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="POST">
            <h2>Iniciar Sesión</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Ingresar</button>
        </form>
    </div>
</body>
</html>