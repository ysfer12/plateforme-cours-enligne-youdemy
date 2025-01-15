<?php
namespace App\Classes;

abstract class Cours
{
    protected $db;
    protected $cours_id;
    protected $titre;
    protected $description;
    protected $typeContenu;
    protected $lienContenu;
    protected $enseignant_id;
    protected $category_id;
    protected $dateAjout;
    protected $tags = [];

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function setAttributes($titre, $description, $typeContenu, $lienContenu, $enseignant_id, $category_id)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->typeContenu = $typeContenu;
        $this->lienContenu = $lienContenu;
        $this->enseignant_id = $enseignant_id;
        $this->category_id = $category_id;
        $this->dateAjout = date('Y-m-d H:i:s');
    }

    abstract public function createCourse();
    abstract public static function fetchCourse($db);

    // Méthode pour récupérer tous les cours
    public function getAllCourses()
    {
        $query = "SELECT * FROM Cours";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Méthode pour mettre à jour un cours
    public function updateCourse($cours_id)
    {
        $query = "UPDATE Cours 
                SET titre = :titre, 
                    description = :description, 
                    typeContenu = :typeContenu,
                    lienContenu = :lienContenu,
                    category_id = :category_id 
                WHERE cours_id = :cours_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':typeContenu', $this->typeContenu);
        $stmt->bindParam(':lienContenu', $this->lienContenu);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':cours_id', $cours_id);

        return $stmt->execute();
    }

    // Méthode pour supprimer un cours
    public function deleteCourse($cours_id)
    {
        $query = "DELETE FROM Cours WHERE cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cours_id', $cours_id);
        return $stmt->execute();
    }

    // Validation de base pour les cours
    protected function validateCourse()
    {
        $errors = [];

        if (empty($this->titre)) {
            $errors[] = "Le titre est obligatoire";
        }

        if (empty($this->typeContenu)) {
            $errors[] = "Le type de contenu est obligatoire";
        }

        if (empty($this->enseignant_id)) {
            $errors[] = "L'identifiant de l'enseignant est obligatoire";
        }

        if (empty($this->category_id)) {
            $errors[] = "La catégorie est obligatoire";
        }

        return $errors;
    }

    // Gestion des tags
    public function addTag($tag_id)
    {
        $query = "INSERT INTO Cours_Tags (cours_id, tag_id) VALUES (:cours_id, :tag_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cours_id', $this->cours_id);
        $stmt->bindParam(':tag_id', $tag_id);
        return $stmt->execute();
    }

    public function removeTag($tag_id)
    {
        $query = "DELETE FROM Cours_Tags WHERE cours_id = :cours_id AND tag_id = :tag_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cours_id', $this->cours_id);
        $stmt->bindParam(':tag_id', $tag_id);
        return $stmt->execute();
    }

    public function getTags()
    {
        $query = "SELECT t.* FROM Tags t 
                JOIN Cours_Tags ct ON t.tag_id = ct.tag_id 
                WHERE ct.cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cours_id', $this->cours_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Getters
    public function getDb()
    {
        return $this->db;
    }

    public function getCoursId()
    {
        return $this->cours_id;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTypeContenu()
    {
        return $this->typeContenu;
    }

    public function getLienContenu()
    {
        return $this->lienContenu;
    }

    public function getEnseignantId()
    {
        return $this->enseignant_id;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    // Setters
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setTypeContenu($typeContenu)
    {
        $this->typeContenu = $typeContenu;
    }

    public function setLienContenu($lienContenu)
    {
        $this->lienContenu = $lienContenu;
    }

    public function setEnseignantId($enseignant_id)
    {
        $this->enseignant_id = $enseignant_id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }
}