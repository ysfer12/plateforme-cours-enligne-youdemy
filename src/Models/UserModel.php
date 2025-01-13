<?php
namespace App\Models;

use App\Classes\Role;
use App\Classes\User;
use App\Config\Database;
use PDO;

class UserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function findUserByEmailAndPassword($email, $password) {
        // Updated query to include the `statut` column
        $query = "SELECT user.id, user.firstName, user.lastName, user.email, 
                         user.password, user.statut, 
                         role.role_id as role_id, role.title as `role`
                  FROM user
                  JOIN role ON role.role_id = user.role_id
                  WHERE user.email = :email AND user.password = :password";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null; 
        } else {
            $role = new Role($row["role_id"], $row["role"]);
            
            return new User(
                $row['id'],
                $row["firstName"],
                $row["lastName"],
                $row["email"],
                $row["password"],
                $role,
                $row["statut"] 
            );
        }
    }
    public function logout() {
        session_destroy();
        header("Location: ../login.php");
    }

    public function register($firstname, $lastname, $email, $password, $roleTitle) {
        // Fetch the role ID based on the role title
        $roleQuery = "SELECT id_role FROM role WHERE titre = :roleTitle";
        $roleStmt = $this->conn->prepare($roleQuery);
        $roleStmt->bindParam(":roleTitle", $roleTitle);
        $roleStmt->execute();
        $roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$roleRow) {
            throw new \Exception("Role not found");
        }
        $roleId = $roleRow['id_role'];
        
        // Insert the new user into the database
        $query = "INSERT INTO utilisateur (prenom, nom, email, mot_de_pass, id_role, statut) 
                  VALUES (:firstname, :lastname, :email, :password, :roleId, 'isNotActive')"; // Default status: 'isNotActive'
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":firstname", $firstname);
        $stmt->bindParam(":lastname", $lastname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":roleId", $roleId);
        
        try {
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Error registering user: " . $e->getMessage());
        }
    }
}