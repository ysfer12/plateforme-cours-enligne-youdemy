<?php
namespace App\Models\Enseignant;
use App\Config\Database;
use PDO;
class UserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

   
    public function getUserInfo($userId) {
        try {
            $query = "SELECT u.*, r.titre as role_titre 
                     FROM Utilisateurs u 
                     JOIN Role r ON u.role_id = r.role_id
                     WHERE u.id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $userId]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            throw new \Exception("Erreur de récupération des informations : " . $e->getMessage());
        }
    }

}
