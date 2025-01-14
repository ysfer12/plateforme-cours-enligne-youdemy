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
        $roleStmt->bindParam(":roleTitle", $roleTitre);
        $roleStmt->execute();
        $roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$roleRow) {
            throw new \Exception("Role not found");
        }
        $roleId = $roleRow['role_id'];
        
        // Determine the status based on the role title
        $statut = ($roleTitle === 'etudiant') ? 'Actif' : 'Inactif';//ternary operator
        
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
}