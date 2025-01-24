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