<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/VehiculoController.php';

$pagina_actual = 'vehiculos';
$titulo = 'Editar Vehículo';

$controller = new VehiculoController();

$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$vehiculo = $controller->obtenerVehiculo($id);
if (!$vehiculo) {
    header('Location: index.php');
    exit;
}

$propiedades = $controller->listarPropiedades();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultado = $controller->actualizar($id, $_POST);
    if (isset($resultado['success'])) {
        header('Location: index.php?mensaje=' . urlencode($resultado['success']));
        exit;
    } else {
        $error = $resultado['error'] ?? "Error al actualizar el vehículo";
    }
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="../../assets/css/styles.css">
    <div class="content-header">
        <h1>Editar Vehículo</h1>
    </div>
    
    <div class="content">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="form-standard" method="POST">
            <div class="form-group">
                <label for="placa">Placa</label>
                <input type="text" id="placa" name="placa" value="<?php echo htmlspecialchars($vehiculo['placa']); ?>" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca</label>
                <select id="marca" name="marca" required>
                    <option value="">Seleccione una marca</option>
                    <option value="Chevrolet" <?php echo $vehiculo['marca'] == 'Chevrolet' ? 'selected' : ''; ?>>Chevrolet</option>
                    <option value="Renault" <?php echo $vehiculo['marca'] == 'Renault' ? 'selected' : ''; ?>>Renault</option>
                    <option value="Mazda" <?php echo $vehiculo['marca'] == 'Mazda' ? 'selected' : ''; ?>>Mazda</option>
                    <option value="Toyota" <?php echo $vehiculo['marca'] == 'Toyota' ? 'selected' : ''; ?>>Toyota</option>
                    <option value="Yamaha" <?php echo $vehiculo['marca'] == 'Yamaha' ? 'selected' : ''; ?>>Yamaha</option>
                    <option value="Honda" <?php echo $vehiculo['marca'] == 'Honda' ? 'selected' : ''; ?>>Honda</option>
                    <option value="Suzuki" <?php echo $vehiculo['marca'] == 'Suzuki' ? 'selected' : ''; ?>>Suzuki</option>
                    <option value="BMW" <?php echo $vehiculo['marca'] == 'BMW' ? 'selected' : ''; ?>>BMW</option>
                    <option value="KTM" <?php echo $vehiculo['marca'] == 'KTM' ? 'selected' : ''; ?>>KTM</option>
                    <option value="AKT" <?php echo $vehiculo['marca'] == 'AKT' ? 'selected' : ''; ?>>AKT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo (Año)</label>
                <input type="text" id="modelo" name="modelo" value="<?php echo htmlspecialchars($vehiculo['modelo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="Carro" <?php echo $vehiculo['tipo'] == 'Carro' ? 'selected' : ''; ?>>Carro</option>
                    <option value="Moto" <?php echo $vehiculo['tipo'] == 'Moto' ? 'selected' : ''; ?>>Moto</option>
                    <option value="Bicicleta" <?php echo $vehiculo['tipo'] == 'Bicicleta' ? 'selected' : ''; ?>>Bicicleta</option>
                </select>
            </div>
            <div class="form-group">
                <label for="propiedad_id">Propiedad</label>
                <select id="propiedad_id" name="propiedad_id" required>
                    <option value="">Seleccione una propiedad</option>
                    <?php foreach ($propiedades as $propiedad): ?>
                        <option value="<?php echo $propiedad['id']; ?>" <?php echo $propiedad['id'] == $vehiculo['propiedad_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($propiedad['descripcion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Actualizar Vehículo</button>
                <a href="index.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>