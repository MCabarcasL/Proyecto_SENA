<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/VehiculoController.php';

$pagina_actual = 'vehiculos';
$titulo = 'Gestión de Vehículos';

$controller = new VehiculoController();
$vehiculos = $controller->listarVehiculos();

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <h1>Gestión de Vehículos</h1>
        <a href="crear.php" class="btn-primary">Nuevo Vehículo</a>
    </div>
    
    <div class="content">
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['mensaje']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <link rel="stylesheet" href="../../assets/css/styles.css">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehiculos as $vehiculo): 
                        $rowClass = '';
                        switch($vehiculo['estado_cartera']) {
                            case 'Al día':
                                $rowClass = 'bg-success text-white';
                                break;
                            case 'En mora':
                                $rowClass = 'bg-danger text-white';
                                break;
                            case 'Convenio':
                                $rowClass = 'bg-warning';
                                break;
                        }
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td><?php echo htmlspecialchars($vehiculo['placa']); ?></td>
                        <td><?php echo htmlspecialchars($vehiculo['marca']); ?></td>
                        <td><?php echo htmlspecialchars($vehiculo['modelo']); ?></td>
                        <td><?php echo htmlspecialchars($vehiculo['tipo']); ?></td>
                        <td>Torre <?php echo htmlspecialchars($vehiculo['torre']); ?> - Piso <?php echo htmlspecialchars($vehiculo['piso']); ?> - <?php echo htmlspecialchars($vehiculo['numero']); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $vehiculo['id']; ?>" class="btn-edit">Editar</a>
                            <form action="eliminar.php" method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $vehiculo['id']; ?>">
                                <button type="submit" class="btn-delete" onclick="return confirm('¿Está seguro de eliminar este vehículo?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>