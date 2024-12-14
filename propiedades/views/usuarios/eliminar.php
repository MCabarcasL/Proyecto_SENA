<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/UsuarioController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $controller = new UsuarioController();
    $resultado = $controller->eliminar($_POST['id']);
    
    if (isset($resultado['success'])) {
        header('Location: index.php?mensaje=' . urlencode($resultado['success']));
    } else {
        header('Location: index.php?error=' . urlencode($resultado['error'] ?? 'Error al eliminar el usuario'));
    }
} else {
    header('Location: index.php');
}
exit;