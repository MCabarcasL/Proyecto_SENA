<?php
require_once '../../../includes/auth_check.php';
require_once '../../../controllers/PQRSController.php';

if (isset($_GET['id'])) {
    $controller = new PQRSController();
    $pqrs = $controller->obtenerPQRS($_GET['id']);
    $pqrs['respuestas'] = $controller->obtenerRespuestas($_GET['id']);
    
    header('Content-Type: application/json');
    echo json_encode($pqrs);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
}