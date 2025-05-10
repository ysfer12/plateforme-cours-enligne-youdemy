<?php
session_start();

require_once '../../../../vendor/autoload.php';
use App\Controllers\Inscription\InscriptionController;
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Auth/login.php');
    exit();
}
if (!isset($_POST['cours_id'])) {
    header('Location: cours-details.php');
    exit();
}
try {
    $controller = new InscriptionController();
    $controller->handleCourseEnrollment(
        $_SESSION['user_id'], 
        (int)$_POST['cours_id']
    );
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>