<?php
namespace App\Controllers\Etudiant;
use App\Classes\Utilisateurs;
use App\Config\Database;
use App\Models\Etudiant\UserModel; 
use PDO;

session_start(); 
class UserController {
 
    
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ../../Auth/login.php");
        exit();
    }
}