<?php
namespace App\Models\Enseignant;

use App\Config\Database;
use PDO;

class CourseModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function getTotalCourses($teacherId) {
        try {
            $query = "SELECT COUNT(*) as total_cours FROM Cours WHERE enseignat_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $teacherId]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return ['total_cours' => 0];
        }
    }

    public function getTotalStudents($teacherId) {
        try {
            $query = "SELECT COUNT(DISTINCT i.etudiant_id) as total_etudiants 
                     FROM Inscriptions i 
                     JOIN Cours c ON i.cours_id = c.cours_id 
                     WHERE c.enseignat_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $teacherId]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return ['total_etudiants' => 0];
        }
    }

    public function getPopularCourse($teacherId) {
        try {
            $query = "SELECT c.cours_id, COUNT(i.id) as populaires
                     FROM Cours c
                     LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id
                     WHERE c.enseignat_id = :id
                     GROUP BY c.cours_id
                     ORDER BY populaires DESC
                     LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $teacherId]);
            $result = $stmt->fetch();
            return $result ?: ['populaires' => 0];
        } catch (\PDOException $e) {
            return ['populaires' => 0];
        }
    }

    public function getRecentCourses($teacherId) {
        try {
            $query = "SELECT c.*, COUNT(i.id) as total_inscriptions
                     FROM Cours c
                     LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id
                     WHERE c.enseignat_id = :id
                     GROUP BY c.cours_id
                     ORDER BY c.dateAjout DESC
                     LIMIT 3";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $teacherId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function getCategories() {
        try {
            $query = "SELECT category_id, nom FROM Category ORDER BY nom";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function getTags() {
        try {
            $query = "SELECT tag_id, nom FROM Tag ORDER BY nom";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

}