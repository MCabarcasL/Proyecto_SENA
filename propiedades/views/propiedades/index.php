<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/PropiedadController.php';

$pagina_actual = 'propiedades';
$titulo = 'Gestión de Propiedades';

$controller = new PropiedadController();
$propiedades = $controller->listarPropiedades();

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <h1>Gestión de Propiedades</h1>
        <a href="crear.php" class="btn-primary">Nueva Propiedad</a>
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
                        <th>Torre</th>
                        <th>Piso</th>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Estado Cartera</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($propiedades as $propiedad): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($propiedad['torre']); ?></td>
                        <td><?php echo htmlspecialchars($propiedad['piso']); ?></td>
                        <td><?php echo htmlspecialchars($propiedad['numero']); ?></td>
                        <td><?php echo htmlspecialchars($propiedad['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($propiedad['estado']); ?></td>
                        <td><?php echo htmlspecialchars($propiedad['estado_cartera']); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $propiedad['id']; ?>" class="btn-edit">Editar</a>
                            <form action="eliminar.php" method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                                <button type="submit" class="btn-delete" onclick="return confirm('¿Está seguro de eliminar esta propiedad?')">Eliminar</button>
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