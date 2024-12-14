<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController();
if (!$auth->isLoggedIn()) {
    header('Location: /propiedades/views/auth/login.php');
    exit();
}