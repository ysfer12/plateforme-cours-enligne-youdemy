<?php
namespace App\Controllers\Auth;

use App\Classes\User;
use App\Config\Database;
use App\Models\UserModel;
use PDO;

session_start(); // Start the session

class AuthController {
    public function login($email, $password) {
        $userModel = new UserModel();
        $user = $userModel->findUserByEmailAndPassword($email, $password);

        if ($user == null) {
            echo "User not found or invalid password. Please check your credentials.";
            return; // Exit the function if user is not found
        }

        if ($user->getStatut() !== 'isActive') {
            echo "Your account is not yet activated. Please contact the administrator.";
            return; 
        }
        
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_role'] = $user->getRole()->getTitle();


        $role = $user->getRole();
        if ($role) {
            switch ($role->getTitle()) {
                case "admin":
                    header("Location: ../admin/dashboard.php");
                    break;
                case "teacher":
                    header("Location: ../Enseignant/home.php");
                    break;
                case "student":
                    header("Location: ../Etudiant/home.php");
                    break;
                default:
                    echo "Invalid role.";
            }
        } else {
            echo "Role not found for the user.";
        }
    }
    public function logout() {
        // Destroy the session
        session_unset();
        session_destroy();
        // Redirect to login page
        header("Location: ../Auth/login.php");
        exit();
    }
    public function register($firstname, $lastname, $email, $password, $role, $status) {
        $userModel = new UserModel();
        $userModel->register($firstname, $lastname, $email, $password, $role, $status);
    }
}