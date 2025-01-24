<?php
namespace App\Models\Etudiant;

class EtudiantModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getStudentInfo($userId) {
        $query = "SELECT * FROM Utilisateurs WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getStudentCourses($studentId) {
        $query = "SELECT c.*, cat.nom as category_name,
                  DATE_FORMAT(i.dateInscription, '%d/%m/%Y') as date_inscription
                  FROM Cours c
                  JOIN Inscriptions i ON c.cours_id = i.cours_id
                  LEFT JOIN Category cat ON c.category_id = cat.category_id
                  WHERE i.etudiant_id = :etudiant_id
                  ORDER BY i.dateInscription DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $studentId);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getStatistics($studentId) {
        $courses = $this->getStudentCourses($studentId);
        return [
            'totalCourses' => count($courses),
            'totalCategories' => count(array_unique(array_column($courses, 'category_id'))),
            'lastInscription' => !empty($courses) ? $courses[0]['date_inscription'] : 'Aucune'
        ];
    }
}