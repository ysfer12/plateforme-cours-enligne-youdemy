<?php
namespace App\Models\Etudiant;
use App\Classes\Role;
use App\Classes\Utilisateurs;
use App\Config\Database;
use PDO;

class UserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }


    public function logout() {
        session_destroy();
        header("Location: ../../Auth/login.php");
    }

}