<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/PropiedadController.php';

$pagina_actual = 'propiedades';
$titulo = 'Crear Propiedad';

$controller = new PropiedadController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = $controller->crear($_POST);
    if (isset($resultado['success'])) {
        header('Location: index.php?mensaje=' . urlencode($resultado['success']));
        exit;
    } else {
        $error = $resultado['error'] ?? "Error al crear la propiedad";
    }
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="../../assets/css/styles.css">
    <div class="content-header">
        <h1>Crear Propiedad</h1>
    </div>
    
    <div class="content">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="form-standard" method="POST">
        <div class="form-group">
                <label for="numero">Número de Propiedad</label>
                <input type="text" id="numero" name="numero" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Propiedad</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="apartamento">Apartamento</option>
                    <option value="casa">Casa</option>
                    <option value="local">Local Comercial</option>
                </select>
            </div>
            <div class="form-group">
                <label for="torre">Torre</label>
                <input type="text" id="torre" name="torre" required>
            </div>
            <div class="form-group">
                <label for="piso">Piso</label>
                <input type="text" id="piso" name="piso" required>
            </div>
          
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" required>
                    <option value="">Seleccione el estado</option>
                    <option value="Ocupado">Ocupado</option>
                    <option value="Vacío">Vacío</option>
                    <option value="Remodelación">Remodelación</option>
                </select>
            </div>
            <div class="form-group">
                <label for="estado_cartera">Estado Cartera</label>
                <select id="estado_cartera" name="estado_cartera" required>
                    <option value="">Seleccione el estado de cartera</option>
                    <option value="Al día">Al día</option>
                    <option value="En mora">En mora</option>
                    <option value="Convenio">Convenio</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Guardar Propiedad</button>
                <a href="index.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>