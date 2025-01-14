<?php
namespace App\Models;

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

    public function findUserByEmailAndPassword($email, $mot_de_passe) {
        // Updated query to include the `statut` column
        $query = "SELECT Utilisateurs.id, Utilisateurs.prenom, Utilisateurs.nom, Utilisateurs.email, 
                         Utilisateurs.mot_de_passe, Utilisateurs.statut, 
                         Role.role_id as role_id, Role.titre as `role`
                  FROM Utilisateurs
                  JOIN role ON Role.role_id = Utilisateurs.role_id
                  WHERE Utilisateurs.email = :email AND Utilisateurs.mot_de_passe = :mot_de_passe";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":mot_de_passe", $mot_de_passe);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null; 
        } else {
            $role = new Role($row["role_id"], $row["role"]);
            
            return new Utilisateurs(
                $row['id'],
                $row["prenom"],
                $row["nom"],
                $row["email"],
                $row["mot_de_passe"],
                $role,
                $row["statut"] 
            );
        }
    }

    public function logout() {
        session_destroy();
        header("Location: ../login.php");
    }

    public function register($prenom, $nom, $email, $mot_de_passe, $roleTitre) {
        // Fetch the role ID based on the role title
        $roleQuery = "SELECT role_id FROM Role WHERE titre = :roleTitre";
        $roleStmt = $this->conn->prepare($roleQuery);
        $roleStmt->bindParam(":roleTitre", $roleTitre);
        $roleStmt->execute();
        $roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$roleRow) {
            throw new \Exception("Role not found");
        }
        $roleId = $roleRow['role_id'];
        
        // Determine the status based on the role title
        $statut = ($roleTitre === 'Etudiant') ? 'Actif' : 'Inactif';
        
        // Insert the new user into the database
        $query = "INSERT INTO Utilisateurs (prenom, nom, email, mot_de_passe, role_id, statut) 
                  VALUES (:prenom, :nom, :email, :mot_de_passe, :roleId, :statut)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":prenom", $prenom);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":mot_de_passe", $mot_de_passe);
        $stmt->bindParam(":roleId", $roleId);
        $stmt->bindParam(":statut", $statut);
        
        try {
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Error registering user: " . $e->getMessage());
        }
    }

    // Get active users with pagination (not soft deleted)
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

    // Get total count of active users
    public function getTotalActiveUsers() {
        $query = "SELECT COUNT(*) FROM Utilisateurs WHERE dateSuppression IS NULL";
        $stmt = $this->conn->query($query);
        return $stmt->fetchColumn();
    }

    // Soft delete a user
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

    // Update user status
    public function updateStatus($userId, $newStatus) {
        // Verify the status is valid
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

    // Get user by ID
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

    // Search users
    public function searchUsers($searchTerm, $roleFilter = null) {
        $query = "SELECT u.*, r.role_id, r.titre as role_titre
                 FROM Utilisateurs u
                 JOIN Role r ON r.role_id = u.role_id
                 WHERE u.dateSuppression IS NULL
                 AND (u.prenom LIKE :search 
                      OR u.nom LIKE :search 
                      OR u.email LIKE :search)";

        if ($roleFilter) {
            $query .= " AND r.titre = :role";
        }

        $stmt = $this->conn->prepare($query);
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(':search', $searchTerm);
        
        if ($roleFilter) {
            $stmt->bindParam(':role', $roleFilter);
        }

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

    // Check if email exists
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