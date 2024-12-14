<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/UsuarioController.php';

$pagina_actual = 'usuarios';
$titulo = 'Crear Usuario';

$controller = new UsuarioController();
$roles = $controller->listarRoles();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = $controller->crear($_POST);
    if (isset($resultado['success'])) {
        header('Location: index.php?mensaje=' . urlencode($resultado['success']));
        exit;
    } else {
        $error = $resultado['error'] ?? "Error al crear el usuario";
    }
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="../../assets/css/styles.css">
    <div class="content-header">
        <h1>Crear Usuario</h1>
    </div>
    
    <div class="content">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="form-standard" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono">
            </div>
            <div class="form-group">
                <label for="rol_id">Rol</label>
                <select id="rol_id" name="rol_id" required>
                    <option value="">Seleccione un rol</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Guardar Usuario</button>
                <a href="index.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>