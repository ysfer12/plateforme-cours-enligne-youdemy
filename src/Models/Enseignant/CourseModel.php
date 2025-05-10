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

    public function getDb() {
        return $this->conn;
    }

    public function createCourse($titre, $description, $lienContenu, $enseignant_id, $category_id, $typeContenu)
    {
        try {
            if ($typeContenu === 'video') {
                $course = new VideoCours($this->conn, $titre, $description, $lienContenu, $enseignant_id, $category_id);
            } elseif ($typeContenu === 'document') {
                $course = new DocumentCours($this->conn, $titre, $description, $lienContenu, $enseignant_id, $category_id);
            } else {
                throw new \Exception("Type de contenu invalide");
            }

            return $course->createCourse();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateCourse($cours_id, $titre, $description, $lienContenu, $enseignant_id, $category_id, $typeContenu)
    {
        try {
            $query = "UPDATE Cours SET 
                        titre = :titre,
                        description = :description,
                        typeContenu = :typeContenu,
                        lienContenu = :lienContenu,
                        category_id = :category_id
                      WHERE cours_id = :cours_id 
                      AND enseignat_id = :enseignant_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':typeContenu', $typeContenu);
            $stmt->bindParam(':lienContenu', $lienContenu);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->bindParam(':enseignant_id', $enseignant_id);

            return $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteCourse($cours_id)
    {
        try {
            $this->conn->prepare("DELETE FROM Cours_Tags WHERE cours_id = ?")->execute([$cours_id]);
            $this->conn->prepare("DELETE FROM Inscriptions WHERE cours_id = ?")->execute([$cours_id]);
            $query = "DELETE FROM Cours WHERE cours_id = :cours_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            return $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function fetchAllCourses($typeContenu)
    {
        try {
            if ($typeContenu === 'video') {
                return VideoCours::fetchCourse($this->conn);
            } elseif ($typeContenu === 'document') {
                return DocumentCours::fetchCourse($this->conn);
            } else {
                throw new \Exception("Type de contenu invalide");
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCourseById($cours_id, $typeContenu = null)
    {
        try {
            $query = "SELECT * FROM Cours WHERE cours_id = :cours_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->execute();

            $course = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$course) {
                throw new \Exception("Cours non trouvé");
            }

            return $course;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function addTagToCourse($cours_id, $tag_id)
    {
        try {
            error_log("Adding tag to course: cours_id = $cours_id, tag_id = $tag_id");
    
            $checkQuery = "SELECT COUNT(*) FROM Cours_Tags WHERE cours_id = :cours_id AND tag_id = :tag_id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':cours_id', $cours_id);
            $checkStmt->bindParam(':tag_id', $tag_id);
            $checkStmt->execute();
            $exists = $checkStmt->fetchColumn();
    
            error_log("Tag existence check result: $exists");
    
            if ($exists) {
                throw new \Exception("Tag already exists for this course");
            }
    
            $query = "INSERT INTO Cours_Tags (cours_id, tag_id) VALUES (:cours_id, :tag_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->bindParam(':tag_id', $tag_id);
            $result = $stmt->execute();
    
            error_log("Tag insert result: $result");
    
            return $result;
        } catch (\Exception $e) {
            error_log("Exception: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }    
    public function removeAllTagsFromCourse($cours_id)
    {
        try {
            $query = "DELETE FROM Cours_Tags WHERE cours_id = :cours_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            return $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la suppression des tags du cours : " . $e->getMessage());
        }
    }
    public function getCourseTags($cours_id)
    {
        try {
            $query = "SELECT tag_id FROM Cours_Tags WHERE cours_id = :cours_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCategories()
    {
        try {
            $query = "SELECT category_id, nom FROM Category ORDER BY nom";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getTags()
    {
        try {
            $query = "SELECT tag_id, nom FROM Tag ORDER BY nom";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCoursesByTeacher($enseignant_id)
    {
        try {
            $query = "SELECT * FROM Cours WHERE enseignat_id = :enseignant_id ORDER BY dateAjout DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':enseignant_id', $enseignant_id);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function getTotalCourses($teacherId) {
        try {
            $query = "SELECT COUNT(*) as total_cours FROM Cours WHERE enseignat_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $teacherId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération du nombre total de cours : " . $e->getMessage());
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
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération du nombre total d'étudiants : " . $e->getMessage());
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
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: ['populaires' => 0];
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération du cours le plus populaire : " . $e->getMessage());
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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération des cours récents : " . $e->getMessage());
        }
    }

    public function getCoursesByCategory($category_id)
    {
        try {
            $query = "SELECT * FROM Cours WHERE category_id = :category_id ORDER BY dateAjout DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function searchCourses($searchTerm)
    {
        try {
            $searchTerm = "%$searchTerm%";
            $query = "SELECT * FROM Cours 
                      WHERE titre LIKE :searchTerm 
                      OR description LIKE :searchTerm 
                      ORDER BY dateAjout DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':searchTerm', $searchTerm);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getTeacherCoursesCount($enseignant_id)
    {
        try {
            $query = "SELECT COUNT(*) FROM Cours WHERE enseignat_id = :enseignant_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':enseignant_id', $enseignant_id);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getMostPopularCourse($enseignant_id)
    {
        try {
            $query = "SELECT c.cours_id, c.titre, COUNT(i.id) as inscriptions 
                      FROM Cours c 
                      LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id 
                      WHERE c.enseignat_id = :enseignant_id 
                      GROUP BY c.cours_id 
                      ORDER BY inscriptions DESC 
                      LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':enseignant_id', $enseignant_id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function validateCourseExists($cours_id)
    {
        try {
            $query = "SELECT COUNT(*) FROM Cours WHERE cours_id = :cours_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}