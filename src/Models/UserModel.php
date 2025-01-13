<?php
namespace App\Models;

use App\Classes\Role;
use App\Classes\Utilisateur;
use App\Config\Database;
use PDO;

class UserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function findUserByEmailAndPassword($email, $mot_de_pass) {
        // Updated query to include the `statut` column
        $query = "SELECT utilisateur.id, utilisateur.nom, utilisateur.prenom, utilisateur.email, 
                         utilisateur.mot_de_pass, utilisateur.statut, 
                         role.id_role as role_id, role.titre as `role`
                  FROM utilisateur
                  JOIN role ON role.id_role = utilisateur.id_role
                  WHERE utilisateur.email = :email AND utilisateur.mot_de_pass = :password";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $mot_de_pass);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null; // User not found or password mismatch
        } else {
            // Create a Role object
            $role = new Role($row["role_id"], $row["role"]);
            
            // Create and return a Utilisateur object with the `statut` value
            return new Utilisateur(
                $row['id'],
                $row["prenom"],
                $row["nom"],
                $row["email"],
                $role,
                $row["mot_de_pass"],
                $row["statut"] // Include the `statut` value
            );
        }
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