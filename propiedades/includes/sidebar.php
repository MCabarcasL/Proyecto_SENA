<nav class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img src="/propiedades/assets/images/logo.png" alt="PropiedadesApp Logo" class="logo">
            <h2>PropiedadesApp</h2>
        </div>
    </div>
    <ul class="nav-links">
        <li><a href="/propiedades/index.php" <?php echo ($pagina_actual == 'dashboard') ? 'class="active"' : ''; ?>>Dashboard</a></li>
        <li><a href="/propiedades/views/usuarios/index.php" <?php echo ($pagina_actual == 'usuarios') ? 'class="active"' : ''; ?>>Usuarios</a></li>
        <li><a href="/propiedades/views/propiedades/index.php" <?php echo ($pagina_actual == 'propiedades') ? 'class="active"' : ''; ?>>Propiedades</a></li>
        <li><a href="/propiedades/views/vehiculos/index.php" <?php echo ($pagina_actual == 'vehiculos') ? 'class="active"' : ''; ?>>Vehículos</a></li>
        <li class="submenu">
            <a href="#" class="submenu-toggle <?php echo (strpos($pagina_actual, 'pqrs') !== false) ? 'active' : ''; ?>">
                PQRS
                <span class="submenu-arrow">▼</span>
            </a>
            <ul class="submenu-content">
                <li><a href="/propiedades/views/pqrs/crear.php" <?php echo ($pagina_actual == 'pqrs_crear') ? 'class="active"' : ''; ?>>Crear PQRS</a></li>
                <li><a href="/propiedades/views/pqrs/mis_pqrs.php" <?php echo ($pagina_actual == 'pqrs_mis') ? 'class="active"' : ''; ?>>Mis PQRS</a></li>
                <?php if ($_SESSION['user_rol'] == 'Administrador'): ?>
                <li><a href="/propiedades/views/pqrs/admin/index.php" <?php echo ($pagina_actual == 'pqrs_admin') ? 'class="active"' : ''; ?>>Administrar PQRS</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <li><a href="/propiedades/views/auth/logout.php">Cerrar Sesión (<?php echo $_SESSION['user_name'] ?? 'Usuario'; ?>)</a></li>
    </ul>
</nav>

<style>
/* Estilos para el submenú */
.submenu-toggle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-right: 15px;
}

.submenu-arrow {
    font-size: 12px;
    transition: transform 0.3s ease;
}

.submenu.active .submenu-arrow {
    transform: rotate(180deg);
}

.submenu-content {
    display: none;
    padding-left: 20px;
    background-color: rgba(0, 0, 0, 0.05);
}

.submenu.active .submenu-content {
    display: block;
}

.submenu-content a {
    padding: 8px 15px;
    display: block;
    font-size: 0.9em;
    color: inherit;
    text-decoration: none;
}

.submenu-content a:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.submenu-content a.active {
    background-color: rgba(0, 0, 0, 0.15);
    font-weight: bold;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Encontrar el submenú que contiene el enlace activo
    const activeLink = document.querySelector('.submenu-content .active');
    if (activeLink) {
        activeLink.closest('.submenu').classList.add('active');
    }

    // Toggle para los submenús
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = this.closest('.submenu');
            submenu.classList.toggle('active');
        });
    });
});
</script>