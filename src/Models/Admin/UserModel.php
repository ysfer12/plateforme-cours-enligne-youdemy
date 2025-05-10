<?php
namespace App\Models\Admin;
use App\Classes\Role;
use App\Classes\Utilisateurs;
use App\Config\Database;
use PDO;

class UserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }


    public function logout() {
        session_destroy();
        header("Location: ../Auth/login.php");
    }


    public function getActiveUsers($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $query = "SELECT u.*, r.role_id, r.titre as role_titre
                 FROM Utilisateurs u
                 JOIN Role r ON r.role_id = u.role_id
                 WHERE u.dateSuppression IS NULL
                 ORDER BY u.dateAjout DESC
                 LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $role = new Role($row['role_id'], $row['role_titre']);
            $users[] = new Utilisateurs(
                $row['id'],
                $row['prenom'],
                $row['nom'],
                $row['email'],
                $row['mot_de_passe'],
                $role,
                $row['statut'],
                $row['dateAjout'],
                $row['dateSuppression']
            );
        }
        
        return $users;
    }

    public function getTotalActiveUsers() {
        $query = "SELECT COUNT(*) FROM Utilisateurs WHERE dateSuppression IS NULL";
        $stmt = $this->conn->query($query);
        return $stmt->fetchColumn();
    }

    public function softDelete($userId, $date) {
        $query = "UPDATE Utilisateurs 
                 SET dateSuppression = :date 
                 WHERE id = :userId AND dateSuppression IS NULL";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':userId', $userId);
        
        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Error deleting user: " . $e->getMessage());
        }
    }

    public function updateStatus($userId, $newStatus) {
        if (!in_array($newStatus, ['Actif', 'Inactif'])) {
            throw new \Exception("Invalid status value");
        }

        $query = "UPDATE Utilisateurs 
                 SET statut = :status 
                 WHERE id = :userId AND dateSuppression IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':userId', $userId);

        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Error updating user status: " . $e->getMessage());
        }
    }

    public function getUserById($userId) {
        $query = "SELECT u.*, r.role_id, r.titre as role_titre
                 FROM Utilisateurs u
                 JOIN Role r ON r.role_id = u.role_id
                 WHERE u.id = :userId AND u.dateSuppression IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }

        $role = new Role($row['role_id'], $row['role_titre']);
        return new Utilisateurs(
            $row['id'],
            $row['prenom'],
            $row['nom'],
            $row['email'],
            $row['mot_de_passe'],
            $role,
            $row['statut'],
            $row['dateAjout'],
            $row['dateSuppression']
        );
    }


    public function emailExists($email, $excludeUserId = null) {
        $query = "SELECT COUNT(*) FROM Utilisateurs 
                 WHERE email = :email 
                 AND dateSuppression IS NULL";

        if ($excludeUserId) {
            $query .= " AND id != :userId";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        
        if ($excludeUserId) {
            $stmt->bindParam(':userId', $excludeUserId);
        }

        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}