<?php
require_once __DIR__ . '/../config/database.php';

class PQRSController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    private function generarConsecutivo() {
        $year = date('Y');
        $prefix = "PQRS";
        
        // Obtener el último consecutivo del año actual
        $query = "SELECT consecutivo FROM pqrs WHERE consecutivo LIKE :year_pattern ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $pattern = "$prefix-$year-%";
        $stmt->bindParam(':year_pattern', $pattern);
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lastNumber = intval(substr($row['consecutivo'], -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf("%s-%s-%05d", $prefix, $year, $newNumber);
    }

    public function crear($datos) {
        try {
            $consecutivo = $this->generarConsecutivo();
            
            $query = "INSERT INTO pqrs (consecutivo, tipo, asunto, descripcion, prioridad, usuario_id) 
                     VALUES (:consecutivo, :tipo, :asunto, :descripcion, :prioridad, :usuario_id)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':consecutivo', $consecutivo);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':asunto', $datos['asunto']);
            $stmt->bindParam(':descripcion', $datos['descripcion']);
            $stmt->bindParam(':prioridad', $datos['prioridad']);
            $stmt->bindParam(':usuario_id', $datos['usuario_id']);

            $stmt->execute();
            return [
                'success' => 'PQRS creada correctamente',
                'consecutivo' => $consecutivo
            ];
        } catch(PDOException $e) {
            error_log("Error al crear PQRS: " . $e->getMessage());
            return ['error' => 'Error al crear la PQRS'];
        }
    }

    public function listarPQRSUsuario($usuario_id) {
        try {
            $query = "SELECT p.*, pr.respuesta, pr.created_at as fecha_respuesta 
                     FROM pqrs p 
                     LEFT JOIN pqrs_respuestas pr ON p.id = pr.pqrs_id
                     AND pr.created_at = (
                         SELECT MAX(created_at)
                         FROM pqrs_respuestas
                         WHERE pqrs_id = p.id
                     )
                     WHERE p.usuario_id = :usuario_id 
                     ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al listar PQRS del usuario: " . $e->getMessage());
            return [];
        }
    }

    public function listarTodasPQRS() {
        try {
            $query = "SELECT p.*, u.nombre as usuario_nombre 
                     FROM pqrs p 
                     LEFT JOIN usuarios u ON p.usuario_id = u.id 
                     ORDER BY 
                        CASE p.estado 
                            WHEN 'Pendiente' THEN 1 
                            WHEN 'En Proceso' THEN 2 
                            WHEN 'Resuelto' THEN 3 
                        END,
                        CASE p.prioridad 
                            WHEN 'Alta' THEN 1 
                            WHEN 'Media' THEN 2 
                            WHEN 'Baja' THEN 3 
                        END,
                        p.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al listar todas las PQRS: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPQRS($id) {
        try {
            $query = "SELECT p.*, u.nombre as usuario_nombre 
                     FROM pqrs p 
                     LEFT JOIN usuarios u ON p.usuario_id = u.id 
                     WHERE p.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al obtener PQRS: " . $e->getMessage());
            return null;
        }
    }

public function obtenerRespuestas($id) {
    try {
        $query = "SELECT respuesta, estado, created_at 
                 FROM pqrs_respuestas 
                 WHERE pqrs_id = :id 
                 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error al obtener respuestas: " . $e->getMessage());
        return [];
    }
}

    public function actualizarEstado($id, $estado, $respuesta = null) {
        try {
            $this->conn->beginTransaction();
    
            // Actualizar estado en la tabla principal
            $query = "UPDATE pqrs SET estado = :estado WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
    
            // Si hay respuesta, guardarla en el historial
            if ($respuesta) {
                $query = "INSERT INTO pqrs_respuestas (pqrs_id, respuesta, estado) 
                         VALUES (:pqrs_id, :respuesta, :estado)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':pqrs_id', $id);
                $stmt->bindParam(':respuesta', $respuesta);
                $stmt->bindParam(':estado', $estado);
                $stmt->execute();
            }
    
            $this->conn->commit();
            return ['success' => 'Estado de PQRS actualizado correctamente'];
        } catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al actualizar estado de PQRS: " . $e->getMessage());
            return ['error' => 'Error al actualizar el estado'];
        }
    }
    
    public function obtenerHistorialRespuestas($id) {
        try {
            $query = "SELECT respuesta, estado, created_at 
                     FROM pqrs_respuestas 
                     WHERE pqrs_id = :id 
                     ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al obtener historial de respuestas: " . $e->getMessage());
            return [];
        }
    }
}