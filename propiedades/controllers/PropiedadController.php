<?php
require_once __DIR__ . '/../config/database.php';

class PropiedadController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function listarPropiedades() {
        try {
            $query = "SELECT * FROM propiedades ORDER BY torre, piso, numero";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al listar propiedades: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPropiedad($id) {
        try {
            $query = "SELECT * FROM propiedades WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al obtener propiedad: " . $e->getMessage());
            return null;
        }
    }

    public function crear($datos) {
        try {
            // Verificar si la propiedad ya existe con la misma torre, piso y número
            if ($this->propiedadExiste($datos['torre'], $datos['piso'], $datos['numero'])) {
                return ['error' => 'Ya existe una propiedad con esta ubicación'];
            }

            $query = "INSERT INTO propiedades (numero, torre, piso, tipo, estado, estado_cartera) 
                     VALUES (:numero, :torre, :piso, :tipo, :estado, :estado_cartera)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':numero', $datos['numero']);
            $stmt->bindParam(':torre', $datos['torre']);
            $stmt->bindParam(':piso', $datos['piso']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':estado_cartera', $datos['estado_cartera']);

            $stmt->execute();
            return ['success' => 'Propiedad creada correctamente'];
        } catch(PDOException $e) {
            error_log("Error al crear propiedad: " . $e->getMessage());
            return ['error' => 'Error al crear la propiedad'];
        }
    }

    public function actualizar($id, $datos) {
        try {
            // Verificar si la propiedad ya existe con la misma torre, piso y número
            if ($this->propiedadExiste($datos['torre'], $datos['piso'], $datos['numero'], $id)) {
                return ['error' => 'Ya existe una propiedad con esta ubicación'];
            }

            $query = "UPDATE propiedades 
                     SET numero = :numero,
                         torre = :torre,
                         piso = :piso,
                         tipo = :tipo, 
                         estado = :estado, 
                         estado_cartera = :estado_cartera 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':numero', $datos['numero']);
            $stmt->bindParam(':torre', $datos['torre']);
            $stmt->bindParam(':piso', $datos['piso']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':estado_cartera', $datos['estado_cartera']);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return ['success' => 'Propiedad actualizada correctamente'];
        } catch(PDOException $e) {
            error_log("Error al actualizar propiedad: " . $e->getMessage());
            return ['error' => 'Error al actualizar la propiedad'];
        }
    }

    public function eliminar($id) {
        try {
            $query = "DELETE FROM propiedades WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return ['success' => 'Propiedad eliminada correctamente'];
            } else {
                return ['error' => 'No se pudo eliminar la propiedad'];
            }
        } catch(PDOException $e) {
            error_log("Error al eliminar propiedad: " . $e->getMessage());
            return ['error' => 'Error al eliminar la propiedad'];
        }
    }

    private function propiedadExiste($torre, $piso, $numero, $id_excluir = null) {
        try {
            $query = "SELECT id FROM propiedades WHERE torre = :torre AND piso = :piso AND numero = :numero";
            if ($id_excluir) {
                $query .= " AND id != :id";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':torre', $torre);
            $stmt->bindParam(':piso', $piso);
            $stmt->bindParam(':numero', $numero);
            
            if ($id_excluir) {
                $stmt->bindParam(':id', $id_excluir);
            }
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("Error al verificar existencia de propiedad: " . $e->getMessage());
            return true;
        }
    }
}