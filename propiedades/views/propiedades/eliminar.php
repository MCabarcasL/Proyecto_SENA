<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/PropiedadController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $controller = new PropiedadController();
    $resultado = $controller->eliminar($_POST['id']);
    
    if (isset($resultado['success'])) {
        header('Location: index.php?mensaje=' . urlencode($resultado['success']));
    } else {
        header('Location: index.php?error=' . urlencode($resultado['error'] ?? 'Error al eliminar la propiedad'));
    }
} else {
    header('Location: index.php');
}
exit;