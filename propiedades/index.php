<?php
// index.php (dashboard)
require_once 'includes/auth_check.php';
require_once 'controllers/DashboardController.php';

$pagina_actual = 'dashboard';
$titulo = 'Dashboard - Sistema de Propiedades';

$controller = new DashboardController();
$estadisticas = $controller->getEstadisticas();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="content-wrapper">
<link rel="stylesheet" href="assets/css/styles.css">
    <div class="content-header">
        <h1>Dashboard</h1>
    </div>
    
    <div class="dashboard-stats">
        <!-- Usuarios -->
        <div class="stats-card">
            <div class="stats-header">
                <i class="fas fa-users"></i>
                <h3>Usuarios</h3>
            </div>
            <div class="stats-body">
                <div class="stat-item">
                    <span class="stat-label">Total Usuarios:</span>
                    <span class="stat-value"><?php echo $estadisticas['usuarios']['total_usuarios']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Residentes:</span>
                    <span class="stat-value"><?php echo $estadisticas['usuarios']['total_residentes']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Vigilantes:</span>
                    <span class="stat-value"><?php echo $estadisticas['usuarios']['total_vigilantes']; ?></span>
                </div>
            </div>
        </div>

        <!-- Propiedades -->
        <div class="stats-card">
    <div class="stats-header">
        <i class="fas fa-building"></i>
        <h3>Propiedades</h3>
    </div>
    <div class="stats-body">
        <div class="stat-item">
            <span class="stat-label">Total Propiedades:</span>
            <span class="stat-value"><?php echo $estadisticas['propiedades']['total_propiedades']; ?></span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Apartamentos:</span>
            <span class="stat-value"><?php echo $estadisticas['propiedades']['total_apartamentos']; ?></span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Casas:</span>
            <span class="stat-value"><?php echo $estadisticas['propiedades']['total_casas']; ?></span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Locales:</span>
            <span class="stat-value"><?php echo $estadisticas['propiedades']['total_locales']; ?></span>
        </div>
        <div class="stat-separator">Estado de Cartera</div>
        <div class="stat-item estado-aldia">
            <span class="stat-label">Al día:</span>
            <span class="stat-value badge-success"><?php echo $estadisticas['propiedades']['al_dia']; ?></span>
        </div>
        <div class="stat-item estado-mora">
            <span class="stat-label">En mora:</span>
            <span class="stat-value badge-danger"><?php echo $estadisticas['propiedades']['en_mora']; ?></span>
        </div>
        <div class="stat-item estado-convenio">
            <span class="stat-label">Convenio:</span>
            <span class="stat-value badge-warning"><?php echo $estadisticas['propiedades']['en_convenio']; ?></span>
        </div>
    </div>
</div>


        <!-- Vehículos -->
        <div class="stats-card">
            <div class="stats-header">
                <i class="fas fa-car"></i>
                <h3>Vehículos</h3>
            </div>
            <div class="stats-body">
                <div class="stat-item">
                    <span class="stat-label">Total Vehículos:</span>
                    <span class="stat-value"><?php echo $estadisticas['vehiculos']['total_vehiculos']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Carros:</span>
                    <span class="stat-value"><?php echo $estadisticas['vehiculos']['total_carros']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Motos:</span>
                    <span class="stat-value"><?php echo $estadisticas['vehiculos']['total_motos']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Bicicletas:</span>
                    <span class="stat-value"><?php echo $estadisticas['vehiculos']['total_bicicletas']; ?></span>
                </div>
            </div>
        </div>

        <!-- PQRS -->
        <div class="stats-card">
            <div class="stats-header">
                <i class="fas fa-ticket-alt"></i>
                <h3>PQRS</h3>
            </div>
            <div class="stats-body">
                <div class="stat-item">
                    <span class="stat-label">Total PQRS:</span>
                    <span class="stat-value"><?php echo $estadisticas['pqrs']['total_pqrs']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Pendientes:</span>
                    <span class="stat-value"><?php echo $estadisticas['pqrs']['pendientes']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">En Proceso:</span>
                    <span class="stat-value"><?php echo $estadisticas['pqrs']['en_proceso']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Resueltos:</span>
                    <span class="stat-value"><?php echo $estadisticas['pqrs']['resueltos']; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-separator {
    padding: 10px;
    background-color: #f8f9fa;
    font-weight: bold;
    margin: 10px -15px;
    padding-left: 15px;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.badge-success, .badge-danger, .badge-warning {
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
    color: white;
}

.badge-success {
    background-color: #28a745;
}

.badge-danger {
    background-color: #dc3545;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

.estado-aldia, .estado-mora, .estado-convenio {
    padding: 8px;
    margin: 5px 0;
    border-radius: 4px;
}

.estado-aldia {
    background-color: rgba(40, 167, 69, 0.1);
}

.estado-mora {
    background-color: rgba(220, 53, 69, 0.1);
}

.estado-convenio {
    background-color: rgba(255, 193, 7, 0.1);
}

.stats-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.stats-header {
    background: var(--primary-color);
    color: white;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stats-body {
    padding: 15px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    color: #666;
}

.stat-value {
    font-weight: bold;
}
</style>

<?php require_once 'includes/footer.php'; ?>