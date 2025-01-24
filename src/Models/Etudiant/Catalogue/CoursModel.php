<?php
namespace App\Models\Etudiant\Catalogue;
use App\Config\Database;
use PDO;

class CoursModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function getCategories() {
        $query = "SELECT * FROM Category ORDER BY nom";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countCourses($search = '', $categoryId = 0) {
        $params = [];
        $whereClause = " WHERE 1=1 ";

        if (!empty($search)) {
            $whereClause .= " AND (c.titre LIKE :search OR c.description LIKE :search OR t.nom LIKE :search)";
            $params[':search'] = "%$search%";
        }

        if ($categoryId > 0) {
            $whereClause .= " AND c.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }

        $query = "
            SELECT COUNT(DISTINCT c.cours_id) as total
            FROM Cours c
            LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
            LEFT JOIN Tag t ON ct.tag_id = t.tag_id
            $whereClause
        ";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getCourses($userId, $search = '', $categoryId = 0, $page = 1, $coursesPerPage = 9) {
        $offset = ($page - 1) * $coursesPerPage;
        $params = [];

        $whereClause = " WHERE 1=1 ";
        if (!empty($search)) {
            $whereClause .= " AND (c.titre LIKE :search OR c.description LIKE :search OR t.nom LIKE :search)";
            $params[':search'] = "%$search%";
        }

        if ($categoryId > 0) {
            $whereClause .= " AND c.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }

        $query = "
            SELECT c.*, cat.nom as category_name,
                   GROUP_CONCAT(DISTINCT t.nom) as tag_names,
                   u.prenom, u.nom as nom_enseignant,
                   COUNT(DISTINCT i.etudiant_id) as nombre_inscrits,
                   CASE WHEN ui.cours_id IS NOT NULL THEN 1 ELSE 0 END as is_inscrit
            FROM Cours c
            LEFT JOIN Category cat ON c.category_id = cat.category_id
            LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
            LEFT JOIN Tag t ON ct.tag_id = t.tag_id
            LEFT JOIN Utilisateurs u ON c.enseignat_id = u.id
            LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id
            LEFT JOIN Inscriptions ui ON c.cours_id = ui.cours_id AND ui.etudiant_id = :user_id
            $whereClause
            GROUP BY c.cours_id
            ORDER BY c.dateAjout DESC
            LIMIT :offset, :limit
        ";

        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $coursesPerPage, PDO::PARAM_INT);
        
        // Bind additional search/category parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCoursById($coursId, $userId = null) {
        $query = "
            SELECT c.*, cat.nom as category_name,
                   GROUP_CONCAT(DISTINCT t.nom) as tag_names,
                   u.prenom, u.nom as nom_enseignant,
                   COUNT(DISTINCT i.etudiant_id) as nombre_inscrits,
                   CASE WHEN ui.cours_id IS NOT NULL THEN 1 ELSE 0 END as is_inscrit
            FROM Cours c
            LEFT JOIN Category cat ON c.category_id = cat.category_id
            LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
            LEFT JOIN Tag t ON ct.tag_id = t.tag_id
            LEFT JOIN Utilisateurs u ON c.enseignat_id = u.id
            LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id
            LEFT JOIN Inscriptions ui ON c.cours_id = ui.cours_id AND ui.etudiant_id = :user_id
            WHERE c.cours_id = :cours_id
            GROUP BY c.cours_id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':cours_id', $coursId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}