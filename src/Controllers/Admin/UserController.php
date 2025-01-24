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

    public function findUserByEmailAndPassword($email, $mot_de_passe) {
        return $this->userModel->findUserByEmailAndPassword($email, $mot_de_passe);
    }

    public function getActiveUsers($page = 1, $perPage = 10) {
        return $this->userModel->getActiveUsers($page, $perPage);
    }

    public function getTotalActiveUsers() {
        return $this->userModel->getTotalActiveUsers();
    }

    public function softDelete($userId, $date) {
        return $this->userModel->softDelete($userId, $date);
    }

    public function updateStatus($userId, $newStatus) {
        return $this->userModel->updateStatus($userId, $newStatus);
    }

    public function getUserById($userId) {
        return $this->userModel->getUserById($userId);
    }

    public function emailExists($email, $excludeUserId = null) {
        return $this->userModel->emailExists($email, $excludeUserId);
    }
}