<?php
require_once __DIR__ . '/../config/database.php';

class VehiculoController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function listarVehiculos() {
        try {
            $query = "SELECT v.*, p.torre, p.piso, p.numero, p.estado_cartera 
                     FROM vehiculos v 
                     JOIN propiedades p ON v.propiedad_id = p.id 
                     ORDER BY p.torre, p.piso, p.numero";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al listar vehículos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerVehiculo($id) {
        try {
            $query = "SELECT v.*, p.torre, p.piso, p.numero 
                     FROM vehiculos v 
                     JOIN propiedades p ON v.propiedad_id = p.id 
                     WHERE v.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al obtener vehículo: " . $e->getMessage());
            return null;
        }
    }

    public function crear($datos) {
        try {
            // Verificar si la placa ya existe
            if ($this->placaExiste($datos['placa'])) {
                return ['error' => 'La placa ya está registrada'];
            }

            $query = "INSERT INTO vehiculos (placa, marca, modelo, tipo, propiedad_id) 
                     VALUES (:placa, :marca, :modelo, :tipo, :propiedad_id)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':placa', $datos['placa']);
            $stmt->bindParam(':marca', $datos['marca']);
            $stmt->bindParam(':modelo', $datos['modelo']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':propiedad_id', $datos['propiedad_id']);

            $stmt->execute();
            return ['success' => 'Vehículo registrado correctamente'];
        } catch(PDOException $e) {
            error_log("Error al crear vehículo: " . $e->getMessage());
            return ['error' => 'Error al registrar el vehículo'];
        }
    }

    public function actualizar($id, $datos) {
        try {
            // Verificar si la placa ya existe para otro vehículo
            if ($this->placaExiste($datos['placa'], $id)) {
                return ['error' => 'La placa ya está registrada'];
            }

            $query = "UPDATE vehiculos 
                     SET placa = :placa,
                         marca = :marca,
                         modelo = :modelo,
                         tipo = :tipo,
                         propiedad_id = :propiedad_id 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':placa', $datos['placa']);
            $stmt->bindParam(':marca', $datos['marca']);
            $stmt->bindParam(':modelo', $datos['modelo']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':propiedad_id', $datos['propiedad_id']);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return ['success' => 'Vehículo actualizado correctamente'];
        } catch(PDOException $e) {
            error_log("Error al actualizar vehículo: " . $e->getMessage());
            return ['error' => 'Error al actualizar el vehículo'];
        }
    }

    public function eliminar($id) {
        try {
            $query = "DELETE FROM vehiculos WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return ['success' => 'Vehículo eliminado correctamente'];
            } else {
                return ['error' => 'No se pudo eliminar el vehículo'];
            }
        } catch(PDOException $e) {
            error_log("Error al eliminar vehículo: " . $e->getMessage());
            return ['error' => 'Error al eliminar el vehículo'];
        }
    }

    public function listarPropiedades() {
        try {
            $query = "SELECT id, CONCAT(torre, ' - Piso ', piso, ' - ', numero) as descripcion 
                     FROM propiedades 
                     ORDER BY torre, piso, numero";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al listar propiedades: " . $e->getMessage());
            return [];
        }
    }

    private function placaExiste($placa, $id_excluir = null) {
        try {
            $query = "SELECT id FROM vehiculos WHERE placa = :placa";
            if ($id_excluir) {
                $query .= " AND id != :id";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':placa', $placa);
            
            if ($id_excluir) {
                $stmt->bindParam(':id', $id_excluir);
            }
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("Error al verificar placa: " . $e->getMessage());
            return true;
        }
    }
}