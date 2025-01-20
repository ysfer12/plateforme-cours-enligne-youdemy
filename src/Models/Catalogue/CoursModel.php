<?php
namespace App\Models\Catalogue;
use PDO;
class CoursModel {
    private $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
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
}