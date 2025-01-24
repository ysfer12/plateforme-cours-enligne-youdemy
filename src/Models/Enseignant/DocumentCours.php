<?php
namespace App\Models\Enseignant;

use App\Classes\Cours;

class DocumentCours extends Cours
{
    public function __construct(
        $db,
        $titre = null,
        $description = null,
        $lienContenu = null,
        $enseignant_id = null,
        $category_id = null
    ) {
        parent::__construct($db);
        if ($titre !== null) {
            $this->setAttributes(
                $titre,
                $description,
                'document',
                $lienContenu,
                $enseignant_id,
                $category_id
            );
        }
    }

    public function createCourse()
    {
        if (!$this->validateDocumentCourse()) {
            return false;
        }
    
        $query = "INSERT INTO Cours (
            titre, 
            description, 
            typeContenu, 
            lienContenu, 
            enseignat_id, 
            category_id,
            dateAjout
        ) VALUES (
            :titre, 
            :description, 
            :typeContenu, 
            :lienContenu, 
            :enseignat_id, 
            :category_id,
            :dateAjout
        )";
    
        $stmt = $this->getDb()->prepare($query);
        $stmt->bindValue(':titre', $this->getTitre());
        $stmt->bindValue(':description', $this->getDescription());
        $stmt->bindValue(':typeContenu', $this->getTypeContenu());
        $stmt->bindValue(':lienContenu', $this->getLienContenu());
        $stmt->bindValue(':enseignat_id', $this->getEnseignantId());
        $stmt->bindValue(':category_id', $this->getCategoryId());
        $stmt->bindValue(':dateAjout', $this->getDateAjout());
    
        if ($stmt->execute()) {
            return $this->getDb()->lastInsertId(); // Return the course ID
        } else {
            return false;
        }
    }
    
    public static function fetchCourse($db)
    {
        $query = "SELECT c.*,
                    GROUP_CONCAT(t.tag_id) as tag_ids,
                    GROUP_CONCAT(t.nom) as tag_names
                 FROM Cours c
                 LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
                 LEFT JOIN Tag t ON ct.tag_id = t.tag_id
                 WHERE c.typeContenu = 'document'
                 GROUP BY c.cours_id
                 ORDER BY c.dateAjout DESC";

        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function validateDocumentCourse()
    {
        $errors = parent::validateCourse();

        if (!empty($this->lienContenu)) {
            if (!filter_var($this->lienContenu, FILTER_VALIDATE_URL)) {
                $errors[] = "Le lien du document doit être une URL valide";
            }

            $extension = strtolower(pathinfo($this->lienContenu, PATHINFO_EXTENSION));
            $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];

            if (!in_array($extension, $allowedExtensions)) {
                $errors[] = "Le type de document n'est pas accepté. Extensions autorisées : " . implode(', ', $allowedExtensions);
            }
        }

        return empty($errors);
    }
}