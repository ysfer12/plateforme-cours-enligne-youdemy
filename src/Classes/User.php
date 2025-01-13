<?php

namespace App\Classes;


class User {
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $role;
    public $statut;

    
    public function __construct($id,$firstName,$lastName, $email,$password='',$role,$statut) {
            $this->id = $id;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
            $this->password = $password;
            $this->role = $role;
            $this->statut=$statut;
    }


    public function getId() { return $this->id; }
    public function getNom() { return $this->firstName; }
    public function getPrenom() { return $this->lastName; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; } 
    public function getStatut() { return $this->statut; }    
   
}