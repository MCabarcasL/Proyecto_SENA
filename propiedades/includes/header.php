<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Sistema de Propiedades'; ?></title>

 <!-- Determinar la ruta base para los assets -->
 <?php
    $rutaBase = '';
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    for ($i = 0; $i < $depth; $i++) {
        $rutaBase .= '../';
    }
    ?>

    <link rel="stylesheet" href="<?php echo $rutaBase; ?>assets/css/styles.css">
</head>
<body>