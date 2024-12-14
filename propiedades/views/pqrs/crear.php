<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/PQRSController.php';

$pagina_actual = 'pqrs';
$titulo = 'Nueva PQRS';

$controller = new PQRSController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST['usuario_id'] = $_SESSION['user_id'];
    $resultado = $controller->crear($_POST);
    if (isset($resultado['success'])) {
        // Guardamos el consecutivo en una variable de sesión para mostrarlo en una alerta
        $_SESSION['pqrs_creada'] = $resultado['consecutivo'];
        header('Location: mis_pqrs.php');
        exit;
    } else {
        $error = $resultado['error'] ?? "Error al crear la PQRS";
    }
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="../../assets/css/styles.css">
    <div class="content-header">
        <h1>Crear Nueva PQRS</h1>
    </div>
    
    <div class="content">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="form-standard" method="POST">
            <div class="form-group">
                <label for="tipo">Tipo de PQRS</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="Petición">Petición</option>
                    <option value="Queja">Queja</option>
                    <option value="Reclamo">Reclamo</option>
                    <option value="Sugerencia">Sugerencia</option>
                </select>
            </div>
            <div class="form-group">
                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto" required maxlength="200">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="prioridad">Prioridad</label>
                <select id="prioridad" name="prioridad" required>
                    <option value="">Seleccione la prioridad</option>
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Enviar PQRS</button>
                <a href="mis_pqrs.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>