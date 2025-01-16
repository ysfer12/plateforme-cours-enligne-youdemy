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
            $_SESSION['login_error'] = "User not found or invalid password. Please check your credentials.";
            header("Location: ../Auth/login.php");
            exit();
        }
    
        if ($user->getStatut() !== 'Actif') {
            $_SESSION['login_error'] = "Your account is not yet activated. Please contact the administrator.";
            header("Location: ../Auth/login.php");
            exit();
        }
        
        // Set session variables for the user
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_prenom'] = $user->getPrenom();
        $_SESSION['user_nom'] = $user->getNom();
        
        $role = $user->getRole();
        if ($role) {
            $_SESSION['user_role'] = $role->getTitre();
            
            switch ($role->getTitre()) {
                case "Admin":
                    // Specifically set admin session variable
                    $_SESSION['admin_id'] = $user->getId();
                    header("Location: ../Admin/dashboard.php");
                    break;
                case "Enseignant":
                    header("Location: ../Enseignant/home.php");
                    break;
                case "Etudiant":
                    header("Location: ../Etudiant/home.php");
                    break;
                default:
                    $_SESSION['login_error'] = "Invalid role.";
                    header("Location: login.php");
                    exit();
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Role not found for the user.";
            header("Location: login.php");
            exit();
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