<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/UsuarioController.php';

$pagina_actual = 'usuarios';
$titulo = 'Gestión de Usuarios';

$controller = new UsuarioController();
$usuarios = $controller->listarUsuarios();

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <h1>Gestión de Usuarios</h1>
        <a href="crear.php" class="btn-primary">Nuevo Usuario</a>
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
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['rol_nombre']); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="btn-edit">Editar</a>
                            <form action="eliminar.php" method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                <button type="submit" class="btn-delete" onclick="return confirm('¿Está seguro de eliminar este usuario?')">Eliminar</button>
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