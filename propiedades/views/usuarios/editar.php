<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/UsuarioController.php';

$pagina_actual = 'usuarios';
$titulo = 'Editar Usuario';

$controller = new UsuarioController();

// Obtener el usuario
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$usuario = $controller->obtenerUsuario($id);
if (!$usuario) {
    header('Location: index.php');
    exit;
}

$roles = $controller->listarRoles();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = $controller->actualizar($id, $_POST);
    if (isset($resultado['success'])) {
        header('Location: index.php?mensaje=' . urlencode($resultado['success']));
        exit;
    } else {
        $error = $resultado['error'] ?? "Error al actualizar el usuario";
    }
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <div class="content-header">
        <h1>Editar Usuario</h1>
    </div>
    
    <div class="content">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="form-standard" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña (dejar en blanco para mantener la actual)</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
            </div>
            <div class="form-group">
                <label for="rol_id">Rol</label>
                <select id="rol_id" name="rol_id" required>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?php echo $rol['id']; ?>" <?php echo ($rol['id'] == $usuario['rol_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($rol['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Actualizar Usuario</button>
                <a href="index.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
