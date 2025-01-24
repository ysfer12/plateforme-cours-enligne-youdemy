<?php 
namespace App\Models\Admin;
use App\Classes\Tags;
use App\Config\Database;
use PDO;

class TagsModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function getTags() {
        $query = "SELECT tag_id, nom FROM Tag ORDER BY tag_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = new Tags($row['tag_id'], $row['nom']);
        }
        
        return $tags;
    }

    public function getTagById($id) {
        $query = "SELECT tag_id, nom FROM Tag WHERE tag_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new Tags($row['tag_id'], $row['nom']);
    }

    public function addTag($nom) {
        $checkQuery = "SELECT tag_id FROM Tag WHERE LOWER(nom) = LOWER(:nom)";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":nom", $nom);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            $existingTag = $checkStmt->fetch(PDO::FETCH_ASSOC);
            return new Tags($existingTag['tag_id'], $nom);
        }
        
        $query = "INSERT INTO Tag (nom) VALUES (:nom)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nom", $nom);
        
        try {
            $stmt->execute();
            $id = $this->conn->lastInsertId();
            return new Tags($id, $nom);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de l'ajout du tag : " . $e->getMessage());
        }
    }

    public function updateTag($id, $nom) {
        $query = "UPDATE Tag SET nom = :nom WHERE tag_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la mise à jour du tag : " . $e->getMessage());
        }
    }

    public function deleteTag($id) {
        $query = "DELETE FROM Tag WHERE tag_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la suppression du tag : " . $e->getMessage());
        }
    }
}