<?php
require_once '../../includes/auth_check.php';
require_once '../../controllers/PQRSController.php';

$pagina_actual = 'pqrs_mis';
$titulo = 'Mis PQRS';

$controller = new PQRSController();
$pqrs_list = $controller->listarPQRSUsuario($_SESSION['user_id']);

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="/propiedades/assets/css/styles.css">
    <div class="content-header">
        <h1>Mis PQRS</h1>
        <a href="crear.php" class="btn-primary">Nueva PQRS</a>
    </div>
    
    <div class="content">
        <?php if (isset($_SESSION['pqrs_creada'])): ?>
            <div class="alert alert-success">
                PQRS creada correctamente. Su número de radicado es: <?php echo $_SESSION['pqrs_creada']; ?>
            </div>
            <?php unset($_SESSION['pqrs_creada']); ?>
        <?php endif; ?>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Consecutivo</th>
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
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pqrs['consecutivo']); ?></td>
                        <td><?php echo htmlspecialchars($pqrs['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($pqrs['asunto']); ?></td>
                        <td class="<?php echo $estadoClass; ?>"><?php echo htmlspecialchars($pqrs['estado']); ?></td>
                        <td><?php echo htmlspecialchars($pqrs['prioridad']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($pqrs['created_at'])); ?></td>
                        <td>
                            <button class="btn-info" onclick="verDetalle(<?php echo $pqrs['id']; ?>)">Ver Detalle</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de detalle -->
<div id="modalDetalle" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Detalle de PQRS</h2>
        <div id="detalleContenido"></div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
    border-radius: 5px;
    position: relative;
    max-width: 600px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    position: absolute;
    right: 10px;
    top: 5px;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.detalle-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.detalle-item {
    margin-bottom: 10px;
}

.detalle-label {
    font-weight: bold;
    margin-bottom: 5px;
}

.detalle-valor {
    padding: 5px 0;
}

.respuesta-historial {
    margin-top: 15px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.respuesta-fecha {
    color: #666;
    margin-bottom: 5px;
}

.respuesta-estado {
    margin-bottom: 10px;
}

.respuesta-texto {
    white-space: pre-line;
}

/* Estados */
.bg-warning { background-color: #fff3cd; }
.bg-info { background-color: #cce5ff; }
.bg-success { background-color: #d4edda; }

/* Badge */
.badge-warning {
    background-color: #ffeeba;
    color: #856404;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.85em;
}
</style>

<script>
function verDetalle(id) {
    fetch('obtener_detalle.php?id=' + id)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            let respuestasHtml = '';
            if (data.respuestas && data.respuestas.length > 0) {
                respuestasHtml = `
                    <div class="detalle-item" style="grid-column: 1 / -1;">
                        <div class="detalle-label">Respuestas:</div>
                        ${data.respuestas.map(resp => `
                            <div class="respuesta-historial">
                                <div class="respuesta-fecha">
                                    <strong>Fecha:</strong> ${new Date(resp.created_at).toLocaleString()}
                                </div>
                                <div class="respuesta-estado">
                                    <strong>Estado:</strong> ${resp.estado}
                                </div>
                                <div class="respuesta-texto">
                                    ${resp.respuesta}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            const contenido = `
                <div class="detalle-grid">
                    <div class="detalle-item">
                        <div class="detalle-label">Consecutivo:</div>
                        <div class="detalle-valor">${data.consecutivo}</div>
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
                ${respuestasHtml}
            `;
            
            document.getElementById('detalleContenido').innerHTML = contenido;
            document.getElementById('modalDetalle').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los detalles de la PQRS');
        });
}

// Cerrar modal cuando se hace clic en la X
document.querySelector('.close').onclick = function() {
    document.getElementById('modalDetalle').style.display = 'none';
}

// Cerrar modal cuando se hace clic fuera de él
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = 'none';
    }
}
</script>

<?php require_once '../../includes/footer.php'; ?>