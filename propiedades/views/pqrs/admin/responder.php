<?php
require_once '../../../includes/auth_check.php';
require_once '../../../controllers/PQRSController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pqrs_id'])) {
    $controller = new PQRSController();
    $resultado = $controller->actualizarEstado(
        $_POST['pqrs_id'],
        $_POST['estado'],
        $_POST['respuesta']
    );
    
    if (isset($resultado['success'])) {
        $_SESSION['mensaje'] = $resultado['success'];
    } else {
        $_SESSION['mensaje'] = $resultado['error'] ?? 'Error al responder la PQRS';
    }
}

header('Location: index.php');
exit;