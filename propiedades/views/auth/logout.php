<?php
session_start();
session_destroy();
header('Location: /propiedades/views/auth/login.php');
exit();