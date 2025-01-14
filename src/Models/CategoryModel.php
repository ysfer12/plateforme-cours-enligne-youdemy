<?php
namespace App\Models;

use App\Classes\Category;
use App\Config\Database;
use PDO;

class CategoryModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function getAllCategories() {
        $query = "SELECT category_id, nom, description FROM Category ORDER BY nom";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category(
                $row['category_id'],
                $row['nom'],
                $row['description']
            );
        }

        return $categories;
    }

    public function getCategoryById($categoryId) {
        $query = "SELECT category_id, nom, description FROM Category WHERE category_id = :category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }

        return new Category(
            $row['category_id'],
            $row['nom'],
            $row['description']
        );
    }

    public function deleteCategory($categoryId) {
        // First check if category has any associated courses
        $checkQuery = "SELECT COUNT(*) FROM Cours WHERE category_id = :category_id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':category_id', $categoryId);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            throw new \Exception("Cette catégorie ne peut pas être supprimée car elle contient des cours.");
        }

        $query = "DELETE FROM Category WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);

        return $stmt->execute();
    }

    public function updateCategory($categoryId, $nom, $description) {
        $query = "UPDATE Category 
                 SET nom = :nom, description = :description 
                 WHERE category_id = :category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function getCourseCount($categoryId) {
        $query = "SELECT COUNT(*) FROM Cours WHERE category_id = :category_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}