<?php
namespace App\Controllers\Admin;
use App\Classes\Utilisateurs;
use App\Config\Database;
use App\Models\Admin\UserModel;
use PDO;

session_start(); 
class UserController {
 
    
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ../Auth/login.php");
        exit();
    }
}