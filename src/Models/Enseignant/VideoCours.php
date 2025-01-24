<?php
namespace App\Models\Enseignant;

use App\Classes\Cours;

class VideoCours extends Cours
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
                'video',
                $lienContenu,
                $enseignant_id,
                $category_id
            );
        }
    }

    public function createCourse()
    {
        if (!$this->validateVideoCourse()) {
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

        return $stmt->execute();
    }

    public static function fetchCourse($db)
    {
        $query = "SELECT c.*,
                    GROUP_CONCAT(t.tag_id) as tag_ids,
                    GROUP_CONCAT(t.nom) as tag_names
                 FROM Cours c
                 LEFT JOIN Cours_Tags ct ON c.cours_id = ct.cours_id
                 LEFT JOIN Tag t ON ct.tag_id = t.tag_id
                 WHERE c.typeContenu = 'video'
                 GROUP BY c.cours_id
                 ORDER BY c.dateAjout DESC";

        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function validateVideoCourse()
    {
        $errors = parent::validateCourse();

        if (!empty($this->lienContenu)) {
            if (!filter_var($this->lienContenu, FILTER_VALIDATE_URL)) {
                $errors[] = "Le lien de la vidéo doit être une URL valide";
            }

            $validDomains = ['youtube.com', 'youtu.be', 'vimeo.com'];
            $domain = parse_url($this->lienContenu, PHP_URL_HOST);
            $isValidDomain = false;

            foreach ($validDomains as $validDomain) {
                if (strpos($domain, $validDomain) !== false) {
                    $isValidDomain = true;
                    break;
                }
            }

            if (!$isValidDomain) {
                $errors[] = "Le lien doit provenir d'une plateforme vidéo acceptée (YouTube, Vimeo)";
            }
        }

        return empty($errors);
    }
}