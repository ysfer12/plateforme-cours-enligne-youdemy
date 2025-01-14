<?php
namespace App\Controllers\Auth;

use App\Classes\Utilisateurs;
use App\Config\Database;
use App\Models\UserModel;
use PDO;

session_start(); // Start the session

class AuthController {
    public function login($email, $mot_de_passe) {
        $userModel = new UserModel();
        $user = $userModel->findUserByEmailAndPassword($email, $mot_de_passe);

        if ($user == null) {
            echo "User not found or invalid password. Please check your credentials.";
            return; // Exit the function if user is not found
        }

        if ($user->getStatut() !== 'actif') {
            echo "Your account is not yet activated. Please contact the administrator.";
            return; 
        }
        
        $role = $user->getRole();
        if ($role) {
            switch ($role->getTitre()) {
                case "Admin":
                    header("Location: ../admin/dashboard.php");
                    break;
                case "Enseignant":
                    header("Location: ../Enseignant/home.php");
                    break;
                case "Etudiant":
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
    public function register($prenom, $nom, $email, $mot_de_passe, $role, $status) {
        $userModel = new UserModel();
        $userModel->register($prenom, $nom, $email, $mot_de_passe, $role, $status);
    }
}