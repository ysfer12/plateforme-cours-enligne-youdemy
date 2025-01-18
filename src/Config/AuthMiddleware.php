<?php
namespace App\Config;

class AuthMiddleware {
    public static function checkUserRole($requiredRole) {
        session_start();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            header('Location: ../Auth/login.php');
            exit();
        }

        if ($_SESSION['user_role'] !== $requiredRole) {
            header('Location: ../Auth/login.php');
            exit();
        }
    }
}
?>