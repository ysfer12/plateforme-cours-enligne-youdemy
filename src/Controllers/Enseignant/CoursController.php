<?php
namespace App\Controllers\Enseignant;

use App\Models\Enseignant\VideoCours;
use App\Models\Enseignant\DocumentCours;
use App\Config\Database;
use App\Models\Enseignant\CourseModel;
use PDO;
use PDOException;

class CoursController
{
    private $db;
    private $courseModel;
    private $error_messages = [];

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connection();
        $this->courseModel = new CourseModel();
    }

    public function createCourse($titre, $description, $lienContenu, $enseignant_id, $category_id, $typeContenu)
    {
        try {
            if ($typeContenu === 'video') {
                $course = new VideoCours(
                    $this->db,
                    $titre,
                    $description,
                    $lienContenu,
                    $enseignant_id,
                    $category_id
                );
            } elseif ($typeContenu === 'document') {
                $course = new DocumentCours(
                    $this->db,
                    $titre,
                    $description,
                    $lienContenu,
                    $enseignant_id,
                    $category_id
                );
            } else {
                throw new \Exception("Type de contenu invalide");
            }

            if ($course->createCourse()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return false;
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

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':typeContenu', $typeContenu);
            $stmt->bindParam(':lienContenu', $lienContenu);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->bindParam(':enseignant_id', $enseignant_id);

            return $stmt->execute();
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return false;
        }
    }

    public function deleteCourse($cours_id)
    {
        try {
            $this->db->prepare("DELETE FROM Cours_Tags WHERE cours_id = ?")->execute([$cours_id]);
            $this->db->prepare("DELETE FROM Inscriptions WHERE cours_id = ?")->execute([$cours_id]);
            $query = "DELETE FROM Cours WHERE cours_id = :cours_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            return $stmt->execute();
        } catch (\Exception $e) {
            $this->error_messages[] = "Erreur lors de la suppression du cours : " . $e->getMessage();
            return false;
        }
    }

    public function fetchAllCourses($typeContenu)
    {
        try {
            if ($typeContenu === 'video') {
                return VideoCours::fetchCourse($this->db);
            } elseif ($typeContenu === 'document') {
                return DocumentCours::fetchCourse($this->db);
            } else {
                throw new \Exception("Type de contenu invalide");
            }
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return [];
        }
    }

    public function getCourseById($cours_id, $typeContenu = null)
    {
        try {
            $query = "SELECT * FROM Cours WHERE cours_id = :cours_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->execute();

            $course = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$course) {
                $this->error_messages[] = "Cours non trouvé";
                return null;
            }

            $typeContenu = $typeContenu ?? $course['typeContenu'];

            if ($typeContenu === 'video') {
                $courseObj = new VideoCours($this->db);
            } elseif ($typeContenu === 'document') {
                $courseObj = new DocumentCours($this->db);
            } else {
                throw new \Exception("Type de contenu invalide");
            }

            return $course;
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return null;
        }
    }

    public function addTagToCourse($cours_id, $tag_id)
    {
        try {
            $query = "INSERT INTO Cours_Tags (cours_id, tag_id) VALUES (:cours_id, :tag_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->bindParam(':tag_id', $tag_id);
            return $stmt->execute();
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return false;
        }
    }

    public function removeTagFromCourse($cours_id, $tag_id)
    {
        try {
            $query = "DELETE FROM Cours_Tags WHERE cours_id = :cours_id AND tag_id = :tag_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->bindParam(':tag_id', $tag_id);
            return $stmt->execute();
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return false;
        }
    }

    public function getCourseTags($cours_id)
    {
        try {
            $query = "SELECT t.* FROM Tag t 
                      JOIN Cours_Tags ct ON t.tag_id = ct.tag_id 
                      WHERE ct.cours_id = :cours_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return [];
        }
    }

    public function getCategories()
    {
        return $this->courseModel->getCategories();
    }

    public function getTags()
    {
        return $this->courseModel->getTags();
    }

    public function getUserInfo($user_id)
    {
        try {
            $query = "SELECT u.*, r.titre as role_titre 
                      FROM Utilisateurs u 
                      JOIN Role r ON u.role_id = r.role_id
                      WHERE u.id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->error_messages[] = "Erreur lors de la récupération des informations de l'utilisateur : " . $e->getMessage();
            return null;
        }
    }

    public function getCoursesByTeacher($enseignant_id)
    {
        try {
            $query = "SELECT * FROM Cours WHERE enseignat_id = :enseignant_id ORDER BY dateAjout DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':enseignant_id', $enseignant_id);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return [];
        }
    }

    public function getCoursesByCategory($category_id)
    {
        try {
            $query = "SELECT * FROM Cours WHERE category_id = :category_id ORDER BY dateAjout DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return [];
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
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':searchTerm', $searchTerm);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return [];
        }
    }

    public function getTeacherCoursesCount($enseignant_id)
    {
        try {
            $query = "SELECT COUNT(*) FROM Cours WHERE enseignat_id = :enseignant_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':enseignant_id', $enseignant_id);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return 0;
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
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':enseignant_id', $enseignant_id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return null;
        }
    }

    public function getRecentCourses($enseignant_id, $limit)
    {
        try {
            $query = "SELECT c.*, COUNT(i.id) as total_inscriptions 
                      FROM Cours c 
                      LEFT JOIN Inscriptions i ON c.cours_id = i.cours_id 
                      WHERE c.enseignat_id = :enseignant_id 
                      GROUP BY c.cours_id 
                      ORDER BY c.dateAjout DESC 
                      LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':enseignant_id', $enseignant_id);
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return [];
        }
    }

    public function getErrorMessages()
    {
        return $this->error_messages;
    }

    public function clearErrorMessages()
    {
        $this->error_messages = [];
    }

    public function validateCourseExists($cours_id)
    {
        try {
            $query = "SELECT COUNT(*) FROM Cours WHERE cours_id = :cours_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cours_id', $cours_id);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (\Exception $e) {
            $this->error_messages[] = $e->getMessage();
            return false;
        }
    }
}