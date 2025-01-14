<?php
namespace App\Classes;

class Category {
    private $id;
    private $nom;
    private $statut;
    private $dateAjout;
    private $dateSuppression;
    private $coursCount;

    public function __construct($id, $nom, $statut, $dateAjout = '', $dateSuppression = '', $coursCount = 0) {
        $this->id = $id;
        $this->nom = $nom;
        $this->statut = $statut;
        $this->dateAjout = $dateAjout;
        $this->dateSuppression = $dateSuppression;
        $this->coursCount = $coursCount;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getDateAjout() {
        return $this->dateAjout;
    }

    public function getDateSuppression() {
        return $this->dateSuppression;
    }

    public function getCoursCount() {
        return $this->coursCount;
    }
}