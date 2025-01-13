<?php
namespace App\Controllers\Auth;

use App\Classes\User;
use App\Config\Database;
use App\Models\UserModel;
use PDO;

class AuthController {
    public function login($email, $mot_de_pass) {
        $userModel = new UserModel();
        $user = $userModel->findUserByEmailAndPassword($email, $mot_de_pass);

        if ($user == null) {
            echo "User not found or invalid password. Please check your credentials.";
            return; // Exit the function if user is not found
        }

        // Check if the user's account is active
        if ($user->getStatut() !== 'isActive') {
            echo "Your account is not yet activated. Please contact the administrator.";
            return; // Exit the function if the account is not active
        }

        // Proceed with role-based redirection
        $role = $user->getRole();
        if ($role) {
            switch ($role->getTitle()) {
                case "admin":
                    header("Location: ../admin/dashboard.php");
                    break;
                case "teacher":
                    header("Location: ../candidate/home.php");
                    break;
                case "student":
                    header("Location: ../recruiter/home.php");
                    break;
                default:
                    echo "Invalid role.";
            }
        } else {
            echo "Role not found for the user.";
        }
    }

    public function register($firstname, $lastname, $email, $password, $role) {
        $userModel = new UserModel();
        $userModel->register($firstname, $lastname, $email, $password, $role);
    }
}