<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/VehiculoController.php';

$pagina_actual = 'vehiculos';
$titulo = 'Registrar Vehículo';

$controller = new VehiculoController();
$propiedades = $controller->listarPropiedades();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = $controller->crear($_POST);
    if (isset($resultado['success'])) {
        header('Location: index.php?mensaje=' . urlencode($resultado['success']));
        exit;
    } else {
        $error = $resultado['error'] ?? "Error al registrar el vehículo";
    }
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="../../assets/css/styles.css">
    <div class="content-header">
        <h1>Registrar Vehículo</h1>
    </div>
    
    <div class="content">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="form-standard" method="POST">
            <div class="form-group">
                <label for="placa">Placa</label>
                <input type="text" id="placa" name="placa" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca</label>
                <select id="marca" name="marca" required>
                    <option value="">Seleccione una marca</option>
                    <option value="Chevrolet">Chevrolet</option>
                    <option value="Renault">Renault</option>
                    <option value="Mazda">Mazda</option>
                    <option value="Toyota">Toyota</option>
                    <option value="Yamaha">Yamaha</option>
                    <option value="Honda">Honda</option>
                    <option value="Suzuki">Suzuki</option>
                    <option value="BMW">BMW</option>
                    <option value="KTM">KTM</option>
                    <option value="AKT">AKT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo (Año)</label>
                <input type="text" id="modelo" name="modelo" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="Carro">Carro</option>
                    <option value="Moto">Moto</option>
                    <option value="Bicicleta">Bicicleta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="propiedad_id">Propiedad</label>
                <select id="propiedad_id" name="propiedad_id" required>
                    <option value="">Seleccione una propiedad</option>
                    <?php foreach ($propiedades as $propiedad): ?>
                        <option value="<?php echo $propiedad['id']; ?>">
                            <?php echo htmlspecialchars($propiedad['descripcion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Registrar Vehículo</button>
                <a href="index.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>