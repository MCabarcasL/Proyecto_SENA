<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/PropiedadController.php';

$pagina_actual = 'propiedades';
$titulo = 'Editar Propiedad';

$controller = new PropiedadController();

$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$propiedad = $controller->obtenerPropiedad($id);
if (!$propiedad) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = $controller->actualizar($id, $_POST);
    if (isset($resultado['success'])) {
        header('Location: index.php?mensaje=' . urlencode($resultado['success']));
        exit;
    } else {
        $error = $resultado['error'] ?? "Error al actualizar la propiedad";
    }
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="../../assets/css/styles.css">
    <div class="content-header">
        <h1>Editar Propiedad</h1>
    </div>
    
    <div class="content">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="form-standard" method="POST">
            <div class="form-group">
                <label for="torre">Torre</label>
                <input type="text" id="torre" name="torre" value="<?php echo htmlspecialchars($propiedad['torre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="piso">Piso</label>
                <input type="text" id="piso" name="piso" value="<?php echo htmlspecialchars($propiedad['piso']); ?>" required>
            </div>
            <div class="form-group">
                <label for="numero">Número de Propiedad</label>
                <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($propiedad['numero']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Propiedad</label>
                <select id="tipo" name="tipo" required>
                    <option value="apartamento" <?php echo $propiedad['tipo'] == 'apartamento' ? 'selected' : ''; ?>>Apartamento</option>
                    <option value="casa" <?php echo $propiedad['tipo'] == 'casa' ? 'selected' : ''; ?>>Casa</option>
                    <option value="local" <?php echo $propiedad['tipo'] == 'local' ? 'selected' : ''; ?>>Local Comercial</option>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" required>
                    <option value="Ocupado" <?php echo $propiedad['estado'] == 'Ocupado' ? 'selected' : ''; ?>>Ocupado</option>
                    <option value="Vacío" <?php echo $propiedad['estado'] == 'Vacío' ? 'selected' : ''; ?>>Vacío</option>
                    <option value="Remodelación" <?php echo $propiedad['estado'] == 'Remodelación' ? 'selected' : ''; ?>>Remodelación</option>
                </select>
            </div>
            <div class="form-group">
                <label for="estado_cartera">Estado Cartera</label>
                <select id="estado_cartera" name="estado_cartera" required>
                    <option value="Al día" <?php echo $propiedad['estado_cartera'] == 'Al día' ? 'selected' : ''; ?>>Al día</option>
                    <option value="En mora" <?php echo $propiedad['estado_cartera'] == 'En mora' ? 'selected' : ''; ?>>En mora</option>
                    <option value="Convenio" <?php echo $propiedad['estado_cartera'] == 'Convenio' ? 'selected' : ''; ?>>Convenio</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Actualizar Propiedad</button>
                <a href="index.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>