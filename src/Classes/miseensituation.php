<?php
class Room {
    public $etudiants = [];
}


class Etudiant extends Room {

    private $nom;
    private $notes=[];

    public function __construct($nom){
        $this->nom=$nom;
    }

     public function ajouterEtudiant($etudiant){

        $this->etudiants= $etudiant;

     }
     public function ajouterNote($note){
        $this->$notes=$note;
     }
     public function calculerMoyenne(){
        $this->notes=array_sum($this->notes)/count($this->notes);
     }
    }
$etudiant1 = new Etudiant('Jean');
$etudiant2 = new Etudiant('Paul');
$etudiant3 = new Etudiant('Marie');
$etudiant4 = new Etudiant('Luc');
$etudiant5 = new Etudiant('Pierre');
