<?php
require_once '../../../includes/auth_check.php';
require_once '../../../controllers/PQRSController.php';

// Verificar si el usuario es administrador
if ($_SESSION['user_rol'] !== 'Administrador') {
    header('Location: ../../index.php');
    exit;
}

$pagina_actual = 'pqrs';
$titulo = 'Administración de PQRS';

$controller = new PQRSController();
$pqrs_list = $controller->listarTodasPQRS();

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="/propiedades/assets/css/styles.css">
    <div class="content-header">
        <h1>Administración de PQRS</h1>
    </div>
    
    <div class="content">
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
            ?>
        </div>
    <?php endif; ?>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Consecutivo</th>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Asunto</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pqrs_list as $pqrs): 
                        // Definir clases según estado y prioridad
                        $estadoClass = '';
                        switch($pqrs['estado']) {
                            case 'Pendiente':
                                $estadoClass = 'bg-warning';
                                break;
                            case 'En Proceso':
                                $estadoClass = 'bg-info';
                                break;
                            case 'Resuelto':
                                $estadoClass = 'bg-success';
                                break;
                        }
                        
                        $prioridadClass = '';
                        switch($pqrs['prioridad']) {
                            case 'Alta':
                                $prioridadClass = 'text-danger fw-bold';
                                break;
                            case 'Media':
                                $prioridadClass = 'text-warning fw-bold';
                                break;
                            case 'Baja':
                                $prioridadClass = 'text-success';
                                break;
                        }
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pqrs['consecutivo']); ?></td>
                        <td><?php echo htmlspecialchars($pqrs['usuario_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($pqrs['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($pqrs['asunto']); ?></td>
                        <td class="<?php echo $estadoClass; ?>"><?php echo htmlspecialchars($pqrs['estado']); ?></td>
                        <td class="<?php echo $prioridadClass; ?>"><?php echo htmlspecialchars($pqrs['prioridad']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($pqrs['created_at'])); ?></td>
                        <td>
                            <button class="btn-info" onclick="verDetalle('<?php echo $pqrs['id']; ?>')">Ver Detalle</button>
                            <?php if ($pqrs['estado'] !== 'Resuelto'): ?>
                                <button class="btn-primary" onclick="responderPQRS('<?php echo $pqrs['id']; ?>')">Responder</button>
                            <?php else: ?>
                                <button class="btn-primary" disabled style="opacity: 0.5; cursor: not-allowed;">Resuelto</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para ver detalle -->
<div id="modalDetalle" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Detalle de PQRS</h2>
        <div id="detalleContenido"></div>
    </div>
</div>

<!-- Modal para responder -->
<div id="modalResponder" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Responder PQRS</h2>
        <form id="formRespuesta" method="POST" action="responder.php">
            <input type="hidden" name="pqrs_id" id="pqrs_id">
            <div class="form-group">
                <label for="estado">Actualizar Estado</label>
                <select name="estado" id="estado" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="En Proceso">En Proceso</option>
                    <option value="Resuelto">Resuelto</option>
                </select>
            </div>
            <div class="form-group">
                <label for="respuesta">Respuesta</label>
                <textarea name="respuesta" id="respuesta" rows="4" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Enviar Respuesta</button>
                <button type="button" class="btn-secondary" onclick="cerrarModal('modalResponder')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Estilos para los modales y estados */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    position: relative;
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    width: 70%;
    border-radius: 5px;
}

.close {
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 28px;
    cursor: pointer;
}

.bg-warning { background-color: #fff3cd; }
.bg-info { background-color: #cce5ff; }
.bg-success { background-color: #d4edda; }
.text-danger { color: #dc3545; }
.text-warning { color: #ffc107; }
.text-success { color: #28a745; }
.fw-bold { font-weight: bold; }

/* Estilos para los detalles */
#detalleContenido {
    margin-top: 20px;
}

.detalle-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.detalle-item {
    margin-bottom: 10px;
}

.detalle-label {
    font-weight: bold;
}

.detalle-valor {
    margin-top: 5px;
}
</style>

<script>
function verDetalle(id) {
    // Hacer una petición AJAX para obtener los detalles
    fetch(`obtener_detalle.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            const contenido = `
                <div class="detalle-grid">
                    <div class="detalle-item">
                        <div class="detalle-label">Consecutivo:</div>
                        <div class="detalle-valor">${data.consecutivo}</div>
                    </div>
                    <div class="detalle-item">
                        <div class="detalle-label">Usuario:</div>
                        <div class="detalle-valor">${data.usuario_nombre}</div>
                    </div>
                    <div class="detalle-item">
                        <div class="detalle-label">Tipo:</div>
                        <div class="detalle-valor">${data.tipo}</div>
                    </div>
                    <div class="detalle-item">
                        <div class="detalle-label">Estado:</div>
                        <div class="detalle-valor">${data.estado}</div>
                    </div>
                    <div class="detalle-item">
                        <div class="detalle-label">Prioridad:</div>
                        <div class="detalle-valor">${data.prioridad}</div>
                    </div>
                    <div class="detalle-item">
                        <div class="detalle-label">Fecha:</div>
                        <div class="detalle-valor">${new Date(data.created_at).toLocaleString()}</div>
                    </div>
                </div>
                <div class="detalle-item" style="grid-column: 1 / -1;">
                    <div class="detalle-label">Asunto:</div>
                    <div class="detalle-valor">${data.asunto}</div>
                </div>
                <div class="detalle-item" style="grid-column: 1 / -1;">
                    <div class="detalle-label">Descripción:</div>
                    <div class="detalle-valor">${data.descripcion}</div>
                </div>
                ${data.respuesta ? `
                    <div class="detalle-item" style="grid-column: 1 / -1;">
                        <div class="detalle-label">Respuesta:</div>
                        <div class="detalle-valor">${data.respuesta}</div>
                        <div class="detalle-valor"><small>Respondido el: ${new Date(data.fecha_respuesta).toLocaleString()}</small></div>
                    </div>
                ` : ''}
            `;

            
            
            document.getElementById('detalleContenido').innerHTML = contenido;
            document.getElementById('modalDetalle').style.display = 'block';
        });
}

function responderPQRS(id) {
    document.getElementById('pqrs_id').value = id;
    document.getElementById('modalResponder').style.display = 'block';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Cerrar modales cuando se hace clic fuera de ellos
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = 'none';
    }
}

// Cerrar modales con el botón X
document.querySelectorAll('.close').forEach(element => {
    element.onclick = function() {
        this.parentElement.parentElement.style.display = 'none';
    }
});
</script>

<?php require_once '../../../includes/footer.php'; ?>