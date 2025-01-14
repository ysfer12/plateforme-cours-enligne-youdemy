<?php

namespace App\Classes;

class Role {
    private $id;
    private $titre;

    public function __construct($id, $titre) {
        $this->id = $id;
        $this->titre = $titre;
    }

    public function getTitre() {
        return $this->titre;
    }
}