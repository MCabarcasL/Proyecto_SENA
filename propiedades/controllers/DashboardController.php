<?php
// controllers/DashboardController.php
require_once __DIR__ . '/../config/database.php';

class DashboardController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getEstadisticas() {
        try {
            $stats = [];
            
            // Total Usuarios
            $query = "SELECT 
                        COUNT(*) as total_usuarios,
                        SUM(CASE WHEN rol_id = 2 THEN 1 ELSE 0 END) as total_residentes,
                        SUM(CASE WHEN rol_id = 3 THEN 1 ELSE 0 END) as total_vigilantes
                     FROM usuarios";
            $stmt = $this->conn->query($query);
            $stats['usuarios'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Total Propiedades por tipo
            $query = "SELECT 
                        COUNT(*) as total_propiedades,
                        SUM(CASE WHEN tipo = 'apartamento' THEN 1 ELSE 0 END) as total_apartamentos,
                        SUM(CASE WHEN tipo = 'casa' THEN 1 ELSE 0 END) as total_casas,
                        SUM(CASE WHEN tipo = 'local' THEN 1 ELSE 0 END) as total_locales
                     FROM propiedades";
            $stmt = $this->conn->query($query);
            $stats['propiedades'] = $stmt->fetch(PDO::FETCH_ASSOC);

             // Propiedades con estado de cartera
             $query = "SELECT 
             COUNT(*) as total_propiedades,
             SUM(CASE WHEN tipo = 'apartamento' THEN 1 ELSE 0 END) as total_apartamentos,
             SUM(CASE WHEN tipo = 'casa' THEN 1 ELSE 0 END) as total_casas,
             SUM(CASE WHEN tipo = 'local' THEN 1 ELSE 0 END) as total_locales,
             SUM(CASE WHEN estado_cartera = 'Al día' THEN 1 ELSE 0 END) as al_dia,
             SUM(CASE WHEN estado_cartera = 'En mora' THEN 1 ELSE 0 END) as en_mora,
             SUM(CASE WHEN estado_cartera = 'Convenio' THEN 1 ELSE 0 END) as en_convenio
          FROM propiedades";
            $stmt = $this->conn->query($query);
            $stats['propiedades'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Total Vehículos por tipo
            $query = "SELECT 
                        COUNT(*) as total_vehiculos,
                        SUM(CASE WHEN tipo = 'carro' THEN 1 ELSE 0 END) as total_carros,
                        SUM(CASE WHEN tipo = 'moto' THEN 1 ELSE 0 END) as total_motos,
                        SUM(CASE WHEN tipo = 'bicicleta' THEN 1 ELSE 0 END) as total_bicicletas
                     FROM vehiculos";
            $stmt = $this->conn->query($query);
            $stats['vehiculos'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // PQRS por estado y tipo
            $query = "SELECT 
                        COUNT(*) as total_pqrs,
                        SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                        SUM(CASE WHEN estado = 'en_proceso' THEN 1 ELSE 0 END) as en_proceso,
                        SUM(CASE WHEN estado = 'resuelto' THEN 1 ELSE 0 END) as resueltos
                     FROM pqrs";
            $stmt = $this->conn->query($query);
            $stats['pqrs'] = $stmt->fetch(PDO::FETCH_ASSOC);

            return $stats;
        } catch(PDOException $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return null;
        }
    }
}