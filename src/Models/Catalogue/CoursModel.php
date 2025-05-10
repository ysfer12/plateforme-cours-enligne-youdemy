<?php
namespace App\Models\Catalogue;
use PDO;
use App\Config\Database;
class CoursModel {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->connection();
    }
    public function getCategories() {
        $query = "SELECT category_id, nom FROM Category ORDER BY nom";
        return $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourses($search, $category, $page, $coursParPage) {
        $offset = ($page - 1) * $coursParPage;
        $whereClause = "WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $whereClause .= " AND c.titre LIKE :search";
            $params[':search'] = "%$search%";
        }

        if ($category > 0) {
            $whereClause .= " AND c.category_id = :category";
            $params[':category'] = $category;
        }

        $query = "
        SELECT DISTINCT c.*, cat.nom as category_name,
                GROUP_CONCAT(DISTINCT t.tag_id) as tag_ids,
                GROUP_CONCAT(DISTINCT t.nom) as tag_names,
                u.prenom, u.nom as nom_enseignant,
                c.typeContenu, c.lienContenu
        FROM Cours c
            LEFT JOIN Category cat ON c.category_id = cat.category_id
            LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
            LEFT JOIN Tag t ON ct.tag_id = t.tag_id
            LEFT JOIN Utilisateurs u ON c.enseignat_id = u.id
            $whereClause
            GROUP BY c.cours_id
            ORDER BY c.dateAjout DESC
            LIMIT :offset, :limit
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $coursParPage, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCourses($search, $category) {
        $whereClause = "WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $whereClause .= " AND c.titre LIKE :search";
            $params[':search'] = "%$search%";
        }

        if ($category > 0) {
            $whereClause .= " AND c.category_id = :category";
            $params[':category'] = $category;
        }

        $query = "
            SELECT COUNT(DISTINCT c.cours_id) as total 
            FROM Cours c
            LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
            LEFT JOIN Tag t ON ct.tag_id = t.tag_id
            $whereClause
        ";

        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getCourseDetails($courseId, $userId = null) {
        $query = "
            SELECT c.*, cat.nom as category_name,
                   GROUP_CONCAT(DISTINCT t.nom) as tag_names,
                   u.prenom, u.nom as nom_enseignant, u.email as email_enseignant,
                   COUNT(DISTINCT i.etudiant_id) as nombre_inscrits,
                   TIMESTAMPDIFF(MONTH, c.dateAjout, CURRENT_TIMESTAMP) as months_since_creation,
                   c.typeContenu, c.lienContenu,
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

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':cours_id', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSimilarCourses($categoryId, $excludeCourseId, $limit = 3) {
        $query = "
            SELECT c.cours_id, c.titre, c.description, cat.nom as category_name,
                   u.prenom, u.nom as nom_enseignant
            FROM Cours c
            LEFT JOIN Category cat ON c.category_id = cat.category_id
            LEFT JOIN Utilisateurs u ON c.enseignat_id = u.id
            WHERE c.category_id = :category_id 
            AND c.cours_id != :exclude_course_id
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':exclude_course_id', $excludeCourseId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}