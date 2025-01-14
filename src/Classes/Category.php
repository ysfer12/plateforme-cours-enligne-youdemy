<?php
namespace App\Classes;

class Category {
    private $category_id;
    private $nom;
    private $description;

    public function __construct($category_id, $nom, $description) {
        $this->category_id = $category_id;
        $this->nom = $nom;
        $this->description = $description;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDescription() {
        return $this->description;
    }
}