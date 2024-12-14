<?php
require_once __DIR__ . '/../config/database.php';

class UsuarioController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Listar todos los usuarios
    // También necesitamos actualizar el método listarUsuarios para quitar el filtro de estado
    public function listarUsuarios() {
        try {
            $query = "SELECT u.*, r.nombre as rol_nombre 
                     FROM usuarios u 
                     JOIN roles r ON u.rol_id = r.id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al listar usuarios: " . $e->getMessage());
            return [];
        }
    }

    // Obtener un usuario específico
    public function obtenerUsuario($id) {
        try {
            $query = "SELECT u.*, r.nombre as rol_nombre 
                     FROM usuarios u 
                     JOIN roles r ON u.rol_id = r.id 
                     WHERE u.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return null;
        }
    }

    // Crear nuevo usuario
    public function crear($datos) {
        try {
            // Verificar si el email ya existe
            if ($this->emailExiste($datos['email'])) {
                return ['error' => 'El correo electrónico ya está registrado'];
            }

            $query = "INSERT INTO usuarios (nombre, email, password, telefono, rol_id) 
                     VALUES (:nombre, :email, :password, :telefono, :rol_id)";
            
            $stmt = $this->conn->prepare($query);
            
            // Hash de la contraseña
            $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);
            
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':rol_id', $datos['rol_id']);

            $stmt->execute();
            return ['success' => 'Usuario creado correctamente'];
        } catch(PDOException $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            return ['error' => 'Error al crear el usuario'];
        }
    }

    // Actualizar usuario
    public function actualizar($id, $datos) {
        try {
            // Verificar si el email ya existe para otro usuario
            if ($this->emailExiste($datos['email'], $id)) {
                return ['error' => 'El correo electrónico ya está registrado'];
            }

            $query = "UPDATE usuarios 
                     SET nombre = :nombre, 
                         email = :email, 
                         telefono = :telefono, 
                         rol_id = :rol_id";
            
            // Si se proporcionó una nueva contraseña, actualizarla
            if (!empty($datos['password'])) {
                $query .= ", password = :password";
            }
            
            $query .= " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':rol_id', $datos['rol_id']);
            $stmt->bindParam(':id', $id);
            
            if (!empty($datos['password'])) {
                $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $password_hash);
            }

            $stmt->execute();
            return ['success' => 'Usuario actualizado correctamente'];
        } catch(PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return ['error' => 'Error al actualizar el usuario'];
        }
    }

    // Eliminar usuario (borrado lógico)
    public function eliminar($id) {
        try {
            // Primero verificamos si el usuario existe
            $checkQuery = "SELECT id FROM usuarios WHERE id = :id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':id', $id);
            $checkStmt->execute();

            if ($checkStmt->rowCount() === 0) {
                return ['error' => 'Usuario no encontrado'];
            }

            // Realizamos el borrado físico
            $query = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ['success' => 'Usuario eliminado correctamente'];
            } else {
                return ['error' => 'No se pudo eliminar el usuario'];
            }
        } catch(PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return ['error' => 'Error al eliminar el usuario'];
        }
    }

    // Listar roles disponibles
    public function listarRoles() {
        try {
            $query = "SELECT id, nombre FROM roles";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al listar roles: " . $e->getMessage());
            return [];
        }
    }

    // Verificar si existe el email
    private function emailExiste($email, $id_excluir = null) {
        try {
            $query = "SELECT id FROM usuarios WHERE email = :email";
            if ($id_excluir) {
                $query .= " AND id != :id";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            
            if ($id_excluir) {
                $stmt->bindParam(':id', $id_excluir);
            }
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("Error al verificar email: " . $e->getMessage());
            return true; // Por seguridad, asumimos que existe en caso de error
        }
    }
}