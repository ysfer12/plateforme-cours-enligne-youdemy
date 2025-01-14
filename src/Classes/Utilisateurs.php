<?php

namespace App\Classes;


class Utilisateurs {
    public $id;
    public $prenom;
    public $nom;
    public $email;
    public $mot_de_passe;
    public $role;
    public $statut;
    public $dateAjout;
    public $dateSuppression;

    
    public function __construct($id, $prenom, $nom, $email, $mot_de_passe='', $role, $statut, $dateAjout='', $dateSuppression='') {
        $this->id = $id;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->role = $role;
        $this->statut = $statut;
        $this->dateAjout = $dateAjout;
        $this->dateSuppression = $dateSuppression;
    }


    public function getId() { return $this->id; }
    public function getPrenom() { return $this->prenom; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getMotDePasse() { return $this->mot_de_passe; }
    public function getRole() { return $this->role; } 
    public function getStatut() { return $this->statut; }  
    public function getDateAjout() { return $this->dateAjout; }
    public function getDateSuppression() { return $this->dateSuppression; }  
   
}